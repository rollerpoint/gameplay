(function (window, document, $, undefined) {

  $(function () {
    var $calendarModal = $('#digitalCalendarModal');
    var api_url = "/wp-json/wp/v2"
    var decodeSpecialChars = function(str) {
      return $("<textarea/>").html(str).text();
    };
    var current_language = 'ru';
    try {
      current_language = $('html').attr('lang').split('-')[0];
    } catch(err) {
      console.warn(err);
    }
    var showReleases = function(date,events) {
      $calendarModal.find('.modal-body').html('');
      var template = $calendarModal.find('.item-template').html();
      for (var i in events) {
        var $item = $(String(template));

        if (events[i].name) {
          $item.find('.event-name .content').text(events[i].name).parent().show();
        }

        if (events[i].rightholders.length) {
          $item.find('.event-rightholders .content').text(events[i].rightholders.map(function (c) {
            return c.name;
          }).join(', ')).parent().show();
        }

        if (events[i].genre.length) {
          $item.find('.event-genre .content').text(events[i].genre.map(function (c) {
            return c.name;
          }).join(', ')).parent().show();
        }

        if (events[i].licence_type.length) {
          $item.find('.event-licence_type .content').text(events[i].licence_type.map(function (c) {
            return c.name;
          }).join(', ')).parent().show();
        }
        if (events[i].customers.length) {
          $item.find('.event-customers .content').text(events[i].customers.map(function (c) {
            return c.name;
          }).join(', ')).parent().show();
        }

        if (events[i].description) {
          $item.find('.event-description .content').text(events[i].description).parent().show();
        }
        if (events[i].pirates_index) {
          $item.find('.event-pirates_index .content').text(events[i].pirates_index).parent().show();
        }
        if (events[i].pirates_formats) {
          $item.find('.event-pirates_formats .content').text(events[i].pirates_formats).parent().show();
        }
        $item.appendTo($calendarModal.find('.modal-body'));
      }
      $calendarModal.find('.modal-body [data-toggle="popover"]').popover({
        container: '#digitalCalendarModal',
        html: true,
        trigger: 'focus'
      }).on('click', function () {
        return false;
      })
      $calendarModal.find('.modal-title').text($calendarModal.find('.modal-title').data('title') + ': ' + moment(date).format('DD.MM.YYYY'));
      $calendarModal.modal();
    }
    var loadTax = function (taxonomy) {
      return new Promise(function (resolve, reject) {
        $.ajax(api_url + '/' + taxonomy, {
            data: {
              per_page: 100
            }
          }).done(function (result) {
            filters_data[taxonomy] = result.map(function (tax) {
              return {
                id: tax.id,
                name: current_language == 'ru' ? tax.name : (tax.name_en || tax.name),
                color: tax.color || null
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
    var filters_data = {
      customers: [],
      rightholders: [],
      genre: [],
      licence_type: []
    }
    if ($('.small-calendar').length || $('#calendar').length) {
      var filters_promise = Promise.all(Object.keys(filters_data).map(function(key) {
        return loadTax(key)
      }));
    }

    
    var loadMonthData = function(date) {
     return new Promise(function(resolve,reject) {

     }) 
    }
    var composeRelease = function (release) {
      var d = {
        id: release.id,
        name: decodeSpecialChars(release.title.rendered),
        rightholders: filters_data.rightholders.filter(function (r) {
          return release.rightholders.indexOf(r.id) > -1
        }),
        customers: filters_data.customers.filter(function (r) {
          return release.customers.indexOf(r.id) > -1
        }),
        startDate: release.release_date ? moment(release.release_date * 1000).startOf('day') : null,
        endDate: release.release_date ? moment(release.release_date * 1000).endOf('day') : null,
        startUnix:release.release_date * 1000,
        description: release.description || '',
        genre: filters_data.genre.filter(function (r) {
          return release.genre.indexOf(r.id) > -1
        }),
        licence_type: filters_data.licence_type.filter(function (r) {
          return release.licence_type.indexOf(r.id) > -1
        }),
        pirates_index: release.pirates_index,
        pirates_formats: release.pirates_formats
      }
      d.color = d.rightholders[0] && d.rightholders[0].color;
      return d;
    }
    if ($('.small-calendar').length) {
      var $scalendar = $('.small-calendar');
      var loadReleases = function(date,download) {
        var start = (date.startOf('month') / 1000).toFixed();
        var end = (date.endOf('month') / 1000).toFixed();
        var query = {
          per_page: 1000,
          meta_query: {
            '0': {
              value: [start, end],
              key: 'wpcf-release_date',
              compare: 'BETWEEN'
            }
          }
        }
        if (download) {
          query.action = 'download_releases';
          return window.open('/wp-admin/admin-post.php?'+$.param(query),'_blank');
        }
        return new Promise(function(resolve,reject) {
          $.ajax(api_url + '/releases', {
            data: query
          }).done(function (result) {
            releases = result.map(function (data) {
              return composeRelease(data)
            });
            resolve(releases)
          })
          .error(function (err) {
            reject(err);
          });
        })
      }
      var calendarChangeMonth = function(date) {
        $scalendar.closest('.loader').addClass('whirl');
        return filters_promise.then(function() {
          return loadReleases(date)
        }).then(function(result) {
          var days = {};
          result.forEach(function(release) {
            var timestamp = release.startUnix;
            if (!days[timestamp]) {
              days[timestamp] = []
            }
            days[timestamp].push(release);
          });
          $.each(days, function(timestamp,day_releases) {
            var $day = $scalendar.find('[data-date="' + timestamp + '"]');
            var day_colors = [];
            day_releases.forEach(function(release) {
              var color = release.color;
              if (day_colors.indexOf(color) == -1) {
                day_colors.push(color);
              }
            })
            var shadow_heigh = 2;
            var shadows = day_colors.sort().map(function(color,i) {
              var white = "#fff 0px -" + ((i + 1) * shadow_heigh-1) + "px 0px 0px inset";
              var shadow = color + " 0px -" + ((i + 1) * shadow_heigh) + "px 0px 0px inset";
              // return  [white,shadow].join(',')
              return shadow;
            });
            var css = " " + shadows.join(', ');
            var $div = $("<div>").text($day.text());
            $div.css('box-shadow',css).addClass('releases');
            $day.html($div);
            $day.on('click',function(e) {
              showReleases(parseInt(timestamp),day_releases);
              return false;
            });
          })
          $scalendar.find('.datepicker-days td:not(.releases)').on('click',function() {
            return false;
          });
          var download = '<button type="button" data-toggle="tooltip" title="Скачать" class="btn btn-xs download-calendar btn-secondary">Скачать</button>';
          var $downloadWrapper = $scalendar.closest('.widget').find('.widget-title');
          $downloadWrapper.find('.download-calendar').remove();
          $(download).appendTo($downloadWrapper).on('click',function() {
            loadReleases(date,true);
          });
          $scalendar.closest('.loader').removeClass('whirl');
        }).catch(function(err) {
          console.warn(err);
          $scalendar.closest('.loader').removeClass('whirl');
        })
      }
      $scalendar.datepicker({
        language: current_language,
        todayHighlight:true,
        maxViewMode:1,
        templates:{
          leftArrow:"<i class='fa fa-angle-left'></i>",     
          rightArrow:"<i class='fa fa-angle-right'></i>"
        }
      }).on('changeMonth',function(e,m) {
        calendarChangeMonth(moment(e.date));
      });
      calendarChangeMonth(moment());
    }
    

   
    if ($('#calendar').length) {
      var releases = [];
      var loaded = false;
      var $filters = $('.calendar-holder .filters .filters-form');
      var titles = {
        customers: "Площадка",
        rightholders: "Дистрибьютор",
        genre: "Жанр",
        licence_type: "Монетизация "
      }
      var currentYear = null;
      
      var chain = filters_promise.then(function () {
        $.each(filters_data, function (key, value) {
          var $select = $filters.find('[name="' + key + '"]');
          $.each(value, function (i, tax) {
            $('<option style="border-left:3px solid ' + tax.color + ';" value="' + tax.id + '" data-color="' + tax.color + '">' + tax.name + '</option>').appendTo($select);
          })
          $select.chosen({
            placeholder_text_multiple: $select.data('placeholder'),
            width: '100%'
          })
          var chosen = $select.data('chosen');
          $select.on('change', function () {
            $(chosen.container).find('.chosen-choices').find('[data-option-array-index]').each(function () {
              var $li = $(this).closest('li');
              var $target_option = $(chosen.container).find('.chosen-drop').find('[data-option-array-index="' + $(this).data('option-array-index') + '"]');
              $li.attr('style', $target_option.attr('style'));
            });
            loadData();
          });
          $select.on('chosen:showing_dropdown', function () {
            var items =
              console.log('chosen:showing_dropdown')
          })
        });
      });
      var loadData = function (download) {
        chain = chain.then(function () {
          currentYear = currentYear || moment().format('YYYY');
          var start = (moment().year(currentYear).startOf('year') / 1000).toFixed();
          var end = (moment().year(currentYear).endOf('year') / 1000).toFixed();
          var query = {
            per_page: 1000,
            meta_query: {
              '0': {
                value: [start, end],
                key: 'wpcf-release_date',
                compare: 'BETWEEN'
              }
            }
          }
          $filters.find('select').each(function (i, select) {
            var $self = $(this);
            var tax_name = $self.attr('name');
            if ($self.val() && $self.val().length) {
              query[tax_name] = $self.val();
            }
          })
          if ($filters.find('[type="search"]').val()) {
            query.search = $filters.find('[type="search"]').val();
          }
          if (download) {
            query.action = 'download_releases';
            return window.open('/wp-admin/admin-post.php?'+$.param(query),'_blank');
          }
          $('#calendar').addClass('whirl');
          $.ajax(api_url + '/releases', {
              data: query
            }).done(function (result) {
              releases = result.map(function (data) {
                return composeRelease(data)
              });
              calendar.setDataSource(releases.filter(function (r) {
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

      $('.download-calendar').on('click',function() {
        loadData(true)
      });


      var $shadowCalendar;
      $('#calendar').calendar({
        language: current_language,
        style:'custom',
        customDataSourceRenderer:function(elt, currentDate, events) {
          var weight = 0;
          var day_colors = [];
          events.forEach(function(release) {
            var color = release.color;
            if (day_colors.indexOf(color) == -1) {
              day_colors.push(color);
            }
          });
          var shadow_heigh = 2;
					var shadows = day_colors.sort().map(function(color,i) {
            var shadow = color + " 0px -" + ((i + 1) * shadow_heigh) + "px 0px 0px inset";
            return shadow;
          });
          elt.css('box-shadow', shadows.join(', '));
        },
        dataSource: [],
        yearChanged: function (e) {
          currentYear = e.currentYear;
          loadData();
        },
        clickDay: function (e) {
          if (e.events.length > 0) {
            showReleases(e.date,e.events);
          }
        },
        mouseOnDay: function (e) {
          if (e.events.length > 0) {
            var content = '';

            for (var i in e.events) {
              content += '<div class="event-tooltip-content">' +
                '<div class="event-name">' + e.events[i].name + '</div>' +
                '</div>';
            }

            $(e.element).popover({
              trigger: 'manual',
              container: 'body',
              html: true,
              content: content
            });

            $(e.element).popover('show');
          }
        },
        mouseOutDay: function (e) {
          if (e.events.length > 0) {
            $(e.element).popover('hide');
          }
        },
        customDayRenderer: function (element, date) {
          var d = moment(date);
          var event = releases.filter(function (r) {
            return r.startDate <= d && r.endDate >= d;
          })[0]
          var $el = $(element);
          if (event) {
            $el.css('cursor', 'pointer');
          } else {
            $el.css('cursor', 'default');
          }
        },
        renderEnd: function (e) {
          if ($shadowCalendar) {
            $shadowCalendar.remove();
          }
          $calendarHeader = $('#calendar').find('.calendar-header');
          $shadowCalendar = $('<div class="calendar"></div>').prependTo($filters.closest('.filters'));
          $calendarHeader.appendTo($shadowCalendar);
        }
      });
      var calendar = $('#calendar').data('calendar');
      $filters.on('submit', function () {
        loadData();
        return false;
      });
    }
  })

})(window, document, window.jQuery);