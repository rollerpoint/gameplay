jQuery(function($) {
    $(document).on('totalpoll.after.ajax', function(x, elements) {
        alert(1);
        var total = 100;
        var first_bigest = false;
        $('.totalpoll-choice .totalpoll-choice-percentage', elements.container).each(function(){
            var $elm = $(this);
            var $elmNumber = $elm.find('.totalpoll-choice-percentage-number');
            var $elmDecimal = $elm.find('.totalpoll-choice-percentage-decimal');
            var percentage = $elm.data('tp-percentage');
            console.log(percentage)
            var other = total - percentage;
            if (percentage > other) {
                percentage = percentage.toFixed() + 1;
            } else {
                percentage = percentage.toFixed();
            }
            var setNumber = function(percentage) {
                percentage = percentage.toFixed(2);
                $elmNumber.html(Math.floor(percentage) || "00");
                $elmDecimal.html("." + ((percentage.split(".")[1]) || "00"));
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