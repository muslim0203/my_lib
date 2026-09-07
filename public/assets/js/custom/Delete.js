function remove(className, title, text, deleteBtnText, cancelBtnText) {
    $('a.' + className).click(function (e) {
        e.preventDefault()

        Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: deleteBtnText,
            cancelButtonText: cancelBtnText
        }).then((result) => {
            if (result.isConfirmed) {
                let url = $(this).attr('data-action');
                let csrf = $(this).attr('data-value');

                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrf
                    },
                    method: 'delete',
                    url: url,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: response.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                location.reload();
                            })
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: "error"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                })
            }
        })
    });
}
