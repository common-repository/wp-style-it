/**
 * Wrapper function to safely use $
 */
function wpsiWrapper($) {
    var wpsi = {

        /**
         * Main entry point
         */
        init: function () {
            wpsi.prefix = 'wpsi_';
            wpsi.templateURL = $('#template-url').val();
            wpsi.ajaxPostURL = $('#ajax-post-url').val();

            wpsi.registerEventHandlers();
            wpsi.registerEditArea();
        },

        /**
         * Registers event handlers
         */
        registerEventHandlers: function () {
        },

        registerEditArea: function () {
            jQuery('.settings_page_wpsi_settings textarea').each(function () {
                editAreaLoader.init({
                    id: jQuery(this).attr('id')		// textarea id
                    , syntax: "html"			// syntax to be uses for highgliting
                    , start_highlight: true		// to display with highlight mode on start-up
                });
            });
        }
    }; // end wpsi

    $(document).ready(wpsi.init);

} // end wpsiWrapper()

wpsiWrapper(jQuery);
