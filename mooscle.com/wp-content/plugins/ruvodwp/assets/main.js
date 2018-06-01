(function(window, document, $, undefined) {



  $.urlParam = function (name) {
      var results = new RegExp('[\?&]' + name + '=([^&#]*)')
                        .exec(window.location.href);

      return results && (results[1] || 0);
  }


    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip({
        container:'body'
      }); 
      $('[data-toggle="popover"]').popover({
        container:'body'
      }); 
    });


    
    $(function() {
      var current_language = 'ru';
      try {
        current_language = $('html').attr('lang').split('-')[0];
      } catch(err) {
        console.warn(err);
      }


      $('#current_employment').on('change',function() {
        var $self = $(this);
        var $form = $self.closest('form');
        console.log($self.val());
        console.log($form.find('.nested-container .nested-item').length);
        if ($self.val() == 2) {
          if ($form.find('.nested-container .nested-item').length == 0) {
            $form.find('.nested-add').click();
          }
        }
      });


      var $answerModal = $('.answer-vacancy-modal');

      $('.answer-vacancy').on('click',function() {
        $answerModal.modal();
      });
      if ($answerModal.find('.login').length) {
        // save fore register action
        $.ajax('/wp-admin/admin-ajax.php',{
          type:'POST',
          data:{
            action:"save_answer_modal_login_redirect",
            href:location.href
          }
        });
        // change for simpel login
        $answerModal.find('[name="redirection_url"]').val(location.href+"?#answer-vacancy")
      } else if (!$answerModal.find('.cv-select').length) {
        // nothing
      }
      $answerModal.find('.modal-footer .btn').on('click',function() {
        $answerModal.find('form .submit').click();
      });

      $('.datepicker').each(function() {
        $(this).datepicker({
          language: current_language,
          todayHighlight:$(this).data('todayHighlight'),
          autoclose:true,
          startView: $(this).data('start-view') || 0,
          format: $(this).data('format'),
          minViewMode: $(this).data('min-view-mode'),
          templates:{
            leftArrow:"<i class='fa fa-angle-left'></i>",     
            rightArrow:"<i class='fa fa-angle-right'></i>"
          }
        })
      });

      if ($answerModal.length && location.hash.indexOf('answer-vacancy') > -1) {
        history.scrollRestoration = 'manual';
        $('html').scrollTop($('.answer-vacancy').offset().top - 300);
        $answerModal.modal();
        history.replaceState({}, $('title').text(), location.pathname);
      }

      var $memberModal = $('#member-modal');
      $('.company-users .add-user').on('click',function() {
        $memberModal.modal();
      });
      $memberModal.find('.modal-footer .btn').on('click',function() {
        $memberModal.find('.active form').find('.submit').click();
      });


      $('.wp-chose-image').each(function() {
        var $self = $(this);
        var size = $self.data('size') || 'post-thumbnail'
        $self.on('click',function() {
          var dlg = wp.media({
              title: 'Выбор изображения',
              multiple : false,
              library : {
                  type : 'image',
              }
          })
          dlg.on('close',function() {
            var selection =  dlg.state().get('selection');
            var attachment = null;
            
            selection.each(function(attach) {
              attachment = attach.attributes
            });
            $self.find('span').text(attachment.filename);
            $self.find('input').val(attachment.id);
            $self.find('img').removeAttr('srcset');
            $self.find('img').attr('src',attachment.sizes[size].url);
            $self.find('.action-info').text($self.find('.action-info').data('change-text'));
          });
          dlg.open()
          // wp.media.editor.send.attachment = function(props, attachment) {
          //   console.log(props, attachment);
          //   $self.find('input').val(attachment.id);
          //   $self.find('img').removeAttr('srcset');
          //   $self.find('img').attr('src',attachment.url);
          // };
          // wp.media.editor.open($(this));
        });
      });
      $('.chosen').each(function() {
        var $select = $(this);
        var $wrapper = $select.closest('.select-wrap');
        if ($select.hasClass('multiple')) {
          $select.attr('multiple',true);
        }
        $select.chosen({
          placeholder_text_multiple: $select.data('placeholder'),
          width: '100%',
          disable_search: $select.data('disable-search'),
          no_results_text: $select.data('no-results-text')
        });
      });
      $('select[data-onchange-click]').change(function() {
        var $this = $(this);
        var $target = $($this.data('onchange-click'));
        
        
        setTimeout(function() {
          $target.click();
        },1);
      });
      
      $('form.loader-form').on('submit',function() {
        $(this).closest('.loader').addClass('whirl');
      });

      var $subscribeModal = $('#subscribe-modal');
      $('#subscribe-open-dialog').on('click',function() {
        $subscribeModal.modal();
      });
      
      var startLoading = function() {
        $subscribeModal.find('.modal-content').addClass('whirl');
      }
      var endLoading = function() {
        $subscribeModal.find('.modal-content').removeClass('whirl');
      }
      $subscribeModal.find('.submit').on('click',function() {
        $subscribeModal.find('form').submit();
        startLoading();
      });
      $subscribeModal.find('.wpcf7').on('wpcf7mailsent',function() {
        endLoading();
        setTimeout(function() {
          $subscribeModal.modal('hide');
          $subscribeModal.find('.wpcf7-response-output').hide();
        },3000)
      });
      $subscribeModal.find('.wpcf7').on('wpcf7mailfailed',function() {
        endLoading();
      });
      $subscribeModal.find('.wpcf7').on('wpcf7invalid',function() {
        endLoading();
      });
      $subscribeModal.find('form').on('wpcf7submit',function() {
        
      });

      var $donateModal = $('#donateModal');
      $('.donate-open-dialog').on('click',function() {
        $donateModal.modal();
      });
      $donateModal.find('.modal-footer').on('click',function() {
        $donateModal.find('form .submit').click();
      });
      $donateModal.find('form').on('submit',function() {
        $donateModal.find('.modal-content').addClass('whirl');
      });
      
      var $expertModal = $('#experCommentModal');
      var $modalSuccess = $('.modal-success');
      if ($modalSuccess.length) {
        $expertModal.modal();
      }
      
      $(window).load(function() {
        if (location.hash.indexOf('expert-comments') > -1) {
          $('html').scrollTop($('.expert-comments').offset().top - 100);
        }
        if (location.hash.indexOf('expert-comment-form') > -1) {
          $('html').scrollTop($('.expert-comment-form').offset().top - 100);
        }
  
        if ($.urlParam('expert_token') || $.urlParam('utm_source') || $.urlParam('from')) {
          var expert_token = $.urlParam('expert_token');
          history.replaceState({}, $('title').text(), location.pathname);
        }
      })

      

      $('.form-file').each(function() {
        var $formfile = $(this);
        var $btn = $formfile.find('.btn-file');
        var $input = $formfile.find('input');
        var $text = $formfile.find('.file-name');
        $formfile.closest('.form-file-wrap').find('img').on('click',function() {
          $input.click();
        }).css('cursor','pointer');
        $btn.on('click',function() {
          $input.click();
        });
        $text.on('click',function() {
          $input.click();
        });
        $input.on('change',function() {
          $text.text(this.files[0] ? this.files[0].name : $text.data('text'))
        })
      });
      if ($('.on-load-scroll').length) {
        history.scrollRestoration = 'manual';
        $('html').animate({scrollTop:$('.on-load-scroll').offset().top - 100});
      }
        
      var isCompany = $('#account-form').find('#account-is-company');
      var companyInputHolder = $('#account-form').find('#company-wrapper');
      isCompany.on('change',function() {
        companyInputHolder.toggle(isCompany.prop('checked'));
      });
      isCompany.change();
      
        var initTypeahead = function($container) {
          $container.find('.typeahead').each(function(i, input) {
            var $input = $(input);
            var src = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              remote: {
                url: $input.data('source') + '&q=%QUERY',
                wildcard: '%QUERY'
              }
            });
            $input.typeahead({
              hint: true,
              highlight: true,
              minLength: 1
            },{
              limit: 100,
              name: 'items',
              display: 'name',
              valueKey: 'name',
              source: src
            });
          });
        };
        initTypeahead($('body'));
        $('.tagsinput').each(function(i, input) {
          var $input = $(input);
          var src = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
              url: $input.data('source') + '&q=%QUERY',
              wildcard: '%QUERY'
            }
          });
          $input.materialtags({
              typeaheadjs: {
                limit: 100,
                name: 'items',
                display: 'name',
                valueKey: 'name',
                source: src
              },
              confirmKeys: [9,13, 44],
              maxTags:$input.data('max-tags'),
              // itemText: $input.data('item-text'),
              // itemValue: $input.data('item-value'),
              freeInput: true
          });
          var is_init = false;
          if ($input.data('submit')) {
            $input.on('itemAdded', function(event) {
              if (is_init) {
                return;
              }
              $input.closest('form').find('.submit').trigger('click');
            });
            $input.on('itemRemoved', function(event) {
              if (is_init) {
                return;
              }
              $input.closest('form').find('.submit').trigger('click');
            });
          }
          if ($input.data('items')) {
            is_init = true;
            $input.data('items').forEach(function(item) {
              $input.tagsinput('add', item);
            });
            is_init = false;
          }
          
        });
        
        $('#salary_by_contract').on('change',function() {
          var $self = $(this);
          if ($self.prop('checked')) {
            $self.closest('form').find('.salary-wrapper input').attr('disabled',true);
          } else {
            $self.closest('form').find('.salary-wrapper input').removeAttr('disabled');
          }
        });
        $('#salary_by_contract').trigger('change');
        
        var $forms = $('.ajax-form');
        var startLoading = function($form) {
          var $loader = $form.find('.loader');
          if (!$loader.length) {
            $loader = $form.closest('.loader');
          }
          console.log($loader);
          if ($loader.hasClass('loader-mini')) {
            $loader.show();
          } else {
            $loader.addClass('whirl');
          }
        }
        var endLoading = function($form) {
          var $loader = $form.find('.loader');
          if (!$loader.length) {
            $loader = $form.closest('.loader');
          }
          if ($loader.hasClass('loader-mini')) {
            $loader.hide();
          } else {
            $loader.removeClass('whirl');
          }
        }

        
        $forms.each(function(i,form) {
          var $form = $(form);
          var $submit = $form.find('[type="submit"]');
          var url = $form.attr('action');
          var method = $form.attr('post');
          var $error = $form.find('.alert-danger');
          var $success = $form.find('.alert-success');
          var process = false;
          if ($form.hasClass('has-nested')) {
            var $template = $form.closest('.form-wrapper').find('.nested-template');
            $form.find('.nested-add').on('click',function() {
              var t = $template.html();
              t = t.replace(/{{index}}/g,(new Date()).getTime() + '');
              var $item = $(t);
              $item.appendTo($form.find('.nested-container'));
              $item.find('.is-typeahead').addClass('typeahead');
              initTypeahead($item);
              $(this).blur();
              return false;
            });
            $form.on('click','.nested-remove',function(e) {
              $(e.target).closest('.nested-item').remove();
              return false;
            });
          }
          $form.ajaxForm({
            beforeSerialize: function($form, options) { 
              try {
                tinyMCE.triggerSave();
              } catch(err) {
                
              }        
            },
            beforeSubmit: function(arr, $form, options) {
              console.log(arr, $form, options);
              $submit.attr('disabled',true);
              if (process) {
                return false;
              }
              startLoading($form);
              process = true;
              $error.hide();
              $success.hide();
            },
            success:function(data) {
              console.log(data);
              endLoading($form);
              if (data.status == 'ok') {
                if (data.message && $success.length) {
                  $success.text(data.message);
                  $success.show();
                  if ($form.closest('.modal').length == 0) {
                    $('html').animate({scrollTop:$success.offset().top - 100});
                  }
                }
                if (data.redirect) {
                  console.log(data.message ? 1000 : 0)
                  setTimeout(function() {
                    location.href = data.redirect;
                  }, data.message ? 1000 : 0);
                } else if (data.content) {
                  // без перезагрузки странцы
                  $(data.content).insertAfter($success);
                  $form.find('.object-id').val(data.id);
                  if ($form.find('.submit').data('update')) {
                    $form.find('.submit').text($form.find('.submit').data('update'))
                  }
                }
                $form.trigger('success',data);
              } else {
                process = false;
                $submit.removeAttr('disabled');
                if ($error.length) {
                  $error.html(data.message || 'Неизвестная ошибка');
                  $error.show();
                  if ($form.closest('.modal').length == 0) {
                    $('html').animate({scrollTop:$error.offset().top - 100});
                  }
                }
                $form.trigger('error',data);
              }
            },
            error:function(err) {
              console.log(err);
              process = false;
              endLoading($form);
              $submit.removeAttr('disabled');
              if ($error.length) {
                $form.trigger('error',err);
                $error.html('Неизвестная ошибка');
                $error.show();
                if ($form.closest('.modal').length == 0) {
                  $('html').animate({scrollTop:$error.offset().top - 100});
                }
              }
            }
          });
          var submit = function($button) {
            var extra_data = {};
          
            if ($button.attr('name')) {
              extra_data[$button.attr('name')] = $button.data('value') || $button.val();
            }
            $form.trigger('beforeSubmit');
            $form.ajaxSubmit({
              data: extra_data,
              
            });
          }

          // $form.find('.submit').on('click', function(e) {
          //   var $button = $(this);
          //   try {
          //     tinyMCE.triggerSave();
          //   } catch(err) {
              
          //   }
          //   if ($button.data('confirm') && !confirm($button.data('confirm'))) {
          //     return false;
          //   }
          //   $form.submit();
            
          //   e.preventDefault();
          //   return false;
          // });
          // $form.on('submit',function(e){
          //   console.log(this.checkValidity())
          //   submit($form.find('.submit'));
          //   e.preventDefault();
          //   return false;
          // });
        });
        
        var $payment_block = $('.subscribe-payment');
        if ($payment_block.length) {
          var $select = $payment_block.find('select.subscribe-length');
          var $terms = $payment_block.find('#apply-terms');
          var $payment_button = $payment_block.find('#payment-button');
          var $payment_cost = $payment_block.find('#payment-cost');
          var $payment_label = $payment_block.find('#payment-label');
          $select.on('change',function() {
            var $option = $select.find(':selected');
            var label = $payment_label.data('user-id') + '_subscribe_' + $select.val();
            var cost = $option.data('cost');
            console.log(label,cost);
            $payment_label.val(label);
            $payment_cost.val($option.data('cost'));
          });
          $select.trigger('change');
          $terms.on('change',function() {
            var $self = $(this);
            if (!$self.prop('checked')) {
              $payment_button.attr('disabled',true);
            } else {
              $payment_button.removeAttr('disabled');
            }
          });
          $terms.trigger('change');
        }

        $('#wpcf-single_email').on('change',function() {
          if ($(this).prop('checked')) {
            $('#mc4wp-subscribe').attr('disabled',true);
          } else {
            $('#mc4wp-subscribe').removeAttr('disabled');
          }
        })

    });

})(window, document, window.jQuery);