<?php

namespace App\Imports;

use App\Models\Zone;
use App\Models\Hotel;
use App\Models\SubZone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportHotel implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $hotel = new Hotel();
        $hotel->name = $row["hotel"];
        $hotel->owner = $row["owner_name"];
        $hotel->sr_no = $row["sr_name"];
        $hotel->total_room = $row["no_of_room"] ?? '0';
        $hotel->phone = $row["phone"] ?? '-';
        $hotel->email = $row["e_mail"] ?? '-';
        $hotel->address = $row["address"] ?? '-';
        $hotel->web_link = $row["website"];
        $hotel->name = $row["hotel"];
        $hotel->sub_zone_id = SubZone::where('name', $row['subzone'])->first()->id;
        $hotel->zone_id = Zone::where('name', $row['zone'])->first()->id;
        $hotel->save();

        return $hotel;

    }
}
