(function (window, document, $, undefined) {

    $(function () {
      var $modal = $('#rumor-modal');
      $('#rumor-open-dialog').on('click',function() {
        $modal.modal();
      });
      
      var startLoading = function() {
        $modal.find('.modal-content').addClass('whirl');
      }
      var endLoading = function() {
        $modal.find('.modal-content').removeClass('whirl');
      }
      $modal.find('.submit').on('click',function() {
        $modal.find('form').submit();
        startLoading();
      });
      $modal.find('.wpcf7').on('wpcf7mailsent',function() {
        endLoading();
        setTimeout(function() {
          $modal.modal('hide');
          $modal.find('.wpcf7-response-output').hide();
        },3000)
      });
      $modal.find('.wpcf7').on('wpcf7mailfailed',function() {
        endLoading();
      });
      $modal.find('.wpcf7').on('wpcf7spam',function() {
        endLoading();
      });
      $modal.find('.wpcf7').on('wpcf7invalid',function() {
        endLoading();
      });
      $modal.find('form').on('wpcf7submit',function() {
        
      });
      var fileTemplate = $modal.find('.fileupload-template').html();
      
      var $file = $modal.find('input[type="file"]').hide();
      $modal.find('.btn-file').click(function() {
        $(this).blur();
        $file.click()
      });
      
      $file.on('change',function() {
        $modal.find('.info-file').text(this.files[0].name);
      });
      
    });
  
  })(window, document, window.jQuery);