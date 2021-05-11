
jQuery(document).ready(function ($) {
    $('.slider').bxSlider({
        controls: true,
        pager: false,
        auto: true,
        autoStart: true,
        autoDelay: 2500,
        autoHover: true,
        responsive: true,
        infiniteLoop: true,
        adaptiveHeight: true,
        useCSS: false
    });
});