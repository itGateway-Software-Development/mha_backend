@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="custom-header">
            {{-- <span style="color: #eb9c00; font-weight:bold;">{{ isset($zoneName) ? $zoneName.' Hotels' : trans('cruds.hotel.title') }} {{ trans('global.list') }} </span> --}}
            @if (isset($zoneName))
                <div><span style="color: #eb9c00; font-weight:bold; font-size: 17px;">{{ $zoneName }}</span> Hotels List
                </div>
            @endif

            <div style="font-weight: bold; color: rgb(21, 94, 190);">Total - {{ count($hotels) }}</div>

            @can('hotel_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <button data-bs-toggle="modal" data-bs-target="#excelImport" type="button" class="btn btn-warning">Import</button>
                        <a class="btn success-btn" href="{{ route('admin.hotels.create') }}?zone={{ $zoneName }}">
                            {{ trans('global.add') }} {{ trans('cruds.hotel.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
        </div>

        <!-- excel import popup -->
        <div class="modal fade" id="excelImport" tabindex="-1" aria-hidden="true">
            <div class=" modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fw-bold" id="exampleModalLabel4">Hotels Import</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="hotelImportForm">
                            <div class="form-group mb-4">
                                <label for="">Excel to import</label>
                                <input type="file" class="form-control mt-2 import_file" name="import_file"
                                    accept=".xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            </div>
                            <button class="btn btn-warning">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- excel import popup -->

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Hotel">
                    <thead>
                        <tr>
                            <th>
                                {{ trans('cruds.hotel.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.hotel.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.hotel.fields.owner') }}
                            </th>
                            <th>
                                {{ trans('cruds.hotel.fields.image') }}
                            </th>
                            <th>
                                {{ trans('cruds.hotel.fields.sr_no') }}
                            </th>
                            <th>
                                {{ trans('cruds.hotel.fields.phone') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hotels as $key => $hotel)
                            <tr data-entry-id="{{ $hotel->id }}">

                                <td>
                                    {{ $hotel->id }}
                                </td>
                                <td>
                                    {{ $hotel->name ?? '' }}
                                </td>
                                <td>
                                    {{ $hotel->owner ?? '' }}
                                </td>
                                <td>
                                    @if (isset($hotel->image))
                                        <img style="width: 100%; height: 100px; object-fit: contain;"
                                            src="{{ asset('storage/images/' . $hotel->image) }}" alt="">
                                    @else
                                        <img style="width: 100%; height: 100px; object-fit: contain;"
                                            src="{{ asset('storage/default.jpg') }}" alt="">
                                    @endif
                                </td>
                                <td>
                                    {{ $hotel->sr_no ?? '' }}
                                </td>
                                <td>
                                    {{ $hotel->phone ?? '' }}
                                </td>
                                <td class="text-nowrap">
                                    @can('hotel_show')
                                        <a class="p-0 glow"
                                            style="width: 26px;height: 36px;display: inline-block;line-height: 36px;color:grey;"
                                            href="{{ route('admin.hotels.show', $hotel->id) }}">
                                            <i class='bx bx-show'></i>
                                        </a>
                                    @endcan

                                    @can('hotel_edit')
                                        <a class="p-0 glow"
                                            style="width: 26px;height: 36px;display: inline-block;line-height: 36px;color:grey;"
                                            href="{{ route('admin.hotels.edit', $hotel->id) }}">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                    @endcan

                                    @can('hotel_delete')
                                        <form id="orderDelete-{{ $hotel->id }}"
                                            action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST"
                                            style="display: inline-block;" onsubmit="return showConfirmation()">
                                            @csrf
                                            @method('delete')
                                            <button onclick="event.preventdefault()"
                                                style="width: 26px;height: 36px;display: inline-block;line-height: 36px;border:none;color:grey;background:none;"
                                                class=" p-0 glow">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            });
            let table = $('.datatable-Hotel:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })

        function showConfirmation() {
            if (confirm('Are you sure you want to submit the form?')) {
                return true;
            } else {
                return false;
            }
        }

        $(document).ready(function() {
            //submit excel import file
            $(document).on("submit", "#hotelImportForm", function (e) {
                e.preventDefault();

                let file = $('.import_file').val();

                if(file == null || file == '') {
                    warning_alert('Please import file');
                    return;
                }

                let formData = new FormData($("#hotelImportForm")[0]);

                if (formData) {
                    $.ajax({
                        url: "/admin/hotels-import",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (res) {
                            if (res == "success") {
                                $("#excelImport").modal("hide");
                                toast_success("Succefully Imported");
                                setTimeout(() => location.reload(), 1000);
                            } else if (res == "fail") {
                                toast_error("Something wrong !");
                            } else {
                                toast_error(res);
                            }
                        },
                    });
                }
            });
        })
    </script>
@endsection
