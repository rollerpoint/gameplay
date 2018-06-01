(function(window, document, $, undefined) {

    $(function() {

      var $translate_modal = $('#translate-modal');
      var $button = $('#translate-modal-open');
      var $modal_content = $translate_modal.find('.modal-body');
      $button.on('click',function() {
        $translate_modal.modal();
        var content = tinymce.activeEditor.getContent();
        var title = '<h1>' + $('#title').val() + '</h1>';
        $modal_content.html('<h3 style="text-align:center">... Загрузка ...</h3>');
        $.ajax('admin-ajax.php', {
          type:'POST',
          dataType:'html',
          data:{
            action:'translate',
            content: title + content,
            content_length: (title + content).length
          }
        }).done(function(result) {
          console.log(result);
          $modal_content.html(result);
        }).fail(function(err) {
          console.log(err);
          $modal_content.html('<h3 style="text-align:center; color:red;">Ошибка перевода</h3>');
        });
      });
      // $('.post-type-post #titlediv #title').on('change',function(e) {
      //   if ($(this).val()) {
      //     $(this).val($(this).val().toUpperCase())
      //   }
      // });

      // 650x490
      var checkTags = function() {
        var tags = $('#new-tag-post_tag').val();
        if (tags.indexOf("Featured_Posts_Block") > -1) {
          alert('Для тега Featured_Posts_Block требуется размер миниатюры не менее 650x490!');
        }
      }
      $('.ajaxtag').on('keydown', '.newtag', function (e) {
        console.log(e);
        if (13 === e.keyCode) {
              checkTags();
          }
      });
      $('.ajaxtag .tagadd').on('click',function() {
        checkTags();
      });
    });

})(window, document, window.jQuery);