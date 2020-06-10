import $ from 'jquery';
import moment from 'moment';

window.applyDatePicker = (elems) => {
    if(0 === elems.length){
        return;
    }
    const elements = $(elems);
    for (let i = 0; i < elements.length; i++) {
        let _this = elements.eq(i);
        let timePicker = !!_this.data('timepicker');
        let dateFormat = timePicker ? 'DD/MM/Y HH:mm' : 'DD/MM/Y';
        let options = {
            autoUpdateInput: !!_this.attr('required'),
            timePicker: timePicker,
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2020,
            maxYear: parseInt(moment().format('YYYY'), 10) + 1,
            locale: {
                format: dateFormat,
                separator: " - ",
            }
        };
        if (_this.data('min-date')) {
            options.minDate = _this.data('min-date');
        } else {
            options.minDate = '01/01/1900';
        }
        if (_this.data('max-date')) {
            options.maxDate = _this.data('max-date');
        }
        _this.daterangepicker(options);
        _this.on('cancel.daterangepicker', function(ev, picker) {
            _this.val('');
        });
        _this.on('apply.daterangepicker', function(ev, picker) {
            _this.val(picker.startDate.format(dateFormat));
        });
    }
};

applyDatePicker($('input.daterangepicker-input'))

let daterangeInputs = $('input.daterange');
if (0 < daterangeInputs.length) {
    for (let i = 0; i < daterangeInputs.length; i++) {
        let _this = daterangeInputs.eq(i);
        let dateFormat = 'Y-MM-DD';
        let options = {
            autoUpdateInput: !!_this.attr('required'),
            showDropdowns: true,
            minYear: 2020,
            maxYear: parseInt(moment().format('YYYY'), 10) + 1,
            locale: {
                format: dateFormat,
                separator: " - ",
            }
        };
        if (_this.data('min-date')) {
            options.minDate = _this.data('min-date');
        } else {
            options.minDate = '1900-01-01';
        }
        _this.daterangepicker(options);
        _this.on('cancel.daterangepicker', function(ev, picker) {
            _this.val('');
        });
        _this.on('apply.daterangepicker', function(ev, picker) {
            _this.val(picker.startDate.format(dateFormat) + ' - ' + picker.endDate.format(dateFormat));
        });
    }
}
