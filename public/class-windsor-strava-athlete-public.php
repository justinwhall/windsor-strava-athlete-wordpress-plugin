<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://windsorup.com/windsor-strava-athlete-wordpress-plugin/
 * @since      1.1.7
 *
 * @package    Windsor_Strava_Athlete
 * @subpackage Windsor_Strava_Athlete/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Windsor_Strava_Athlete
 * @subpackage Windsor_Strava_Athlete/public
 * @author     Justin W Hall <justin@windsorup.com>
 */
class Windsor_Strava_Athlete_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	private $athlete;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'windsor_strava_athlete_profile', array( $this, 'wsa_render_athlete_profile' ) );
		add_shortcode( 'windsor_strava_athlete_single_activity', array( $this, 'wsa_render_single_activity' ) );
		add_shortcode( 'windsor_strava_athlete_feed', array( $this, 'wsa_render_feed' ) );

	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/windsor-strava-athlete-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$wsa_options = get_option('wsa_options');
		if (isset($wsa_options['gmaps_api_key'])) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/windsor-strava-athlete-public.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'windsor_google_maps', '//maps.google.com/maps/api/js?libraries=geometry&key=' . $wsa_options['gmaps_api_key'] , array( 'jquery', $this->plugin_name ), '', false );
			wp_enqueue_script( 'windsor_rich_marker', plugin_dir_url( __FILE__ ) . 'js/richmarker-compiled.js', array('windsor_google_maps', 'jquery', $this->plugin_name ), $this->version, false );
		}

	}

	public function get_distance( $val ){
		$unit = ($this->athlete->measurement_preference == 'feet') ? 0.000621371192 : 0.001;
	    return number_format( ceil( $val * $unit ) );
	}

	public function get_elevation( $val ){
		$unit = ($this->athlete->measurement_preference == 'feet') ? 3.28084 : 1;
	    return number_format( ceil( $val * $unit ) );
	}

	/**
	 * render the shortcode.
	 *
	 * @since  1.0.0
	 * 
	 * @param  array $atts // shortcode options
	 * @return string 
	 */
	public  function wsa_render_athlete_profile( $atts ) {


		$wsa_options = get_option( 'wsa_options' );
		// Provide defaults.
		$atts = shortcode_atts( 
			array(
				'athleteid' => false,
				'followers' => true,
				'layout'    => 'map', 
				'clubs'     => true,
				'bikes'     => true,
				'stats'     =>  false,
				'lat'       => false,
				'lng'       => false,
				'showfeed'  => false,
				'totals'  => false,
				'zoom'      => 8
			), $atts );

		$atts['totals'] = $this->parse_totals($atts);

		$atts['mapid'] = $atts['athleteid'];

		if ($atts['athleteid'] && isset($wsa_options['api_key'])) {

			$atts['stats'] = array_map( 'trim', explode(',', $atts['stats'] ) );
			$atts['stats']['totals'] = array_map( 'trim', $atts['totals'] );

			$wsa_options = get_option('wsa_options');
			$headers = array('Authorization: Bearer ' . $wsa_options['api_key']);

			// Athlete Bio
			$ch = curl_init();
			$url='https://www.strava.com/api/v3/athletes/' . $atts['athleteid'];
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$athlete = json_decode(curl_exec($ch));
			$this->athlete = json_decode(curl_exec($ch));


			// Athlete stats
			if ($atts['stats']){
				$ch = curl_init();
				$url='https://www.strava.com/api/v3/athletes/' . $atts['athleteid'] . '/stats';
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				$stats = json_decode(curl_exec($ch));
			}


			if ($atts['showfeed']) {
				// Athlete activity
				$ch = curl_init();
				$url='https://www.strava.com/api/v3/athlete/activities';
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				$activities = curl_exec($ch);
			}

			ob_start();

				echo '<div class="wsa-wrap">';
				include( WSA_PATH . '/includes/view-profile.php');
				if ($atts['showfeed']) {
					include( WSA_PATH . '/includes/view-activities-map.php');
				}
				echo '<div class="powered-by-wsa" target="_blank">Powered by <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">Windsor Strava Athlete</a></div></div>';
				
			return ob_get_clean(); 

		}
		else{

			if (!$atts['athleteid']) {
				echo _e('No, athlete ID. See the <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">docs</a> to learn how to find this.<br />');
			}
			if (!isset($wsa_options['api_key'])) {
				echo _e('No, Strava API key saved <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">docs</a> to learn how to find this.');
			}

		}
	}

	public static function wsa_render_feed( $atts ) {

		$wsa_options = get_option( 'wsa_options' );
		// Provide defaults.
		
		$atts = shortcode_atts( 
			array(
				'athleteid' => false,
				'activities' => 5,
				'followers' => true,
				'clubs' => true
			), $atts );


		if ($atts['athleteid'] && isset($wsa_options['api_key'])) {

			$wsa_options = get_option('wsa_options');
			$headers = array('Authorization: Bearer ' . $wsa_options['api_key']);

			$ch = curl_init();
			$url='https://www.strava.com/api/v3/athletes/' . $atts['athleteid'];
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$athlete = json_decode(curl_exec($ch));
			// $this->athlete = json_decode(curl_exec($ch));

			$ch = curl_init();
			$url='https://www.strava.com/api/v3/athlete/activities';
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$activities = json_decode(curl_exec($ch));


			ob_start();
			include( WSA_PATH . '/includes/view-feed.php');
			return ob_get_clean();


		} else {

			if (!$atts['athleteid']) {
				echo _e('No, athlete ID. See the <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">docs</a> to learn how to find this.<br />');
			}
			if (!isset($wsa_options['api_key'])) {
				echo _e('No, Strava API key saved <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">docs</a> to learn how to find this.');
			}

		}


	}

	public function parse_totals( $atts ) {
		if($atts['totals'] === false){
			$totals = false;
		} else {
			$totals = array_map( 'trim', explode( ',', $atts['totals'] ) );
		}

		return $totals;
	}
	
	public function wsa_render_single_activity ( $atts ) {

		$wsa_options = get_option('wsa_options');
		$atts = shortcode_atts( 
			array(
				'activityid' => false,
				'followers'  => false,
				'lat'        => false,
				'lng'        => false,
				'zoom'       => 8,
				'mapid'      => false
			), $atts );

			$atts['mapid'] = $atts['activityid'];

		if (isset($wsa_options['api_key']) && $atts['activityid'] != false ) {

			$wsa_options = get_option('wsa_options');
			$headers = array('Authorization: Bearer ' . $wsa_options['api_key']);

			//single activity
			$ch = curl_init();
			$url='https://www.strava.com/api/v3/activities/' . $atts['activityid'];
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$activity = curl_exec($ch);
			$activity_decode = json_decode($activity);

			// Athlete Bio
			$ch = curl_init();
			$url='https://www.strava.com/api/v3/athletes/' . $activity_decode->athlete->id;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$athlete = curl_exec($ch);

			ob_start();

			include( WSA_PATH . '/includes/view-activity-sinlge-map.php');

				
			return ob_get_clean(); 
		}
		else{

			if (!$atts['activityid']) {
				echo _e('No, activity ID. See the <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">docs</a> to learn how to find this.<br />');
			}
			if (!isset($wsa_options['api_key'])) {
				echo _e('No, Strava API key saved <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">docs</a> to learn how to find this.<br>');
			}
			if (!isset($wsa_options['gmaps_api_key'])) {
				echo _e('No, Google Maps API key saved <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">docs</a> to learn how to find this.');
			}

		}
	}


}