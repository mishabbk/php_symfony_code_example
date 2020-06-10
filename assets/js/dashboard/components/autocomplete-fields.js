import $ from 'jquery';

window.selectable = (select) => {
    let options = {
        dropdownAutoWidth: true,
        width: '100%'
    };
    const route = select.data('route');
    if (route) {
        options.ajax = {
            url: route,
            data: function(params) {
                const selectRefreshed = $('#' + select.attr('id'));
                if (selectRefreshed.length && selectRefreshed.data('route-datas')) {
                    params = Object.assign(params, JSON.parse(selectRefreshed.data('route-datas')));
                }

                return params;
            },
            dataType: 'json',
            delay: 1000,
        };
        select.change(function() {
            let value = select.val();
            if (Array.isArray(value)) {
                value = JSON.stringify(value);
            }
            select.closest('.choice-remote-container').find('input').val(value);
        });
    }
    select.select2(options);
};

$(document).ready(function() {
    const selects = $('select');
    if (selects.length) {
        for (let i = 0; i < selects.length; i++) {
            selectable(selects.eq(i));
        }
    }
});
