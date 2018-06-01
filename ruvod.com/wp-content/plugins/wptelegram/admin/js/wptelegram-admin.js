function pasteHtmlAtCaret(html, selectPastedContent) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            var firstNode = frag.firstChild;
            range.insertNode(frag);
            
            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                if (selectPastedContent) {
                    range.setStartBefore(firstNode);
                } else {
                    range.collapse(true);
                }
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if ( (sel = document.selection) && sel.type != "Control") {
        // IE < 9
        var originalRange = sel.createRange();
        originalRange.collapse(true);
        sel.createRange().pasteHTML(html);
        if (selectPastedContent) {
            range = sel.createRange();
            range.setEndPoint("StartToStart", originalRange);
            range.select();
        }
    }
}
(function( $ ) {
	'use strict';
	$(document).ready(function() {
		if ( 'function' == typeof $().emojioneArea ) {
			if (window.matchMedia('(max-width: 800px)').matches) {
		        var pos = 'top';
		    } else {
		        var pos = 'left';
		    }
			var e = $('#wptelegram_message_template').emojioneArea({
				container: "#wptelegram_message_template-container",
				hideSource: true,
				pickerPosition: pos,
				tonesStyle: 'radio',
			    });
		}
		$('.wptelegram-tag').click(function () {
		    if ( 'function' == typeof $().emojioneArea )
		    	$('.emojionearea-editor')[0].focus();
			    var val = this.innerText;
			    pasteHtmlAtCaret(val,true);
		});
		var send_to = $('#wptelegram_meta_box #send_to');
		var template = $('#wptelegram_meta_box #message_template');
		
		var chat_ids = $('.wptelegram_send_to');
		chat_ids.hide();
	    $('input[type=radio][name=wptelegram_send_message]').change(function() {
	        if (this.value == 'yes') {
	            send_to.show(300);
	            template.show(300);
	        }
	        else{
	        	send_to.hide(300);
	            template.hide(300);
	        }
	    });
	    $('#wptelegram_send_to_all').change(function() {
	        if(this.checked) {
	            chat_ids.hide(300);
	        }
	        else{
	        	chat_ids.show(300);
	        }
	    });
	});
})(jQuery);