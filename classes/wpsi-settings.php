<?php

if (!class_exists('WPSI_Settings')) {

    /**
     * Handles plugin settings and user profile meta fields
     */
    class WPSI_Settings extends WPSI_Module
    {
        protected $settings;
        protected static $default_settings;
        protected static $readable_properties = array('settings');
        protected static $writeable_properties = array('settings');

        const REQUIRED_CAPABILITY = 'administrator';


        /*
         * General methods
         */

        /**
         * Constructor
         *
         * @mvc Controller
         */
        protected function __construct()
        {
            $this->register_hook_callbacks();
        }

        /**
         * Public setter for protected variables
         *
         * Updates settings outside of the Settings API or other subsystems
         *
         * @mvc Controller
         *
         * @param string $variable
         * @param array $value This will be merged with WPSI_Settings->settings, so it should mimic the structure of the WPSI_Settings::$default_settings. It only needs the contain the values that will change, though. See WordPress_Style_It->upgrade() for an example.
         */
        public function __set($variable, $value)
        {
            // Note: WPSI_Module::__set() is automatically called before this

            if ($variable != 'settings') {
                return;
            }

            $this->settings = self::validate_settings($value);
            update_option('wpsi_settings', $this->settings);
        }

        /**
         * Register callbacks for actions and filters
         *
         * @mvc Controller
         */
        public function register_hook_callbacks()
        {
            add_action('admin_menu', __CLASS__ . '::register_settings_pages');
            add_action('init', array($this, 'init'));
            add_action('admin_init', array($this, 'register_settings'));

            add_filter(
                'plugin_action_links_' . plugin_basename(dirname(__DIR__)) . '/bootstrap.php',
                __CLASS__ . '::add_plugin_action_links'
            );
        }

        /**
         * Prepares site to use the plugin during activation
         *
         * @mvc Controller
         *
         * @param bool $network_wide
         */
        public function activate($network_wide)
        {
        }

        /**
         * Rolls back activation procedures when de-activating the plugin
         *
         * @mvc Controller
         */
        public function deactivate()
        {
        }

        /**
         * Initializes variables
         *
         * @mvc Controller
         */
        public function init()
        {
            self::$default_settings = self::get_default_settings();
            $this->settings = self::get_settings();
        }

        /**
         * Executes the logic of upgrading from specific older versions of the plugin to the current version
         *
         * @mvc Model
         *
         * @param string $db_version
         */
        public function upgrade($db_version = 0)
        {
            /*
            if( version_compare( $db_version, 'x.y.z', '<' ) )
            {
                // Do stuff
            }
            */
        }

        /**
         * Checks that the object is in a correct state
         *
         * @mvc Model
         *
         * @param string $property An individual property to check, or 'all' to check all of them
         * @return bool
         */
        protected function is_valid($property = 'all')
        {
            // Note: __set() calls validate_settings(), so settings are never invalid

            return true;
        }


        /*
         * Plugin Settings
         */

        /**
         * Establishes initial values for all settings
         *
         * @mvc Model
         *
         * @return array
         */
        protected static function get_default_settings()
        {
            $blocks = array(
                "code" => ""
            );

            $options = array(
                "suppress_on_posts" => false,
                "suppress_on_pages" => false,
                "suppress_on_attachment" => false,
                "suppress_on_category" => false,
                "suppress_on_tag" => false,
                "suppress_on_home" => false,
                "suppress_on_front" => false,
                "suppress_on_author" => false,
                "suppress_on_archive" => false,
                "suppress_on_error" => false,
                "suppress_on_wptouch" => false,
                "suppress_on_logged_in" => false,
                "suppress-post-id" => "",
                "suppress-category" => array(),
                "suppress-tag" => array(),
                "suppress-user" => array(),
                "suppress-format" => array(),
                "suppress-post-type" => array(),
                "suppress-language" => array(),
                "suppress-url" => "",
                "suppress-referrer" => "",
                "suppress-ipaddress" => ""
            );

            return array(
                'db-version' => '0',
                'blocks' => $blocks,
                'options' => $options
            );
        }

        /**
         * Retrieves all of the settings from the database
         *
         * @mvc Model
         *
         * @return array
         */
        protected static function get_settings()
        {
            $settings = shortcode_atts(
                self::$default_settings,
                get_option('wpsi_settings', array())
            );

            return $settings;
        }

        /**
         * Adds links to the plugin's action link section on the Plugins page
         *
         * @mvc Model
         *
         * @param array $links The links currently mapped to the plugin
         * @return array
         */
        public static function add_plugin_action_links($links)
        {
            array_unshift($links, '<a href="http://wordpress.org/extend/plugins/wp-style-it/faq/">Help</a>');
            array_unshift($links, '<a href="options-general.php?page=' . 'wpsi_settings">Settings</a>');

            return $links;
        }

        /**
         * Adds pages to the Admin Panel menu
         *
         * @mvc Controller
         */
        public static function register_settings_pages()
        {
            add_submenu_page(
                'options-general.php',
                WPSI_NAME . ' Settings',
                WPSI_NAME,
                self::REQUIRED_CAPABILITY,
                'wpsi_settings',
                __CLASS__ . '::markup_settings_page'
            );
        }

        /**
         * Creates the markup for the Settings page
         *
         * @mvc Controller
         */
        public static function markup_settings_page()
        {
            if (current_user_can(self::REQUIRED_CAPABILITY)) {
                echo self::render_template('wpsi-settings/page-settings.php');
            } else {
                wp_die('Access denied.');
            }
        }

        private function add_settings_field($id, $title, $section)
        {
            add_settings_field(
                $id,
                $title,
                array($this, 'markup_fields'),
                'wpsi_settings',
                $section,
                array('label_for' => $id)
            );
        }

        private function add_settings_field_blocks($id)
        {
            add_settings_field(
                $id,
                '',
                array($this, 'markup_code'),
                'wpsi_settings',
                'wpsi_section-blocks',
                array('label_for' => $id)
            );
        }

        public function markup_code($field)
        {
            echo self::render_template('wpsi-settings/page-settings-code.php', array('settings' => $this->settings, 'field' => $field), 'always');
        }

        private function add_settings_field_options($id, $title)
        {
            $this->add_settings_field($id, $title, 'wpsi_section-options');
        }

        private function add_settings_section($id, $title)
        {
            add_settings_section(
                $id,
                $title,
                __CLASS__ . '::markup_section_headers',
                'wpsi_settings'
            );
        }

        /**
         * Registers settings sections, fields and settings
         *
         * @mvc Controller
         */
        public function register_settings()
        {
            $blocks = $this->settings['blocks'];

            /*
             * Block Section
             */
            $this->add_settings_section('wpsi_section-blocks', 'Style');

            $this->add_settings_field_blocks('wpsi_block-code');

            /*
             * Options Section
             */
            $this->add_settings_section('wpsi_section-options', 'Options');

            $this->add_settings_field_options('wpsi_suppress-on-posts', 'Suppress style on posts');
            $this->add_settings_field_options('wpsi_suppress-on-pages', 'Suppress style on pages');
            $this->add_settings_field_options('wpsi_suppress-on-attachment', 'Suppress style on attachment page');
            $this->add_settings_field_options('wpsi_suppress-on-category', 'Suppress style on category page');
            $this->add_settings_field_options('wpsi_suppress-on-tag', 'Suppress style on tag page');
            $this->add_settings_field_options('wpsi_suppress-on-home', 'Suppress style on home page');
            $this->add_settings_field_options('wpsi_suppress-on-front', 'Suppress style on front page');
            $this->add_settings_field_options('wpsi_suppress-on-archive', 'Suppress style on archive page');
            $this->add_settings_field_options('wpsi_suppress-on-error', 'Suppress style on error page');
            $this->add_settings_field_options('wpsi_suppress-on-author', 'Suppress style on author page');
            $this->add_settings_field_options('wpsi_suppress-on-logged-in', 'Suppress style for logged in users');
            $this->add_settings_field_options('wpsi_suppress-on-wptouch', 'Suppress style on WPtouch mobile site');
            $this->add_settings_field_options('wpsi_suppress-post-id', 'Suppress style for specific post/page IDs');
            $this->add_settings_field_options('wpsi_suppress-category', 'Suppress style for specific categories');
            $this->add_settings_field_options('wpsi_suppress-tag', 'Suppress style for specific tags');
            $this->add_settings_field_options('wpsi_suppress-user', 'Suppress style for specific authors');
            if (current_theme_supports('post-formats')) {
                $this->add_settings_field_options('wpsi_suppress-format', 'Suppress style for specific post formats');
            }
            $this->add_settings_field_options('wpsi_suppress-post-type', 'Suppress style for specific post types');
            $this->add_settings_field_options('wpsi_suppress-language', 'Suppress style for specific languages');
            $this->add_settings_field_options('wpsi_suppress-url', 'Suppress style for specific URL paths');
            $this->add_settings_field_options('wpsi_suppress-referrer', 'Suppress style for specific referrers');
            $this->add_settings_field_options('wpsi_suppress-ipaddress', 'Suppress style for specific IP addresses');

            // The settings container
            register_setting('wpsi_settings', 'wpsi_settings', array($this, 'validate_settings'));
        }

        /**
         * Adds the section introduction text to the Settings page
         *
         * @mvc Controller
         *
         * @param array $section
         */
        public static function markup_section_headers($section)
        {
            echo self::render_template('wpsi-settings/page-settings-section-headers.php', array('section' => $section), 'always');
        }

        /**
         * Delivers the markup for settings fields
         *
         * @mvc Controller
         *
         * @param array $field
         */
        public function markup_fields($field)
        {
            global $q_config;
            echo self::render_template('wpsi-settings/page-settings-fields.php', array('settings' => $this->settings, 'field' => $field, 'q_config' => $q_config), 'always');
        }

        private function setting_default_if_not_set($new_settings, $section, $id, $value)
        {
            if (!isset($new_settings[$section][$id])) {
                $new_settings[$section][$id] = $value;
            }
        }

        private function setting_empty_string_if_not_set($new_settings, $section, $id)
        {
            $this->setting_default_if_not_set($new_settings, $section, $id, '');
        }

        private function setting_empty_array_if_not_set($new_settings, $section, $id)
        {
            $this->setting_default_if_not_set($new_settings, $section, $id, array());
        }

        private function setting_zero_if_not_set($new_settings, $section, $id)
        {
            $this->setting_default_if_not_set($new_settings, $section, $id, '0');
        }

        /**
         * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
         *
         * @mvc Model
         *
         * @param array $new_settings
         * @return array
         */
        public function validate_settings($new_settings)
        {
            $new_settings = shortcode_atts($this->settings, $new_settings);

            if (!is_string($new_settings['db-version'])) {
                $new_settings['db-version'] = WordPress_Style_It::VERSION;
            }

            /*
             * Blocks Settings
             */

            if (!isset($new_settings['blocks'])) {
                $new_settings['blocks'] = array();
                $this->setting_empty_string_if_not_set($new_settings, 'blocks', 'code');
            }

            /*
             * Options Settings
             */

            if (!isset($new_settings['options'])) {
                $new_settings['options'] = array();
            }

            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-posts');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-pages');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-attachment');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-category');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-tag');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-home');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-front');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-archive');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-error');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-wptouch');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-author');
            $this->setting_zero_if_not_set($new_settings, 'options', 'suppress-on-logged-in');
            $this->setting_empty_string_if_not_set($new_settings, 'options', 'suppress-post-id');
            $this->setting_empty_array_if_not_set($new_settings, 'options', 'suppress-category');
            $this->setting_empty_array_if_not_set($new_settings, 'options', 'suppress-tag');
            $this->setting_empty_array_if_not_set($new_settings, 'options', 'suppress-user');
            $this->setting_empty_array_if_not_set($new_settings, 'options', 'suppress-format');
            $this->setting_empty_array_if_not_set($new_settings, 'options', 'suppress-post-type');
            $this->setting_empty_array_if_not_set($new_settings, 'options', 'suppress-language');
            $this->setting_empty_string_if_not_set($new_settings, 'options', 'suppress-url');
            $this->setting_empty_string_if_not_set($new_settings, 'options', 'suppress-referrer');
            $this->setting_empty_string_if_not_set($new_settings, 'options', 'suppress-ipaddress');

            return $new_settings;
        }
    } // end WPSI_Settings
}
