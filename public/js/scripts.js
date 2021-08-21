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


  //promoted words
  const addFormToCollection = (e) => {

    const wrap$ = $('.' + e.currentTarget.dataset.collectionHolderClass);

    const size = wrap$.find('.row').length;

    const html = wrap$.attr('data-prototype').replace(
        /__name__/g,
        size
    );

    const item$ = $(html).addClass('row');

    item$.find('.form-label-group').wrap('<div class="col"></div>');

    wrap$.append(item$);
  };

  document
      .querySelectorAll('.add_item_link')
      .forEach(btn => btn.addEventListener("click", addFormToCollection));

  for (let n = 0; n < 3; n++) {
    $('.add_item_link').trigger('click');
  }

  //promoted words end

})(jQuery);
