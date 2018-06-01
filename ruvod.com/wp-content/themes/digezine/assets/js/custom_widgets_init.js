(function (window, document, $, undefined) {

  $(window).load(function () {
    //return;
    $(".widget-owl-carousel").removeClass('not-owl').addClass('owl-carousel owl-theme').owlCarousel({
      loop: true,
      autoplay: true,
      autoplayTimeout: 10000,
      margin: 1,
      nav: true,
      dots: false,
      autoHeight: true,
      navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
      responsive: {
        0: {
          items: 1
        },
        600: {
          items: 2
        },
        1000: {
          items: 3
        }
      }
    });
  });


})(window, document, window.jQuery);