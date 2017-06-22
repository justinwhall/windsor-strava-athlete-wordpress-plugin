<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://justinwhall.com
 * @since             1.0.0
 * @package           Windsor Strava Athlete
 *
 * @wordpress-plugin
 * Plugin Name:       Windsor Strava Athlete
 * Plugin URI:        https://windsorup.com/windsor-wordpress-strava-plugin-athlete/
 * Description:       Displays your Strava profile and stats
 * Version:           1.3.0
 * Author:            Justin W Hall
 * Author URI:        https://windsorup.com/windsor-wordpress-strava-plugin-athlete/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       windsor-strava-athlete
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WSA_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-windsor-strava-athlete.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-windsor-strava-athlete-units.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_windsor_strava_athlete() {

	$plugin = new Windsor_Strava_Athlete();
	$plugin->run();

}
run_windsor_strava_athlete();
