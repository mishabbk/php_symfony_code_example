import $ from "jquery";

$(document).ready(() => {
    const $body = $('body');

    $body.on('change', 'input[type="file"]', function (e) {
        const form = $(this).closest('form');
        const container = $(this).closest('.drag-and-drop');

        if (form.is('.automatic-upload')) {
            if (e.target.files.length) {
                form.submit();
            }
        } else {
            if (0 === container.find('ul.upload-preview').length) {
                container.append('<ul class="upload-preview list-group"/>');
            }
            const ul = container.find('ul.upload-preview');
            const uuid = getUUID();
            ul.append(getHtmlForPreview(e.target.files, uuid));
            const newInputFile = $(this).clone();
            newInputFile.val('');
            $(this).attr('data-group-id', uuid);
            $(this).hide();
            $('.custom-file').append(newInputFile);
        }
    });

    $body.on('click', '.document_action_edit',  function (e) {
        e.preventDefault();
        const parent = $(this).closest('li');
        parent.find('.documents_item').toggleClass('hide');
        parent.find('.input-group').toggleClass('hide');
    });

    $body.on('click', '.document_name_update_btn', function () {
        event.preventDefault();
        const parent = $(this).closest('li');
        const url = parent.attr('data-edit-path');
        const documentName = parent.find('.input-group input').val();
        if (url && documentName) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    'edit_document_name[name]': documentName
                },
                success: function (data) {
                    parent.find('.documents_item').html(data);
                    parent.find('.documents_item').toggleClass('hide');
                    parent.find('.input-group').toggleClass('hide');
                }
            });
        }
    });

    $body.on('click', '.document_action_remove', function (e) {
        e.preventDefault();
        const parent = $(this).closest('li');
        const documentUUID = parent.attr('data-group-id');
        if (documentUUID) {
            $('li.documents[data-group-id='+documentUUID+']').remove();
            $('input[type="file"][data-group-id='+documentUUID+']').remove();
        } else {
            parent.remove();
        }
    });

    function getHtmlForPreview(files, uuid) {
        let html = '';
        if (files.length > 1) {
            html += '<li class="documents list-group-item" data-group-id="'+uuid+'">' +
                    '   <div class="document_action d-flex align-items-center justify-content-end">' +
                    '       <a href="#" class="badge badge-danger document_action_remove not_uploaded"><i class="fa fa-trash-alt"></i> Groupe</a>' +
                    '   </div>' +
                    '</li>';
        }

        for (let i = 0; i < files.length; i++) {
            html += '<li class="documents list-group-item" data-group-id="'+uuid+'">' +
                    '   <div class="documents_item d-flex justify-content-between align-items-center">' +
                    '       <span class="document_name">'+files[i].name+'</span>';
            if (files.length === 1) {
                html += '   <div class="document_action d-flex align-items-center justify-content-end">' +
                    '           <a href="#" class="badge badge-danger document_action_remove"><i class="fa fa-trash-alt"></i></a>' +
                    '       </div>';
            }
            html += '   </div>' +
                    '</li>';
        }

        return html;
    }

    function getUUID() {
        return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }
});
