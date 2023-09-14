$(document).ready(function () {
    // header slider
    $('.header-slider .owl-carousel').owlCarousel({
        items: 1,
        loop: true,
        dots: false,
        stagePadding: 0,
        rtl: true,
        responsive: {
            // breakpoint from 0 up
            0: {
                items: 1,
                nav: false,
            },
            768: {
                items: 1,
                nav: true,
            },
        },
    });
    // opinion slider
    $('.opinion .owl-carousel').owlCarousel({
        items: 1,
        loop: true,
        rtl: true,
        dots: true,
    });

    // sponsors
    $('.sponsors .owl-carousel').owlCarousel({
        loop: true,
        rtl: true,
        dots: false,
        responsive: {
            0: {
                items: 3,
            },
            768: {
                items: 4,
            },
            992: {
                items: 6,
            },
        },
    });
});

// fixed navbar on scroll
$('header').addClass('animate__animated');
window.addEventListener('scroll', function () {
    if (window.pageYOffset >= 250) {
        $('header').css('position', 'fixed');
        $('header').addClass('animate__slideInDown');
        $('header .head').css('display', 'none');
        $('nav').css('height', 'auto');
        $('nav').css('boxShadow', '0 0 15px rgb(0 0 0 / 10%)');
    } else {
        $('header').css('position', 'static');
        $('header').removeClass('animate__slideInDown');
        $('header .head').css('display', 'block');
        $('nav').css('height', '120px');
        $('nav').css('boxShadow', 'none');
    }
});
