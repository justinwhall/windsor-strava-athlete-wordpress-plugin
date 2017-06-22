<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://windsorup.com/windsor-strava-athlete-wordpress-plugin/
 * @since      1.0.0
 *
 * @package    Windsor_Strava_Athlete
 * @subpackage Windsor_Strava_Athlete/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Windsor_Strava_Athlete
 * @subpackage Windsor_Strava_Athlete/admin
 * @author     Justin W Hall <justin@windsorup.com>
 */
class Windsor_Strava_Athlete_Admin {

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

	/**
	 * plugin options.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the options page for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wsc_register_options_page() {

		// This page will be under "Settings"
		add_options_page(
		    'Settings Admin', 
		    'Windsor Strava Athlete', 
		    'manage_options', 
		    'wsa-setting-admin', 
		    array( $this, 'wsa_create_admin_page' )
		);

	}

	/**
	 * Creates admin options page.
	 *
	 * @since    1.0.0
	 */
	public function wsa_create_admin_page() {

        $this->options = get_option( 'wsa_options' );

        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'wsa_settings_group' );   
                do_settings_sections( 'wsa-setting-admin' );
                do_settings_sections( 'wsa-setting-feed-styles' );
                submit_button(); 
            ?>
            </form>
            Follow me on <a target="blank" href="https://www.strava.com/athletes/533982">Strava</a> or <a target="blank" href="https://twitter.com/justinwhall">Twitter</a>.
        </div>
        <?php

	}

	/**
	 * Registers Plugin Settings
	 *
	 * @since    1.0.0
	 */    
	public function wsc_register_settings() {        
        register_setting(
            'wsa_settings_group', 
            'wsa_options', 
            array( $this, 'sanitize' ) 
        );

        add_settings_section(
            'wsa_options', 
            'Strava Athlete', 
            array( $this, 'print_section_info' ), 
            'wsa-setting-admin'
        );     

        add_settings_section(
            'wsa_options', 
            'Strava Athlete Feed Styles', 
            array( $this, 'print_feed_section_info' ), 
            'wsa-setting-feed-styles'
        );     

        add_settings_field(
            'api_key', 
            'Strava API Key',
            array( $this, 'id_number_callback' ), 
            'wsa-setting-admin', 
            'wsa_options'           
        );      

        add_settings_field(
            'gmaps_api_key', 
            'Google Maps API Key',
            array( $this, 'google_maps_api' ), 
            'wsa-setting-admin', 
            'wsa_options'           
        );  

        add_settings_field(
            'feed_date_px', 
            'Feed Date Pixel Size',
            array( $this, 'feed_date_px' ), 
            'wsa-setting-feed-styles', 
            'wsa_options'           
        );      
        
        add_settings_field(
            'feed_title_px', 
            'Feed Title Pixel Size',
            array( $this, 'feed_title_px' ), 
            'wsa-setting-feed-styles', 
            'wsa_options'           
        );      

        add_settings_field(
            'feed_meta_px', 
            'Feed Meta Pixel Size',
            array( $this, 'feed_meta_px' ), 
            'wsa-setting-feed-styles', 
            'wsa_options'           
        );      
      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $new_input = array();

        foreach ( $input as $key => $value ) {
            if( isset( $input[$key] ) )
                $new_input[$key] = sanitize_text_field( $input[$key] );
        }

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        $url = 'https://www.littlebot.io/docs/windsor-strava-athlete/getting-started/';
        $link = sprintf( wp_kses( __( 'Learn how to get your API key <a href="%s">here</a>.', 'my-text-domain' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
        echo $link;
    }

    public function print_feed_section_info()
    {
        print __('Change the text size of your feed. This can help formatting if using in the sidebar.', 'windsor-strava-athlete');
    }


    public function feed_date_px()
    {
       printf(
           '<input type="number" style="width: 50px;" id="feed_date_px" name="wsa_options[feed_date_px]" value="%s" /> px',
           isset( $this->options['feed_date_px'] ) ? esc_attr( $this->options['feed_date_px']) : 14
       );
    }
    
    public function feed_title_px()
    {
       printf(
           '<input type="number" style="width: 50px;" id="feed_title_px" name="wsa_options[feed_title_px]" value="%s" /> px',
           isset( $this->options['feed_title_px'] ) ? esc_attr( $this->options['feed_title_px']) : 23
       );
    }

    public function feed_meta_px()
    {
       printf(
           '<input type="number" style="width: 50px;" id="feed_meta_px" name="wsa_options[feed_meta_px]" value="%s" /> px',
           isset( $this->options['feed_meta_px'] ) ? esc_attr( $this->options['feed_meta_px']) : 16
       );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="api_key" name="wsa_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }

    public function google_maps_api()
    {
        printf(
            '<input type="text" id="gmaps_api_key" name="wsa_options[gmaps_api_key]" value="%s" />',
            isset( $this->options['gmaps_api_key'] ) ? esc_attr( $this->options['gmaps_api_key']) : ''
        );
    }


}