<?php

/**
 * @link       http://windsorup.com/windsor-strava-club-wordpress-plugin/
 * @since      1.0.0
 *
 * @package    Windsor_Strava_Club
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('wsc_options');
