(function($) {
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  $(document).on('click', 'a[data-module-delete="1"]', function (e) {

    e.preventDefault();

    let this$ = $(this);

    $.get(
        this$.attr('href'),
        {},
        function (data) {
          if (data && typeof data.success !== 'undefined' && data.success) {
            this$.closest('tr').remove();
          }
        },
        'json'
    );
  });

})(jQuery);
