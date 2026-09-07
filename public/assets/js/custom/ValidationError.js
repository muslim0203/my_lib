function validationError(formId, buttonId) {
    console.log(formId)
    // Define form element
    const form = document.getElementById(formId);

    var data = new FormData(form);

    var formElements = [];
    var validationMessages = [];

    for (var [key, value] of data) {
        if (key === '_token') {
            continue;
        }

        var id = form.querySelector('input[name="' + key + '"]').getAttribute('id');

        if (form.querySelector("label[for='" + id + "']").classList.contains('required')) {
            formElements.push(key);
            validationMessages.push(form.querySelector("label[for='" + id + "']").getAttribute('data-value'));
        }
    }

    // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
    var validator = FormValidation.formValidation(form, {
        plugins: {
            trigger: new FormValidation.plugins.Trigger(), bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row', eleInvalidClass: '', eleValidClass: ''
            })
        }
    });

    for (let i = 0; i < formElements.length; i++) {
        let notEmpty = {
            message: validationMessages[i] ?? 'Text input is required'
        }

        let extraValidator = {
            validators: {
                notEmpty: notEmpty
            }
        }

        validator.addField(formElements[i], extraValidator)

    }

    // Submit button handler
    const submitButton = document.getElementById(buttonId);
    submitButton.addEventListener('click', function (e) {
        // Prevent default button action
        e.preventDefault();

        // Validate form before submit
        if (validator) {
            validator.validate().then(function (status) {

                if (status == 'Valid') {

                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    form.submit();
                }
            });
        }
    });
}
