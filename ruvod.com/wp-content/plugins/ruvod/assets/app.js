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
    });

})(window, document, window.jQuery);