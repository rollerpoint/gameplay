(function(window, document, $, undefined) {

    $(function() {
	if (location.pathname != "/wp-admin/post-new.php") {
	    $('#twp_send_to_channel_no').click();
	}
      var $form = $('#posts-filter');
      if ($form.length) {
        $form.find('select.filter').each(function(i,select) {
          var $select = $(select);
          var $hidden = $select.closest('form').find('[name="' + $select.attr('name').split('-')[0] + '"]')
          if ($hidden.val()) {
            $select.val($hidden.val().split(','));
          }
          $select.chosen();
          $select.on('change',function() {
            if ($select.val()) {
              $hidden.val($select.val().join(','));
            } else {
              $hidden.val('');
            }
          })
        });
      }

    });

})(window, document, window.jQuery);