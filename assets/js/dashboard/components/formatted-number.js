import $ from 'jquery';

window.formatFloat = (val) => {
    if (!val) {
        return;
    }
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ").replace('.', ',');
};
window.myParseFloat = (val) => {
    return parseFloat(val.toString().replace(/\s/g, '').replace('.', ','));
};
window.roundFloat = (val) => {
    return myParseFloat((Math.round(val * 100) / 100).toFixed(2));
};
window.formatNumbers = () => {
    let inputs = $('input.format-number');
    if (0 < inputs.length) {
        inputs.keyup(function() {
            let _this = $(this);
            let val = _this.val().replace(/\s/g, '');
            if (!val) {
                return;
            }
            let formatted = formatFloat(val);
            if (formatted !== val) {
                _this.val(formatted);
            }
        }).trigger('keyup');
    }
};

formatNumbers();
