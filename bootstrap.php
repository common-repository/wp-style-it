<?php
/*
Plugin Name: WP Style It
Plugin URI:  https://wordpress.org/plugins/wp-style-it/
Description: Add Custom CSS to your WordPress site without modifying your theme or plugins.
Version:     0.1
Author:      Henri Benoit
Author URI:  http://benohead.com
*/

/*
 * This plugin was built on top of WordPress-Plugin-Skeleton by Ian Dunn.
 * See https://github.com/iandunn/WordPress-Plugin-Skeleton for details.
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}

define('WPSI_NAME', 'WP Style It');
define('WPSI_REQUIRED_PHP_VERSION', '5.3'); // because of get_called_class()
define('WPSI_REQUIRED_WP_VERSION', '3.1'); // because of esc_textarea()

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function wpsi_requirements_met()
{
    global $wp_version;

    if (version_compare(PHP_VERSION, WPSI_REQUIRED_PHP_VERSION, '<')) {
        return false;
    }

    if (version_compare($wp_version, WPSI_REQUIRED_WP_VERSION, '<')) {
        return false;
    }

    return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function wpsi_requirements_error()
{
    global $wp_version;

    require_once(dirname(__FILE__) . '/views/requirements-error.php');
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if (wpsi_requirements_met()) {
    require_once(__DIR__ . '/classes/wpsi-module.php');
    require_once(__DIR__ . '/classes/wp-style-it.php');
    require_once(__DIR__ . '/includes/admin-notice-helper/admin-notice-helper.php');
    require_once(__DIR__ . '/classes/wpsi-settings.php');

    if (class_exists('WordPress_Style_It')) {
        $GLOBALS['wpsi'] = WordPress_Style_It::get_instance();
        register_activation_hook(__FILE__, array($GLOBALS['wpsi'], 'activate'));
        register_deactivation_hook(__FILE__, array($GLOBALS['wpsi'], 'deactivate'));
    }
} else {
    add_action('admin_notices', 'wpsi_requirements_error');
}
