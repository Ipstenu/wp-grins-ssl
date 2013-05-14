<?php
/*
Plugin Name: SSL Grins
Plugin URI: http://halfelf.org/plugins/wp-grins-ssl
Description: A Clickable Smilies hack for WordPress.
Version: 5.0
Author: Alex King, Ronald Huereca, Mika Epstein
Author URI: http://www.ipstenu.org
Props: Original author, Alex King.  Original fork, Ronald Huereca

Original plugin WP Grins Copyright (c) 2004-2007 Alex King
http://alexking.org/projects/wordpress

SSL version created on June 20, 2008 by Ronald Huereca
SSL fork created on Sept 21, 2011 by Mika "Ipstenu" Epstein
Copyright 2011-2013 Mika Epstein (email: ipstenu@ipstenu.org)

    This file is part of SSL Grins, a plugin for WordPress.

    SSL Grins is free software: you can redistribute it and/or 
	modify it under the terms of the GNU General Public License as published 
	by the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    SSL Grins is distributed in the hope that it will be
    useful, but WITHOUT ANY WARRANTY; without even the implied warranty
    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.


*/

global $wp_version;
	if (version_compare($wp_version,"3.4","<")) { exit( __('This plugin requires WordPress 3.4', 'ippy-wpg') ); }


if (!class_exists('WPGrins')) {
    class WPGrins	{
		/**
		* PHP 5 Constructor
		*/		


		var $wpgs_defaults;
		var $wpgs_bbp_fancy;
	
	    public function __construct() {
	        add_action( 'init', array( &$this, 'init' ) );
	        
	    	// Setting plugin defaults here:
			$this->wpgs_defaults = array(
		        'comments'      => '0',
		        'bbpress'       => '0',
		        'buddypress'    => '0',
		    );
		
		    //global $wpgs_bbp_fancy;
		    $this->wpgs_bbp_fancy = get_option('_bbp_use_wp_editor');

		    // Register and define the settings
			add_action('admin_init', array(&$this,'admin_init' ) );

			//Scripts
			add_action('wp_print_scripts', array(&$this,'add_scripts_frontend'),1000);
			//Styles
			add_action('wp_print_styles', array(&$this,'add_styles_frontend'));
			
			//Ajax
			add_action('wp_ajax_grins', array(&$this,'ajax_print_grins'));
			add_action('wp_ajax_nopriv_grins', array(&$this,'ajax_print_grins')); 
		}
		function ajax_print_grins() {
			echo $this->wp_grins();
			exit;
		}
				
		function wp_grins() {
				global $wpsmiliestrans;
				$grins = '';
				$smiled = array();
				foreach ($wpsmiliestrans as $tag => $grin) {
					if (!in_array($grin, $smiled)) {
						$smiled[] = $grin;
						$tag = esc_attr(str_replace(' ', '', $tag));
						$srcurl = apply_filters('smilies_src', includes_url("images/smilies/$grin"), $grin, site_url());
						$grins .= "<img src='$srcurl' alt='$tag' onclick='jQuery.wpgrins.grin(\"$tag\");' />";
						
					}
				}
				return $grins;
		} //end function wp_grins
		
		function add_styles() {
			wp_enqueue_style('wp-grins', plugins_url('wp-grins-ssl/wp-grins.css'));
		}
		function add_scripts() {
			wp_enqueue_script('wp_grins_ssl', plugins_url('wp-grins-ssl/wp-grins.js'), array("jquery"), 1.0); 
			wp_localize_script( 'wp_grins_ssl', 'wpgrinsssl', $this->get_js_vars());
		}
		
		function add_styles_frontend() {
    		$options = get_option('ippy_wpgs_options');
    		$valuebb = $options['bbpress'];
    		$valueco = $options['comments'];
    		$ippy_wpgs_bbp_fancy = get_option( '_bbp_use_wp_editor' );
    		
    		if ( function_exists('is_bbpress') ) {
                if ( is_bbpress()  && ( $valuebb != '0') && !is_null($valuebb) && ($ippy_wpgs_bbp_fancy == '0') ) {
                    $this->add_styles();
                }
              }
            if ( comments_open() && is_singular() && ( $valueco != '0') && !is_null($valueco) ) {
                $this->add_styles();
            }
        }		
		function add_scripts_frontend() {
    		$options = get_option('ippy_wpgs_options');
    		$valuebb = $options['bbpress'];
    		$valueco = $options['comments'];
    		$ippy_wpgs_bbp_fancy = get_option( '_bbp_use_wp_editor' );
    		
    		if ( function_exists('is_bbpress') ) {
                if ( is_bbpress()  && ( $valuebb != '0') && !is_null($valuebb) && ($ippy_wpgs_bbp_fancy == '0') ) {
                    $this->add_scripts();
                }
              }
            if ( comments_open() && is_singular() && ( $valueco != '0') && !is_null($valueco) ) {
                $this->add_scripts();
            }
        }
            //Returns various JavaScript vars needed for the scripts
            function get_js_vars() {
                if (is_ssl()) {
                   	$schema_ssl = 'https'; 
                } else { 
                   	$schema_ssl = 'http'; 
                }
                return array(
                    'Ajax_Url' => admin_url('admin-ajax.php', $schema_ssl),
                    'LOCATION' => 'admin'
                );
            } //end get_js_vars
		/*END UTILITY FUNCTIONS*/

	function admin_init(){
	
		register_setting(
			'discussion',               // settings page
			'ippy_wpgs_options',         // option name
			array( $this, 'validate_options') // validation callback
		);
		
		add_settings_field(
			'ippy_wpgs_bbpress',         // id
			'WP Grins',                // setting title
			array( $this, 'setting_input' ),   // display callback
			'discussion',               // settings page
			'default'                   // settings section
		);
	}
	
	// Display and fill the form field
	function setting_input() {
		$options = wp_parse_args(get_option( 'ippy_wpgs_options'), $this->bcq_defaults );
		?>
		<a name="wpgs" value="wpgs"></a><input id='comments' name='ippy_wpgs_options[comments]' type='checkbox' value='1' <?php if ( ( $valueco != '0') && !is_null($valueco) ) { echo ' checked="checked"'; } ?> /> <?php _e('Activate Smilies for comments', 'ippy-wpgs'); ?>
		<?php
		if ( function_exists('is_bbpress') && ($ippy_wpgs_bbp_fancy == '0') ) { ?>
	<br /><input id='bbpress' name='ippy_wpgs_options[bbpress]' type='checkbox' value='1' <?php if ( ( $valuebb != '0') && !is_null($valuebb) ) { echo ' checked="checked"'; } ?> /> <?php _e('Activate Smilies for bbPress', 'ippy-wpgs'); } 
		else { ?>
		<input type='hidden' id='bbpress' name='ippy_wpgs_options[bbpress]' value='0'> <?php } 
	}
	
	// Validate user input
	function validate_options( $input ) {
		$valid = array();
		$valid['comments'] = $input['comments'];
		$valid['bbpress'] = $input['bbpress'];
		$valid['buddypress'] = $input['buddypress'];
		unset( $input );
		return $valid;
	}


    }
}

//instantiate the class
if (class_exists('WPGrins')) {
	$GrinsSSL = new WPGrins();
}
// left in for legacy reasons
if (!function_exists('wp_grins')) {
	function wp_grins() { 
		print('');
	}
}
if (!function_exists('wp_print_grins')) {
	function wp_print_grins() {
		global $GrinsSSL;
		if (isset($GrinsSSL)) {
			return $GrinsSSL->wp_grins();
		}
	}
}