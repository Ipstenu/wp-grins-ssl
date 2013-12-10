<?php
/*
Plugin Name: SSL Grins
Plugin URI: http://halfelf.org/plugins/wp-grins-ssl
Description: A Clickable Smilies hack for WordPress.
Version: 5.1.1
Author: Alex King, Ronald Huereca, Mika Epstein
Author URI: http://www.ipstenu.org
Text Domain: wp-grins-ssl
Domain Path: /i18n

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
	if (version_compare($wp_version,"3.7","<")) { exit( __('This plugin requires WordPress 3.7', 'wp-grins-ssl') ); }


if (!class_exists('WPGrinsHELF')) {
    class WPGrinsHELF {

		var $wpgs_defaults;
		var $wpgs_bbp_fancy;

        public function __construct() {
            add_action( 'init', array( $this, 'init' ) );
            
    		$this->wpgs_defaults = array(
    	        'comments'      => '0',
    	        'bbpress'       => '0',
    	        'buddypress'    => '0',
    	    );
    	    $this->bcq_bbp_fancy = get_option('_bbp_use_wp_editor');
    	    
        }
    
        public function init() {
			add_action( 'admin_init', array( $this,'admin_init' ) );
			add_action( 'init', array( $this, 'internationalization' ));
            add_filter( 'plugin_row_meta', array( $this, 'donate_link'), 10, 2);
            add_filter( 'plugin_action_links', array( $this, 'add_settings_link'), 10, 2 );


			if( !is_admin() && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' )) ) {
    			add_action('wp_print_scripts', array( $this,'add_scripts_frontend'),1000);
    			add_action('wp_print_styles', array( $this,'add_styles_frontend'));
			}
			add_action('wp_ajax_grins', array( $this,'ajax_print_grins'));
			add_action('wp_ajax_nopriv_grins', array( $this,'ajax_print_grins')); 
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
		}
		
		function add_styles() {
			wp_enqueue_style('wp-grins', plugins_url(dirname(plugin_basename(__FILE__)) . '/wp-grins.css'), false, 5.0);
		}
		function add_scripts() {
			wp_enqueue_script('wp_grins_ssl', plugins_url(dirname(plugin_basename(__FILE__)) . '/wp-grins.js'), array("jquery"), 5.0); 
			wp_localize_script( 'wp_grins_ssl', 'wpgrinsssl', $this->get_js_vars());
		}
		
		function add_styles_frontend() {
    		$options = wp_parse_args(get_option( 'ippy_wpgs_options'), $this->wpgs_defaults );
    		
    		if ( function_exists('is_bbpress') ) {
                if ( is_bbpress()  && ( $options['bbpress'] != '0') && !is_null($options['bbpress']) && ( $this->bcq_bbp_fancy == false ) ) {
                    $this->add_styles();
                }
              }
            if ( comments_open() && is_singular() && ( $options['comments'] != '0') && !is_null($options['comments']) ) {
                $this->add_styles();
            }
        }		
		function add_scripts_frontend() {
    		$options = wp_parse_args(get_option( 'ippy_wpgs_options'), $this->wpgs_defaults );
    		
    		if ( function_exists('is_bbpress') ) {
                if ( is_bbpress()  && ( $options['bbpress'] != '0') && !is_null($options['bbpress']) && ( $this->bcq_bbp_fancy == false ) ) {
                    $this->add_scripts();
                }
              }
            if ( comments_open() && is_singular() && ( $options['comments'] != '0') && !is_null($options['comments']) ) {
                $this->add_scripts();
            }
        }
        
        function get_js_vars() {
            if (is_ssl()) { $schema_ssl = 'https'; } 
            else { $schema_ssl = 'http'; }
            return array(
                'Ajax_Url' => admin_url('admin-ajax.php', $schema_ssl),
                'LOCATION' => 'admin'
            );
        }

    	function admin_init(){
    		register_setting(
    			'discussion',               // settings page
    			'ippy_wpgs_options',         // option name
    			array( $this, 'validate_options') // validation callback
    		);
    		
    		add_settings_field(
    			'ippy_wpgs_bbpress',         // id
    			__('SSL Grins', 'wp-grins-ssl'),                // setting title
    			array( $this, 'setting_input' ),   // display callback
    			'discussion',               // settings page
    			'default'                   // settings section
    		);
    	}
    	
    	// Display and fill the form field
    	function setting_input() {
    		$options = wp_parse_args(get_option( 'ippy_wpgs_options'), $this->wpgs_defaults );
    		?>
    		<a name="wpgs" value="wpgs"></a><input id='comments' name='ippy_wpgs_options[comments]' type='checkbox' value='1' <?php checked( $options['comments'], 1 ); ?> /> <?php _e('Activate Smilies for comments', 'wp-grins-ssl'); ?>
    		<?php
    		if ( function_exists('is_bbpress') && ( $this->bcq_bbp_fancy == false ) ) { ?>
    		  <br /><input id='bbpress' name='ippy_wpgs_options[bbpress]' type='checkbox' value='1' <?php checked( $options['bbpress'], 1 ); ?> /> <?php _e('Activate Smilies for bbPress', 'wp-grins-ssl'); } 
    	}
    	
    	// Validate user input
    	function validate_options( $input ) {
    	    $options = wp_parse_args(get_option( 'ippy_wpgs_options'), $this->wpgs_defaults );
    		$valid = array();

    	    foreach ($options as $key=>$value) {
        	    if (!isset($input[$key])) $input[$key]=$this->wpgs_defaults[$key];
            }
    	    
    	    foreach ($options as $key=>$value) {
        	    $valid[$key] = $input[$key];
            }

    		unset( $input );
    		return $valid;
    	}

    	// Internationalization
    	function internationalization() {
    		load_plugin_textdomain('wp-grins-ssl', false, dirname(plugin_basename(__FILE__)) . '/i18n' );
    	}
    	
    	// donate link on manage plugin page
    	function donate_link($links, $file) {
    	        if ($file == plugin_basename(__FILE__)) {
    	                $donate_link = '<a href="https://www.wepay.com/donations/halfelf-wp">' . __( 'Donate', 'wp-grins-ssl' ) . '</a>';
    	                $links[] = $donate_link;
    	        }
    	        return $links;
    	}
    	
    	// add settings to manage plugin page
    	function add_settings_link( $links, $file ) {
    		if ( plugin_basename( __FILE__ ) == $file ) {
    			$settings_link = '<a href="' . admin_url( 'options-discussion.php' ) . '#wpgs">' . __( 'Settings', 'wp-grins-ssl' ) . '</a>';
    			array_unshift( $links, $settings_link );
    		}
    		return $links;
    	}
    }
}

//instantiate the class
if (class_exists('WPGrinsHELF')) {
	new WPGrinsHELF();
}