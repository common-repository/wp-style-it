/**
 * Wrapper function to safely use $
 */
var custom_uploader;
function wpsiAdminWrapper($) {
    var wpsiAdmin = {
        /**
         * Main entry point
         */
        init: function () {
            $('#wpsi_block-code').parent().parent().find('th').hide();
            wpsiAdmin.registerEditArea();
        },

        registerEditArea: function () {
            jQuery('.settings_page_wpsi_settings textarea').each(function () {
                editAreaLoader.init({
                    id: jQuery(this).attr('id'), syntax: "css", start_highlight: true, word_wrap: true, allow_resize: true
                });
            });
        }
    }; // end wpsiAdmin

    $(document).ready(wpsiAdmin.init);

} // end wpsiAdminWrapper()

wpsiAdminWrapper(jQuery);
