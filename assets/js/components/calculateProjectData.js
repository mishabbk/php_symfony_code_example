import $ from "jquery";
import 'rangeslider.js';

var calculateProjectData = {
    formatNumber: function (n) {
        return n.toLocaleString('de-DE', { maximumFractionDigits: 0 });
    },
    updateTable : function(){
        if ($('.range-box-bottom table').length) {
            $('#project-budget').text(this.formatNumber(parseInt($('.budget input#price').val(), 10)));
            $('#project-month').text(parseInt($('.budget input#month').val(), 10))
        }
    }
};

// range slider start
$(function(){
    // price
    $('.budget input#price').rangeslider({
        polyfill:false,
        onInit:function(){
            let value = parseInt($('.budget input#price').val());
            value = calculateProjectData.formatNumber(value);
            $('.header.price-header input.pull-right').val(value +' €');
            calculateProjectData.updateTable();
        },
        onSlide:function(position, value){
            $('.price-header input.pull-right').val(calculateProjectData.formatNumber(parseInt(value))+' €');
            calculateProjectData.updateTable();
        },
    });
    $('.header.price-header input.pull-right').keyup(function(e){
        let value = parseInt($(this).val().split('.').join('').replace(/\D/g,''));
        if (!isNaN(value)) {
            $(this).val(calculateProjectData.formatNumber(value));
            if (e.key === 'Enter') {
                $(this).trigger('blur');
            }
        }
    });
    $('.header.price-header input.pull-right').blur(function(e){
        let value = $('.price-header input.pull-right').val().split('.').join('');
        $('.budget input#price').val(parseInt(value)).trigger('change');
    });

    // month
    $('.budget input#month').rangeslider({
        polyfill:false,
        onInit:function(){
            $('.header.month-header .pull-right').val(parseInt($('.budget input#month').val())+' '+monthsLabel);
            calculateProjectData.updateTable();
        },
        onSlide:function(position, value){
            $('.month-header .pull-right').val(parseInt(value)+' '+monthsLabel);
            calculateProjectData.updateTable();
        },
    });
    $('.header.month-header input.pull-right').keyup(function(e){
        if (e.key === 'Enter') {
            $(this).trigger('blur');
        }
    });
    $('.header.month-header input.pull-right').blur(function(e){
        //var val = $(this).val().replace(/\D/g,'');   // check only for digits
        $('.budget input#month').val(parseInt($('.month-header input.pull-right').val())).trigger("change");
    });
});// JavaScript Document
