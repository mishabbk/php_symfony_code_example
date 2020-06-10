import $ from "jquery";

$(document).ready(function() {
    $('.btn-choice-group').unbind('click').click(function(e) {
        e.preventDefault();
        let _this = $(this);
        if (_this.is('.disabled')) return;
        let parent = _this.closest('.btn-choice-container');
        let input = parent.find(_this.data('target'));
        parent.find('input').prop('checked', false);
        input.prop('checked', true).trigger('change');
        parent.find('.btn-primary').toggleClass('btn-primary btn-default');
        _this.addClass('btn-primary').removeClass('btn-default');
    });
});
