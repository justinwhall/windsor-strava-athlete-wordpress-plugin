<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://windsorup.com/windsor-strava-club-wordpress-plugin/
 * @since      1.0.0
 *
 * @package    Windsor_Strava_Club
 * @subpackage Windsor_Strava_Club/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Windsor_Strava_Club
 * @subpackage Windsor_Strava_Club/includes
 * @author     Justin W Hall <justin@windsorup.com>
 */
class Windsor_Strava_Athlete_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'windsor-strava-athlete',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
