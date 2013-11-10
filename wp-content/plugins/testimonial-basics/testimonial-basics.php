<?php
/*
Plugin Name: Testimonial Basics
Plugin URI: http://www.kevinsspace.ca/testimonial-basics-wordpress-plugin/
Description: This plugin facilitates easy management of customer testimonials. The user can set up an input form in a page or in a widget, and display all or selected testimonials in a page or a widget. The plug in is very easy to use and modify.
Version: 3.0.0
Author: Kevin Archibald
Author URI: http://www.kevinsspace.ca
License: GPLv3
===================================================================================================
                       LICENSE
===================================================================================================

License: GNU General Public License V3
License URI: see the license.txt file for license details.

	testimonial-basics is a plugin for WordPress
    Copyright (C) 2012 Kevin Archibald

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/** ----------- Session Start ----------------------------------------------
 * Start session if not already started. The session is required
 * for passing the password from katb_captcha.php to the input
 * form data validation
 * ------------------------------------------------------------------------- */
if(!isset($_SESSION)) session_start();

/** ------------- Prevent Direct Access ---------------------------------------
* prevent file from being accessed directly
* ---------------------------------------------------------------------------- */
if ('testimonial-basics.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

/** ------------- Plugin Activation ---------------------------------------
 *
 * When the plugin is activated this function is executed
 * It initially checks for a minimum version of WordPress and won't load if
 * the WordPress Version is below that.
 * 
 * Checks for database table and creates one if not there
 * table name : database prefix + testimonial_basics
 * 
 *-------------------------------------------------------------------------- */
 global $katb_db_new_version;
 $katb_db_new_version = '1.1';
 
function katb_testimomial_basics_activate() {
	//Check version compatibilities
    if ( version_compare( get_bloginfo( 'version' ), '3.1', '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
        die ('Please Upgrade your WprdPress to use this plugin.');
    }
	//Check for database table and create if not there
	global $wpdb;
	global $katb_db_new_version;
	$katb_tb_installed_version = get_option( 'katb_database_version' );
	$tablename = $wpdb->prefix.'testimonial_basics';
	$tableprefix = strtolower($wpdb->prefix);
	if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename && $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tableprefix.'testimonial_basics' ) {
		// add charset & collate like wp core
		$charset_collate = '';
		if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}
		//create new table
		$sql = "CREATE TABLE `$tablename` (
				`tb_id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  				`tb_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  				`tb_group` char(20) NOT NULL,
  				`tb_order` int(4) UNSIGNED NOT NULL,
  				`tb_approved` int(1) UNSIGNED NOT NULL,
  				`tb_name` char(100) NOT NULL,
  				`tb_location` char(100) NOT NULL,
  				`tb_email` char(100) NOT NULL,
 				`tb_url` char(100) NOT NULL,
  				`tb_testimonial` text NOT NULL,
  				PRIMARY KEY (`tb_id`)
			)$charset_collate;";
		require_once ( ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		update_option('katb_database_version',$katb_db_new_version);
	} elseif ( $katb_tb_installed_version !== $katb_db_new_version ) {
		//table requires upgrading
		$sql = "CREATE TABLE `$tablename` (
				`tb_group` char(20) NOT NULL,
  				`tb_email` char(100) NOT NULL
 			);";
		require_once ( ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		update_option('katb_database_version',$katb_db_new_version);
	}
}
register_activation_hook( __FILE__, 'katb_testimomial_basics_activate' );

function katb_update_db_check() {
    global $katb_db_new_version;
    if (get_option('katb_database_version') != $katb_db_new_version) {
        katb_testimomial_basics_activate();
    }
}
add_action('plugins_loaded', 'katb_update_db_check');

/** ------------- Plugin Deactivation ---------------------------------------
 *
 * When the plugin is deactivated this function is executed
 * 
 * Nothing is required to be done with tis plugin but I left the function here
 * as a reminder
 * 
 * --------------------------------------------------------------------------- */
function katb_testimomial_basics_deactivate() {
	//Do these things when deactvated
}
register_deactivation_hook(__FILE__,'katb_testimomial_basics_deactivate');

/** ------------- Load Admin Functions ---------------------------------------
 *
 * The admin functions are loaded if in admin mode
 * 
 * --------------------------------------------------------------------------- */
if( is_admin() ) {
	//load admin functions
	require_once( dirname(__FILE__).'/includes/katb_testimonial_basics_admin.php' );
}

/** ------------- Plugin Setup ---------------------------------------
 *
 * When the plugin is loaded this function is executed
 * 
 * Loads these files on setup
 * Activates the translation on setup
 * 
 * ---------------------------------------------------------------------- */
function katb_testimonial_basics_plugin_setup () {
	require_once( dirname(__FILE__).'/includes/katb_shortcodes.php' );
	require_once( dirname(__FILE__).'/widgets/katb_input_testimonial_widget.php' );
	require_once( dirname(__FILE__).'/widgets/katb_testimonial_widget.php' );
	require_once( dirname(__FILE__).'/widgets/katb_testimonial_multiple_display_widget.php' );
	require_once( dirname(__FILE__).'/includes/katb_functions.php' );
	//enable translation
	load_plugin_textdomain('testimonial-basics', false, 'testimonial-basics/languages');
}
add_action( 'plugins_loaded','katb_testimonial_basics_plugin_setup');

/** ------------- Enqueue Styles ---------------------------------------
 *
 * When the plugin is activated this function is executed
 * 
 * Loads these files on setup
 * Activates the translation on setup
 * 
 * ----------------------------------------------------------------------- */
function katb_add_user_style(){
	wp_register_style( 'katb_user_styles',plugin_dir_url(__FILE__).'css/katb_user_styles.css' );
	wp_enqueue_style( 'katb_user_styles' );
}
add_action('wp_enqueue_scripts','katb_add_user_style');

/** ------------- Enqueue Scripts ---------------------------------------
 *
 * When the plugin is activated this function is executed
 * 
 * Loads these files on setup
 * Activates the excerpt jQuery script if needed
 * 
 * ----------------------------------------------------------------------- */
function katb_load_excerpt_jquery () {
	$katb_options = katb_get_options();
	if ( $katb_options['katb_use_widget_excerpts'] == 1 || $katb_options['katb_use_excerpts'] == 1 ) {
		wp_enqueue_script( 'testimonial_basics_excerpt_js', plugins_url() . '/testimonial-basics/js/katb_excerpt_doc_ready.js', array('jquery'));
	}
	
}
add_action('wp_enqueue_scripts','katb_load_excerpt_jquery');

/** ------------- Custom Styles ---------------------------------------
 *
 * This function loads custom user styles if required
 * 
 * Initially it loads the user options from the database.
 * If the user has selected custom testimonial display for the content
 * or for the widget displa it loads the respective files
 * 
 * uses katb_get_options() located in \includes\katb_functions.php
 * 
 * ---------------------------------------------------------------------- */
function katb_add_custom_styles(){
	require_once( dirname(__FILE__).'/css/katb_display_custom_style.php' );
	require_once( dirname(__FILE__).'/css/katb_widget_custom_style.php' );
 }
add_action( 'wp_print_styles', 'katb_add_custom_styles' );


/** ------------- Filter for Content Area Input Form ---------------------------------------
 *
 * This function is the filter used to load the testimonial form in a content area
 * The user enters <p><!-- katb_input_form --></p> or <!-- katb_input_form --> 
 * in the content area. When the filter picks this up katb_input_form is called
 * and the filtered string is replaced by the input form ($html_string)
 * 
 * 
 * uses katb_input_form() defined above
 * accepts string $content
 * rerurns string $content
 * 
 * ------------------------------------------------------------------------------------------ */
function katb_insert_input_form($content){
	if (strpos($content, "<p><!-- katb_input_form --></p>") !== false) {
		//$content = str_replace("<p><!-- katb_input_form --></p>", katb_input_form(), $content);
		$content = str_replace("<p><!-- katb_input_form --></p>",katb_display_input_form(), $content);
	}elseif (strpos($content, "<!-- katb_input_form -->") !== false) {
			//$content = str_replace("<!-- katb_input_form -->", katb_input_form(), $content);
			$content = str_replace("<!-- katb_input_form -->",katb_display_input_form(), $content);
	}
	return $content;
}
add_filter('the_content', 'katb_insert_input_form',99);

?>