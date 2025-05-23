<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://techinspire.my.id
 * @since             1.0.1
 * @package           Pmb_Stba
 *
 * @wordpress-plugin
 * Plugin Name:       PMB STBA Pontianak
 * Plugin URI:        https://techinspire.my.id
 * Description:       test Plugin PMB STBA Pontianak
 * Version:           1.0.0
 * Author:            Sukardi
 * Author URI:        https://techinspire.my.id/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pmb-stba
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// ============== https://carbonfields.net/ ================
if(!defined('Carbon_Fields_Plugin\PLUGIN_FILE')){
    define( 'Carbon_Fields_Plugin\PLUGIN_FILE', __FILE__ );

    define( 'Carbon_Fields_Plugin\RELATIVE_PLUGIN_FILE', basename( dirname( \Carbon_Fields_Plugin\PLUGIN_FILE ) ) . '/' . basename( \Carbon_Fields_Plugin\PLUGIN_FILE ) );
}

add_action( 'after_setup_theme', 'carbon_fields_boot_plugin' );
if(!function_exists('carbon_fields_boot_plugin')){
    function carbon_fields_boot_plugin() {
        if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
            require( __DIR__ . '/vendor/autoload.php' );
        }
        \Carbon_Fields\Carbon_Fields::boot();

        if ( is_admin() ) {
            \Carbon_Fields_Plugin\Libraries\Plugin_Update_Warning\Plugin_Update_Warning::boot();
        }
    }
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PMB_STBA_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pmb-stba-activator.php
 */
function activate_pmb_stba() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pmb-stba-activator.php';
	Pmb_Stba_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pmb-stba-deactivator.php
 */
function deactivate_pmb_stba() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pmb-stba-deactivator.php';
	Pmb_Stba_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pmb_stba' );
register_deactivation_hook( __FILE__, 'deactivate_pmb_stba' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pmb-stba.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pmb_stba() {

	$plugin = new Pmb_Stba();
	$plugin->run();
    
    // Debugging: Check if admin_post hooks are registered
    add_action('init', function() {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (!has_action('admin_post_pmb_delete_user')) {
                error_log('PMB STBA: admin_post_pmb_delete_user hook not registered');
            }
        }
    });
}
run_pmb_stba();
