$(document).ready(function () {
    let $body = $('body');

    $body.on('change', '.js-automatic-select', function (e) {
        let form = $(this).closest('form');
            $.ajax({
                url : form.attr('action'),
                type: form.attr('method'),
                data: form.serializeArray(),
                error: function (error) {
                    $('.js-select-block').html(error.responseText);
                }
            });
    });

    $body.on('click', '.js-modal-document',  function () {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            data: {},
            type: 'POST',
            success: function(data) {
                $('#modalEditDocument').find('.card-body--project-document').replaceWith(data);
                $('#modalEditDocument').modal('show');
            }
        });
    });

    $body.on('click', '.js-edit-document', function () {
        event.preventDefault();

        let form = $(this).closest('form');

        $.ajax({
            url : form.attr('action'),
            type: form.attr('method'),
            data: form.serializeArray(),
            success: function(data) {
                let documentId = data.value;

                $(document).find('.js-doc-name-' + documentId + ' a').text(data.name);
                $(document)
                    .find('.js-doc-type-' + documentId + ' option[value=' + data.type + ']')
                    .prop('selected', 'selected');
                $('#modalEditDocument').modal('hide');
            },
        });
    })
});