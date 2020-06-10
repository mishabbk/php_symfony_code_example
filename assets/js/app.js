// ------ style ------
import '../css/app.scss';

// ------ jquery and bootstrap basics ------
import $ from 'jquery'
// make jQuery global
global.$ = global.jQuery = $;

// ------ app ------
import './components/uploadFiles';

$(document).ready(function(){
    $('.hamber').click( function(){
        $('.hamber').toggleClass('k-n');
        $('nav').toggleClass('zoom-in');
    });
    $('.view').click(function(e) {
        e.stopPropagation();
        $('.teaser-full').slideToggle(300);
        $(this).toggleClass('open');
    });

    const rgpd = $('#rgpd');
    if(rgpd.length){
        rgpd.find('.close-bloc').click(function(){
            rgpd.remove();
        });
    }
});

$(window).scroll(function(){
    var sticky = $('.kb-header'),
        scroll = $(window).scrollTop();

    if (scroll >= 100) sticky.addClass('header-fixed');
    else sticky.removeClass('header-fixed');
});
