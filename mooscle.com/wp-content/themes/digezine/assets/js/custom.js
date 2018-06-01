
(function (window, document, $, undefined) {

    
    var safari = false;
    if (
      navigator.userAgent.indexOf('Safari') > -1 &&
      navigator.userAgent.indexOf('Chrome') == -1
    ) {
      safari = true;
    }
  function is_touch_device() {
    return 'ontouchstart' in window // works on most browsers 
      ||
      navigator.maxTouchPoints; // works on IE10/11 and Surface
  };

  function ellipsizeTextBox(el) {
    //return;
    if (!el) {
      return
    }
    var wordArray = el.innerHTML.split(' ');
    while (wordArray.length > 0 && (el.scrollHeight) - 3 > el.offsetHeight) {
      wordArray.pop();
      el.innerHTML = wordArray.join(' ') + '...';
    }
  }
  

  if (is_touch_device()) {
    $('html').addClass('touch');
  } else {
    $('html').addClass('no-touch');
  }

$(window).load(function() {
    var $elipsize = $('.elipsize');
    $elipsize.each(function (i, post) {
      var $self = $(this);
      if ($self.closest('.elipsize-wrapper')) {
        var usedHeight = 0;
        $self.siblings().each(function() {
          usedHeight += $(this).outerHeight(true);
        });
        $self.height($self.closest('.elipsize-wrapper').height() - usedHeight);
      }
      var $content = $self.find('.elipsize-content');
      if ($content.length) {
        $content.height($self.height());
      }
      ellipsizeTextBox($content[0] || this);
    });
})
  $(function () {
    $('.share-btns__item.toggle-item').on('click', function () {
      var $self = $(this);
      $self.closest('.share-btns__list').toggleClass('open');
      return false;
    });

    $('.desktop-open').on('click',function() {
      var $self = $(this);
      var $block = $self.closest('.desktop-limin-height');
      $block.toggleClass('desktop-closed').toggleClass('desktop-opened');
      $self.find('i').toggleClass('icon-arrow-down').toggleClass('icon-arrow-up');
      return false;
    });
    $('.desktop-limin-height').each(function() {
      var $self = $(this);
      //23???
      if ((this.scrollHeight - 23) <= $self.height()) {
        $self.addClass('no-control');
      } else {
        $self.addClass('has-control');
      }
    });

  });


})(window, document, window.jQuery);