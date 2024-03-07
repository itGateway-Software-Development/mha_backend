'use strict';

//sweetalert warning message
let warning_alert = (text) => {
    Swal.fire({
        title: text,
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "OK",
        customClass: {
            confirmButton: "d-none",
            cancelButton: "btn btn-outline-primary waves-effect",
        },
    });
};

//Toast message
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

//Toast message - success
let toast_success = (text) => {
    Toast.fire({
        icon: "success",
        title: text,
    });
};

//Toast message - error
let toast_error = (text) => {
    Toast.fire({
        icon: "error",
        title: text,
    });
};
