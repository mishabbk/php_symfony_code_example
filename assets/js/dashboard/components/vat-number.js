import $ from 'jquery';

$(document).ready(function() {
    const vatNumbers = $('.vat-number-container');
    if (!vatNumbers.length) return;
    for (let i = 0; i < vatNumbers.length; i++) {
        const input = vatNumbers.eq(i).find('input');
        const link = vatNumbers.eq(i).find('a');
        link.click(function() {
            let href;
            const num = input.val();
            if (num) {
                href = 'https://ec.europa.eu/taxation_customs/vies/vatResponse.html?memberStateCode=' + num.substring(0, 2) + '&number=' + num.substring(2, 100) + '&traderName=';
            } else {
                href = 'https://ec.europa.eu/taxation_customs/vies/';
            }
            $(this).attr('href', href);
        });
    }
});
