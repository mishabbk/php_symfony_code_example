import $ from 'jquery';

import 'bootstrap/js/dist/dropdown';
import 'popper.js/dist/popper';

$(document).ready(() => {
    const container = $('.nav.nav-tabs');
    const items = container.find('.nav-item:not(.dropdown)');
    let dropdownItemHtml = '<li class="nav-item dropdown">' +
        '<a class="nav-link dropdown-toggle " data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false"></a>' +
        '<div class="dropdown-menu dropdown-menu-right">';
    items.find('> a').each((i, item) => {
        let classes = 'dropdown-item';
        if ($(item).hasClass('active')) {
            classes += ' active';
        }
        dropdownItemHtml += '<a class="'+classes+'" href="'+$(item).attr('href')+'">'+$(item).text()+'</a>';
    });
    dropdownItemHtml += '</div></li>';
    container.append(dropdownItemHtml);

    const dropdownContainer = container.find('.nav-item.dropdown');
    const dropdownItems = dropdownContainer.find('.dropdown-menu > a');

    const resizeTabs = () => {
        const dropdownWidth = dropdownContainer.width();
        let stopWidth = dropdownWidth;
        let hiddenItems = [];
        const containerWidth = container.width();
        items.each((i, item) => {
            $(item).show();
            if (containerWidth >= (stopWidth + $(item).width())) {
                stopWidth += $(item).width();
            } else if (
                hiddenItems.length === 0
                && (items.length - 1) === i
                && (containerWidth + dropdownWidth) >= (stopWidth + $(item).width())
            ) {
                stopWidth += $(item).width();
            } else {
                $(item).hide();
                hiddenItems.push(i);
            }
        });
        if (!hiddenItems.length) {
            dropdownContainer.hide();
        }
        else {
            dropdownContainer.show();
            dropdownItems.each((i, item) => {
                if (!hiddenItems.includes(i)) {
                    $(item).hide();
                } else{
                    $(item).show();
                }
            })
        }
    };

    resizeTabs();

    $(window).resize(() => {
        resizeTabs();
    });
});
