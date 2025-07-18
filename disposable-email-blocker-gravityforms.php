<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Disposable_Email_Blocker_Gravityforms
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Disposable Email Blocker - GravityForms
 * Plugin URI:        https://wordpress.org/plugins/disposable-email-blocker-gravityforms/
 * Description:       Prevent From Submitting Any Disposable/Temporary Emails On GravityForms Forms.
 * Version:           2.0.2
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disposable-email-blocker-gravityforms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_VERSION', '2.0.2' );

/**
 * Define Plugin Folders Path
 */
define( 'DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Plugin database table name.
define( 'DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_TABLE_NAME', 'gform_disposable_domains' );

// Plugin db version.
define( 'DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_DB_VERSION', '20251705' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-disposable-email-blocker-gravityforms-activator.php
 *
 * @since    2.0.0
 */
function on_activate_disposable_email_blocker_gravityforms() {
	require_once DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-gravityforms-activator.php';

	Disposable_Email_Blocker_Gravityforms_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_disposable_email_blocker_gravityforms' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-disposable-email-blocker-gravityforms-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_disposable_email_blocker_gravityforms() {
	require_once DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-gravityforms-deactivator.php';

	Disposable_Email_Blocker_Gravityforms_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_disposable_email_blocker_gravityforms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since    2.0.0
 */
require DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-gravityforms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_disposable_email_blocker_gravityforms() {
	$plugin = new Disposable_Email_Blocker_Gravityforms();

	$plugin->run();
}

run_disposable_email_blocker_gravityforms();
