/**
 * Reusable function for using Ajax Request
 *
 * @param {object} options
 */
const ajaxRequest = (options) => {
    var defaults = {
        url: '',
        method: 'GET',
        data: {},
        headers: {},
        dataType: 'json',
        processData: true,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        beforeSendCallback: null,
        successCallback: () => {},
        errorCallback: () => {}
    };

    // Merge default options with user-provided options
    options = $.extend({}, defaults, options);

    if (options.data instanceof FormData) {
        options.processData = false;
        options.contentType = false;
    }

    $.ajax({
        url: options.url,
        method: options.method,
        data: options.data,
        headers: options.headers,
        dataType: options.dataType,
        processData: options.processData,
        contentType: options.contentType,
        beforeSend(xhr) {
            if (typeof options.beforeSendCallback === 'function') {
                options.beforeSendCallback(xhr);
            }
        },
        success(response) {
            options.successCallback(response);
        },
        error(xhr, status, error) {
            options.errorCallback(xhr, status, error);
        }
    });
};

/**
 * Set invalid class to elements for each field in the errors object
 *
 * @param {object} errors
 */
function handleValidatorErrors(errors) {

    // clear old errors
    $('input, select, textarea')
        .removeClass('is-invalid')
        .removeAttr('title');

    $('.select2-selection').removeClass('is-invalid');
    $('div.invalid-feedback').remove();

    for (let field in errors) {

        let message = errors[field];

        // target by name OR id
        let $field = $(`[name="${field}"], #${field}`);

        if ($field.length) {

            $field.addClass('is-invalid');
            $field.attr('title', message);

            // 👉 SELECT2 FIX
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container')
                    .find('.select2-selection')
                    .addClass('is-invalid');
            }

            // 👉 ERROR MESSAGE
            let errorDiv = `<div class="invalid-feedback">${message}</div>`;

            $field.closest('.col-md-6, .col-md-4, .col-12')
                    .append(errorDiv);
        }
    }
}

function clearValidationErrors() {
    $('input, select, textarea')
        .removeClass('is-invalid')
        .removeAttr('title');

    $('.select2-selection').removeClass('is-invalid');
    $('div.invalid-feedback').remove();
}

/**
 * Automatically resets all forms inside any modal when it's hidden.
 * Applies to all modals on the page.
 */
const resetModalFormValues = () => {
    // Use a delegated event to listen for any modal hidden event
    $(document).on('hidden.bs.modal', '.modal', function () {
        const $modal = $(this);
        const $form = $modal.find('form');

        if ($form.length) {
            console.log(`Resetting form inside modal: #${$modal.attr('id')}`);

            // Reset the form fields
            $form[0].reset();

            // Clear Select2 fields
            $form.find('select.select2-hidden-accessible')
                .val('')
                .trigger('change');

            // Re-enable selects if disabled
            $form.find('select').prop('disabled', false);

            // Remove validation classes and tooltips
            $form.find('input, select, textarea')
                .removeClass('is-invalid')
                .removeAttr('title');

            // Remove error feedback elements
            $form.find('div.invalid-feedback[id$="-error"]').remove();
        }
    });
};



