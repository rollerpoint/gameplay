moment.locale('ru');

(function (window, document, $, undefined) {
  $(function () {
    if ($('#calendar').length) {
      var releases = [];
      var $calendarModal = $('#calendarModal');
      var chain = Promise.resolve();
      var api_url = "https://ruvod.com/wp-json/wp/v2"
      var loaded = false;
      var $filters = $('.calendar-holder .filters .filters-form');
      var filters_data = {
        customers: [],
        rightholders: [],
        genre:[],
        licence_type:[]
      }
      var titles = {
        customers: "Площадка",
        rightholders: "Дистрибьютор",
        genre:"Жанр",
        licence_type:"Монетизация "
      }
      var currentYear = null;
      var loadTax = function (taxonomy) {
        return new Promise(function (resolve, reject) {
          $.ajax(api_url + '/' + taxonomy)
            .done(function (result) {
              filters_data[taxonomy] = result.map(function (tax) {
                return {
                  id: tax.id,
                  name: tax.name
                }
              });
              resolve();
            })
            .error(function (err) {
              console.warn(err);
              reject(err);
            })
        })
      }
      var composeRelease = function(release) {
        return {
          id: release.id,
          name: release.title.rendered,
          rightholders:filters_data.rightholders.filter(function(r) { return release.rightholders.indexOf(r.id) > -1 }),
          customers:filters_data.customers.filter(function(r) { return release.customers.indexOf(r.id) > -1 }),
          startDate: release.release_date ? moment(release.release_date * 1000).startOf('day') : null,
          endDate: release.release_date ? moment(release.release_date * 1000).endOf('day') : null,
          description: release.description || '',
          genre: filters_data.genre.filter(function(r) { return release.genre.indexOf(r.id) > -1 }),
          licence_type: filters_data.licence_type.filter(function(r) { return release.licence_type.indexOf(r.id) > -1 }),
        }
      }
      
      $.each(filters_data, function (key, value) {
        chain = chain.then(function () {
          return loadTax(key)
        })
      });
      // $filters.addClass('row');
      chain = chain.then(function() {
        var $item = $('<div class="filter-item"></div>');
        var $title = $('<label></label>').text('Фильм').appendTo($item);
        var $input = $('<div><input type="search" name="title" placeholder="Название"></div>').appendTo($item);
        $item.appendTo($filters);
        $.each(filters_data, function (key, value) {
          var $item = $('<div class="filter-item"></div>');
          var $title = $('<label></label>').text(titles[key]).appendTo($item);
          var $select = $('<select name="' + key + '" multiple></select>').appendTo($item);
          $.each(value, function (i, tax) {
            $('<option value="' + tax.id + '">' + tax.name + '</option>').appendTo($select);
          })
          $item.appendTo($filters);
          $select.chosen({
            placeholder_text_multiple: "Выберите значения",
            width:'100%'
          })
          $select.on('change',function() {
            loadData()
            loadTable();
          });
        });
        var d = '<a href="http://ruvod.com/wp-content/uploads/2017/10/RELEASE-PLAN-FOR-RUVOD.xlsx" class="btn btn-primary">СКАЧАТЬ ШАБЛОН</a>'
        var s = '<button class="btn btn-primary pull-right">ПОИСК</button>'
        $('<div class="submit-container">' + d + s + '</div>').appendTo($filters);
      });
      var loadData = function() {
        $('#calendar').addClass('whirl');
        chain = chain.then(function () {
          currentYear = currentYear || moment().format('YYYY');
          var start = (moment().year(currentYear).startOf('year') / 1000).toFixed();
          var end = (moment().year(currentYear).endOf('year') / 1000).toFixed();
          var query = {
            per_page: 100,
            meta_query:{
              '0':{
                value:[start,end],
                key:'wpcf-release_date',
                compare: 'BETWEEN'
              },
              // '1':{
              //   key: 'wpcf-release_date',
              //   compare : 'NOT EXISTS'
              // },
              // relation: 'OR'
            }
          }
          $filters.find('select').each(function(i,select) {
            var $self = $(this);
            var tax_name = $self.attr('name');
            if ($self.val() && $self.val().length) {
              query[tax_name] = $self.val();
            }
          })
          query.search = $filters.find('[type="search"]').val();
          $.ajax(api_url + '/releases',{
            data:query
          }).done(function (result) {
              releases = result.map(function(data) { return composeRelease(data) });
              calendar.setDataSource(releases.filter(function(r) {
                return !!r.startDate;
              }));
              $('#calendar').removeClass('whirl');
            })
            .error(function (err) {
              $('#calendar').removeClass('whirl');
              console.warn(err);
            });
        });
      };
      var loadTable = function(page) {
        return;
        var $holder = $('#open-releases-holder');
        page = page || 1;
        var query = {
          page:page,
          action:'opened_date_release'
        };
        $holder.addClass('whirl');
        $filters.find('select').each(function(i,select) {
          var $self = $(this);
          var tax_name = $self.attr('name');
          if ($self.val() && $self.val().length) {
            query[tax_name] = $self.val();
          }
        });
        query.search = $filters.find('[type="search"]').val();
        $.ajax('/wp-admin/admin-ajax.php',{
          data:query
        })
        .done(function (result) {
          $holder.html(result);
          $holder.find('a').on('click',function() {
            var url = $(this).attr('href');
            var page = url.indexOf('page/') > -1 ? url.split('page/')[1].split('/')[0] : 1;
            loadTable(page);
            return false;
          });
          $holder.removeClass('whirl');
        })
        .error(function (err) {
          $holder.removeClass('whirl');
        });
      };
      loadTable();
      var $shadowCalendar;
      $('#calendar').calendar({
        language: 'ru',
        dataSource:[],
        yearChanged:function(e) {
          currentYear = e.currentYear;
          loadData();
        },
        clickDay: function(e) {
          if(e.events.length > 0) {
              var content = '';
              var date = e.date;
              for(var i in e.events) {
                  content += '<div class="event-modal-content">'
                                + '<div class="event-name">' + e.events[i].name + '</div>'
                                + '<div class="event-rightholders"><b>Дистрибьютор:</b> ' + e.events[i].rightholders.map(function(c) { return c.name; } ).join(', ')  + '</div>'
                                + '<div class="event-genre"><b>Жанр: </b>' + e.events[i].genre.map(function(c) { return c.name; } ).join(', ') + '</div>'
                                + '<div class="event-licence_type"><b>Модель монетизации:</b> ' + e.events[i].licence_type.map(function(c) { return c.name; } ).join(', ') + '</div>'
                                + ( e.events[i].customers.length ? ('<div class="event-customers"><b>Площадки:</b> ' + e.events[i].customers.map(function(c) { return c.name; } ).join(', ') + '</div>') : '')
                                + ( e.events[i].description && ('<div class="event-description"><b>Описание:</b> ' + e.events[i].description + '</div>'))
                            + '</div>';
              }
              $calendarModal.find('.modal-body').html(content);
              $calendarModal.find('.modal-title').text('Цифровые релизы: ' + moment(date).format('DD.MM.YYYY'));
              $calendarModal.modal();
          }
        },
        mouseOnDay: function(e) {
            if(e.events.length > 0) {
                var content = '';
                
                for(var i in e.events) {
                    content += '<div class="event-tooltip-content">'
                                  + '<div class="event-name">' + e.events[i].name + '</div>'
                              + '</div>';
                }
            
                $(e.element).popover({ 
                    trigger: 'manual',
                    container: 'body',
                    html:true,
                    content: content
                });
                
                $(e.element).popover('show');
            }
        },
        mouseOutDay: function(e) {
            if(e.events.length > 0) {
                $(e.element).popover('hide');
            }
        },
        customDayRenderer: function(element, date) {
            var d = moment(date);
            var event = releases.filter(function(r) {
              return r.startDate <= d && r.endDate >= d;
            })[0]
            var $el = $(element);
            if (event) {
              $el.css('cursor','pointer');
            } else {
              $el.css('cursor','default');
            }
        },
        renderEnd:function(e) {
          if ($shadowCalendar) {
            $shadowCalendar.remove();
          }
          $calendarHeader = $('#calendar').find('.calendar-header');
          $shadowCalendar = $('<div class="calendar"></div>').prependTo($filters.closest('.filters'));
          $calendarHeader.appendTo($shadowCalendar);
        }
      });
      var calendar = $('#calendar').data('calendar');
      $filters.on('submit',function() {
        loadData();
        return false;
      });
    }

  });

})(window, document, window.jQuery);