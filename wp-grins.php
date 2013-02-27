<?php

// SSL Grins
//
// Original plugin WP Grins
// Copyright (c) 2004-2007 Alex King
// http://alexking.org/projects/wordpress
//
// SSL version created on June 20, 2008 by Ronald Huereca
// SSL version created on Sept 21, 2011 by Mika "Ipstenu" Epstein
// Copyright 20011 Mika Epstein (email: ipstenu@ipstenu.org)
//
// This is an add-on for WordPress
// http://wordpress.org/
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// *****************************************************************

/*
Plugin Name: SSL Grins
Plugin URI: http://halfelf.org/plugins/wp-grins-ssl
Description: A Clickable Smilies hack for WordPress.
Version: 4.4
Author: Alex King, Ronald Huereca, Mika Epstein
Author URI: http://www.ipstenu.org
Props:  Original author, Alex King.  Original fork, Ronald Huereca
*/

if (!class_exists('WPGrins')) {
    class WPGrins	{
		/**
		* PHP 5 Constructor
		*/		
		function __construct(){
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

// Register and define the settings
add_action('admin_init', 'ippy_wpgs_admin_init');

function ippy_wpgs_admin_init(){

	register_setting(
		'discussion',               // settings page
		'ippy_wpgs_options',         // option name
		'ippy_wpgs_validate_options' // validation callback
	);
	
	add_settings_field(
		'ippy_wpgs_bbpress',         // id
		'WP Grins',                // setting title
		'ippy_wpgs_setting_input',   // display callback
		'discussion',               // settings page
		'default'                   // settings section
	);
}

register_activation_hook( __FILE__, 'ippy_wpgs_activate' );

function ippy_wpgs_activate() {
	$options = get_option( 'ippy_wpgs_options' );
	$options['comments'] = '0';
	$options['bbpress'] = '0';
	update_option('ippy_wpgs_options', $options);
}

// Display and fill the form field
function ippy_wpgs_setting_input() {
	// get option value from the database
	$options = get_option( 'ippy_wpgs_options' );
	$valuebb = $options['bbpress'];
	$valueco = $options['comments'];
	$ippy_wpgs_bbp_fancy = get_option( '_bbp_use_wp_editor' );
	?>
<p><?php 
	if ( function_exists('is_bbpress') && ($ippy_wpgs_bbp_fancy == '0') ) { ?>
<input id='bbpress' name='ippy_wpgs_options[bbpress]' type='checkbox' value='1' <?php if ( ( $valuebb != '0') && !is_null($valuebb) ) { echo ' checked="checked"'; } ?> /> Activate Smilies for bbPress<br /> <?php } 
	else { ?>
	<input type='hidden' id='bbpress' name='ippy_wpgs_options[bbpress]' value='0'> <?php } 
?>
<input id='comments' name='ippy_wpgs_options[comments]' type='checkbox' value='1' <?php if ( ( $valueco != '0') && !is_null($valueco) ) { echo ' checked="checked"'; } ?> /> Activate Smilies for comments
	<?php
}

// Validate user input
function ippy_wpgs_validate_options( $input ) {
	$valid = array();
	$valid['comments'] = $input['comments'];
	$valid['bbpress'] = $input['bbpress'];
	unset( $input );
	return $valid;
}

// donate link on manage plugin page

add_filter('plugin_row_meta', 'ippy_wpgs_donate_link', 10, 2);
function ippy_wpgs_donate_link($links, $file) {
        if ($file == plugin_basename(__FILE__)) {
                $donate_link = '<a href="https://www.wepay.com/donations/halfelf-wp">Donate</a>';
                $links[] = $donate_link;
        }
        return $links;
}
