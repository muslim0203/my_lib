function confirmClicked(buttonId, formId) {
    document.getElementById(buttonId).addEventListener("click", function (e) {
        e.preventDefault();

        let form = document.getElementById(formId);

        let data = new FormData(form);

        if (data.get('description') === '') {
            toastr.error("Description is required");
        } else {

            $.ajax({
                url: form.getAttribute('action'),
                headers: {
                    'X-CSRF-Token': data.get('_token')
                },
                type: 'POST',
                data: {description: data.get('description')},
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: response.message,
                            icon: "success",
                            showConfirmButton: true,
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
}

function cancelClicked(buttonId, formId) {
    document.getElementById(buttonId).addEventListener("click", function (e) {
        e.preventDefault();

        let form = document.getElementById(formId);

        let data = new FormData(form);

        if (data.get('description') === '') {
            toastr.error("Description is required");
        } else {

            $.ajax({
                url: form.getAttribute('action'),
                headers: {
                    'X-CSRF-Token': data.get('_token')
                },
                type: 'POST',
                data: {description: data.get('description'), is_editable: data.get('is_editable')},
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: response.message,
                            icon: "success",
                            showConfirmButton: true,
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
}
