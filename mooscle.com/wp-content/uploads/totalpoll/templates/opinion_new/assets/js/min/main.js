jQuery(function($) {
    $(document).on('totalpoll.after.ajax', function(x, elements) {
        var total = 100;
        var first_bigest = false;
        $('.totalpoll-choice .totalpoll-choice-percentage', elements.container).each(function(){
            var $elm = $(this);
            var $elmNumber = $elm.find('.totalpoll-choice-percentage-number');
            var $elmDecimal = $elm.find('.totalpoll-choice-percentage-decimal');
            var percentage = $elm.data('tp-percentage');
            var percentageDecimal = percentage - Math.floor(percentage)
            console.log(percentageDecimal);
            if (percentageDecimal == 0.5) {
                if (percentage > 50) {
                    percentage+=0.1;
                } else {
                    percentage-=0.1;
                }
            }
            var setNumber = function(percentage) {
                percentage = percentage.toFixed();
                $elmNumber.html(Math.floor(percentage) || "0");
                $elmDecimal.html("." + ((percentage.split(".")[1]) || "0"));
            };

            jQuery({countNum: 0}).animate({countNum: percentage}, {
                duration: 900,
                step: function() {
                    setNumber(this.countNum);
                },
                complete: function() {
                    setNumber(percentage);
                }
            });
        });
    });
});