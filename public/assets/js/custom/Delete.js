/**
 * Admin panelidagi o'chirish tugmalari.
 *
 * Marshrutlar GET dan DELETE ga o'tkazilgandan keyin so'rov kontrollerga
 * yetib boradi. Kontrollerlarning bir qismi JSON (`Success::send`), bir
 * qismi esa redirect qaytaradi. Ilgari faqat JSON kutilgani uchun
 * redirect qaytargan sahifalarda o'chirish bajarilsa ham qizil "xato"
 * oynasi chiqardi.
 */
function remove(className, title, text, deleteBtnText, cancelBtnText, successText) {
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
                        // Faqat aniq `success: false` xato hisoblanadi.
                        // Redirect yoki bo'sh javob - muvaffaqiyat (2xx).
                        let failed = response
                            && typeof response === 'object'
                            && response.success === false;

                        Swal.fire({
                            title: failed
                                ? (response.message || text)
                                : ((response && response.message) || successText || title),
                            icon: failed ? "error" : "success",
                            showConfirmButton: failed,
                            timer: failed ? undefined : 1000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        // Ilgari xato holati umuman qayta ishlanmasdi:
                        // 403/419 da tugma jimgina hech nima qilmasdi.
                        let message = (xhr.responseJSON && xhr.responseJSON.message)
                            || (xhr.status === 419 ? 'CSRF token expired' : null)
                            || (xhr.status === 403 ? 'Forbidden' : null)
                            || ('HTTP ' + xhr.status);

                        Swal.fire({
                            title: message,
                            icon: "error"
                        });
                    }
                })
            }
        })
    });
}
