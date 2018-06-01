(function() {
    tinymce.create("tinymce.plugins.incut_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {

            ed.addButton("incut", {
                title : "Врезка",
                cmd : "incut_command",
                image : "https://cdn2.iconfinder.com/data/icons/freecns-cumulus/16/519898-184_FormRectangleBorder-512.png"
            });

            //button functionality.
            ed.addCommand("incut_command", function() {
                var selected_text = ed.selection.getContent();
                var return_text = "<div class='well'>" + selected_text + "</div>";
                ed.execCommand("mceInsertContent", 0, return_text);
            });

            ed.on('init', function () {
                // ed.formatter.register('incut_div_format', {
                //     block: 'div',
                //     format: 'div',
                //     classes: 'well',
                //     title: 'Врезка',
                //     wrapper: true
                // });
            })
            

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "Extra Buttons",
                author : "Boris Penkovskiy",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("incut_button_plugin", tinymce.plugins.incut_button_plugin);
})();