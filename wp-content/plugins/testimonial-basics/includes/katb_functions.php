<?php
/**
 * This is the admin file for Testimonial Basics
 *
 * @package		Testimonial Basics WordPress Plugin
 * @copyright	Copyright (c) 2012, Kevin Archibald
 * @license		http://www.gnu.org/licenses/quick-guide-gplv3.html  GNU Public License
 * @author		Kevin Archibald <www.kevinsspace.ca/contact/>
 */
/**
 * Get Testimonial Basics Plugin Options
 * 
 * Array that holds all of the defined values
 * for Testimonial Basics Plugin Options. If the user 
 * has not specified a value for a given Theme 
 * option, then the option's default value is
 * used instead.
 *
 * @uses	katb_get_option_defaults()	defined below
 * 
 * @uses	get_option()
 * @uses	wp_parse_args()
 * 
 * @return	array	$katb_options	current values for all Theme options
 */
 
function katb_get_options() {

	// Get the option defaults
	$katb_option_defaults = katb_get_option_defaults();
	// Globalize the variable that holds the Theme options
	global $katb_options;
	// Parse the stored options with the defaults
	$katb_options = wp_parse_args( get_option( 'katb_testimonial_basics_options', array() ), $katb_option_defaults );
	// Return the parsed array
	return $katb_options;
}

/**
 * Get Testimonial Basics Plugin Option Defaults
 * 
 * Array that holds all of the deault values
 * for Testimonial Basics Plugin Options. 
 *
 * @uses	katb_get_option_parameters()	defined below
 * 
 * 
 * @return	array	$katb_option_defaults	current default values for all Theme options
 */
function katb_get_option_defaults() {
	// Get the array that holds all
	// Theme option parameters
	$katb_option_parameters = katb_get_option_parameters();
	// Initialize the array to hold
	// the default values for all
	// Theme options
	$katb_option_defaults = array();
	// Loop through the option
	// parameters array
	foreach ( $katb_option_parameters as $katb_option_parameter ) {
		$name = $katb_option_parameter['name'];
		// Add an associative array key
		// to the defaults array for each
		// option in the parameters array
		$katb_option_defaults[$name] = $katb_option_parameter['default'];
	}
	// Return the defaults array
	return $katb_option_defaults;
}

/**
 * Get Testimonial Basics Plugin Option Parameters
 * 
 * Array that holds all of the parameters
 * for Testimonial Basics Plugin Options. 
 *
 * @return	array	$options	all elements for each option
 */
function katb_get_option_parameters() {
	
	$options = array (
/* -------------------------Input Form -------------------------------------- */
		'katb_admin_access_level' => array(
			'name' => 'katb_admin_access_level',
			'title' => __('User role to edit testimonials','testimonial-basics'),
			'type' => 'select',
			'valid_options' => array(
				"Administrator", 
				"Editor"
			),
			'description' => __('default: Administrator','testimonial-basics'),
			'section' => 'input',
			'default' => 'Administrator',
			'class' => 'select'
		),
		'katb_contact_email' => array(
			'name' => 'katb_contact_email',
			'title' => __('Testimonial notify email address','testimonial-basics'),
			'type' => 'text',
			'description' => __('Leave blank to use admin email','testimonial-basics'),
			'section' => 'input',
			'default' => '',
			'class' => 'email'
		),
		'katb_use_captcha' => array(
			'name' => 'katb_use_captcha',
			'title' => __( 'Use captcha on input forms' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include captcha.','testimonial-basics'),
			'section' => 'input',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),				
		'katb_include_input_title' => array(
			'name' => 'katb_include_input_title',
			'title' => __( 'Include title on input form' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'input',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_input_title' => array(
			'name' => 'katb_input_title',
			'title' => __('Title for Input Form','testimonial-basics'),
			'type' => 'text',
			'description' => __('Default:Add a Testimonial','testimonial-basics'),
			'section' => 'input',
			'default' => 'Add a Testimonial',
			'class' => 'nohtml'
		),			
		'katb_include_email_note' => array(
			'name' => 'katb_include_email_note',
			'title' => __( 'Include email note' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'input',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_email_note' => array(
			'name' => 'katb_email_note',
			'title' => __('Email note','testimonial-basics'),
			'type' => 'text',
			'description' => __('Default:Email is not published','testimonial-basics'),
			'section' => 'input',
			'default' => 'Email is not published',
			'class' => 'nohtml'
		),
		'katb_show_html_content' => array(
			'name' => 'katb_show_html_content',
			'title' => __('Show html allowed strip in input form','testimonial-basics'),
			'type' => 'checkbox',
			'description' => __('Default: checked','testimonial-basics'),
			'section' => 'input',
			'default' => 1,
			'class' => 'checkbox'
		),
		'katb_show_html_widget' => array(
			'name' => 'katb_show_html_widget',
			'title' => __('Show html allowed strip in widget form','testimonial-basics'),
			'type' => 'checkbox',
			'description' => __('Default: not checked','testimonial-basics'),
			'section' => 'input',
			'default' => 0,
			'class' => 'checkbox'
		),		
// Content Display
		'katb_use_excerpts' => array(
			'name' => 'katb_use_excerpts',
			'title' => __( 'Use excerpts in testimonial display' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'content',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_excerpt_length' => array(
			'name' => 'katb_excerpt_length',
			'title' => __('Excerpt length in characters','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: 400','testimonial-basics'),
			'section' => 'content',
			'default' => '400',
			'class' => 'nohtml'
		),
		'katb_show_website' => array(
			'name' => 'katb_show_website',
			'title' => __( 'Show website in testimonial' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include website','testimonial-basics'),
			'section' => 'content',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_show_date' => array(
			'name' => 'katb_show_date',
			'title' => __( 'Show date in testimonial' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include date','testimonial-basics'),
			'section' => 'content',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_show_location' => array(
			'name' => 'katb_show_location',
			'title' => __( 'Show location in testimonial' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include location','testimonial-basics'),
			'section' => 'content',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_use_gravatars' => array(
			'name' => 'katb_use_gravatars',
			'title' => __( 'Use gravatars' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'content',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),		
		'katb_use_italic_style' => array(
			'name' => 'katb_use_italic_style',
			'title' => __( 'Use italic font style' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'content',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),		
		'katb_use_formatted_display' => array(
			'name' => 'katb_use_formatted_display',
			'title' => __( 'Use formatted display' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'content',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),		
		'katb_content_font' => array(
			'name' => 'katb_content_font',
			'title' => __('Font for Content Display','testimonial-basics'),
			'type' => 'select',
			'valid_options' => array(
				"default font", 
				"Georgia, serif",
				"Verdana, Geneva, sans-serif", 
				"Arial, Helvetica, sans-serif",
				"'Book Antiqua', 'Palatino Linotype', Palatino, serif",
				"Cambria, Georgia, serif",
				"'Comic Sans MS', sans-serif",
				"Tahoma, Geneva, sans-serif",
				"'Times New Roman', Times, serif",
				"'Trebuchet MS', Helvetica, sans-serif"									
			),
			'description' => __('default: Verdana','testimonial-basics'),
			'section' => 'content',
			'default' => 'default font',
			'class' => 'select'
		), 			
		'katb_background_wrap_color' => array(
			'name' => 'katb_background_wrap_color',
			'title' => __('Background Wrap Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #EDEDED','testimonial-basics'),
			'section' => 'content',
			'default' => '#EDEDED',
			'class' => 'hexcolor'
		),
		'katb_testimonial_box_color' => array(
			'name' => 'katb_testimonial_box_color',
			'title' => __('Testimonial Box Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #DBDBDB','testimonial-basics'),
			'section' => 'content',
			'default' => '#DBDBDB',
			'class' => 'hexcolor'
		),
		'katb_testimonial_box_font_color' => array(
			'name' => 'katb_testimonial_box_font_color',
			'title' => __('Testimonial Box Font Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #000000','testimonial-basics'),
			'section' => 'content',
			'default' => '#000000',
			'class' => 'hexcolor'
		),		
		'katb_author_location_color' => array(
			'name' => 'katb_author_location_color',
			'title' => __('Author,Location, and Date Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #000000','testimonial-basics'),
			'section' => 'content',
			'default' => '#000000',
			'class' => 'hexcolor'
		),		
		'katb_website_link_color' => array(
			'name' => 'katb_website_link_color',
			'title' => __('Website Link Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #3384E8','testimonial-basics'),
			'section' => 'content',
			'default' => '#3384E8',
			'class' => 'hexcolor'
		),
		'katb_website_link_hover_color' => array(
			'name' => 'katb_website_link_hover_color',
			'title' => __('Website Link Hover Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #FFFFFF','testimonial-basics'),
			'section' => 'content',
			'default' => '#FFFFFF',
			'class' => 'hexcolor'
		),
//Widget
		'katb_use_widget_excerpts' => array(
			'name' => 'katb_use_widget_excerpts',
			'title' => __( 'Use excerpts in widget testimonial display' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'widget',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_widget_excerpt_length' => array(
			'name' => 'katb_widget_excerpt_length',
			'title' => __('Widget excerpt length in characters','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: 250','testimonial-basics'),
			'section' => 'widget',
			'default' => '250',
			'class' => 'nohtml'
		),
		'katb_widget_show_website' => array(
			'name' => 'katb_widget_show_website',
			'title' => __( 'Show website in widget' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include website','testimonial-basics'),
			'section' => 'widget',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_widget_show_date' => array(
			'name' => 'katb_widget_show_date',
			'title' => __( 'Show date in widget' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include date','testimonial-basics'),
			'section' => 'widget',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_widget_show_location' => array(
			'name' => 'katb_widget_show_location',
			'title' => __( 'Show location in widget' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include location','testimonial-basics'),
			'section' => 'widget',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_widget_use_gravatars' => array(
			'name' => 'katb_widget_use_gravatars',
			'title' => __( 'Use gravatars' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'widget',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_widget_use_italic_style' => array(
			'name' => 'katb_widget_use_italic_style',
			'title' => __( 'Use italic font style' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'widget',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),
		'katb_widget_use_formatted_display' => array(
			'name' => 'katb_widget_use_formatted_display',
			'title' => __( 'Use formatted display' , 'testimonial-basics' ),
			'type' => 'checkbox',
			'description' => __('Check to include','testimonial-basics'),
			'section' => 'widget',
			'default' =>0, // 0 for off
			'class' => 'checkbox'
		),		
		'katb_widget_font' => array(
			'name' => 'katb_widget_font',
			'title' => __('Font for Widget Display','testimonial-basics'),
			'type' => 'select',
			'valid_options' => array(
				"default font", 
				"Georgia, serif",				
				"Verdana, Geneva, sans-serif", 
				"Arial, Helvetica, sans-serif",
				"'Book Antiqua', 'Palatino Linotype', Palatino, serif",
				"Cambria, Georgia, serif",
				"'Comic Sans MS', sans-serif",
				"Tahoma, Geneva, sans-serif",
				"'Times New Roman', Times, serif",
				"'Trebuchet MS', Helvetica, sans-serif"									
			),
			'description' => __('default: Verdana','testimonial-basics'),
			'section' => 'widget',
			'default' => 'default font',
			'class' => 'select'
		),
		'katb_widget_background_color' => array(
			'name' => 'katb_widget_background_color',
			'title' => __('Background Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #EDEDED','testimonial-basics'),
			'section' => 'widget',
			'default' => '#EDEDED',
			'class' => 'hexcolor'
		),
		'katb_widget_font_color' => array(
			'name' => 'katb_widget_font_color',
			'title' => __('Font Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #000000','testimonial-basics'),
			'section' => 'widget',
			'default' => '#000000',
			'class' => 'hexcolor'
		),
		'katb_widget_author_location_color' => array(
			'name' => 'katb_widget_author_location_color',
			'title' => __('Author,Location, and Date Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #000000','testimonial-basics'),
			'section' => 'widget',
			'default' => '#000000',
			'class' => 'hexcolor'
		),
		'katb_widget_website_link_color' => array(
			'name' => 'katb_widget_website_link_color',
			'title' => __('Website Link Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #3384E8','testimonial-basics'),
			'section' => 'widget',
			'default' => '#3384E8',
			'class' => 'hexcolor'
		),
		'katb_widget_website_link_hover_color' => array(
			'name' => 'katb_widget_website_link_hover_color',
			'title' => __('Website Link Hover Color','testimonial-basics'),
			'type' => 'text',
			'description' => __('default: #FFFFFF','testimonial-basics'),
			'section' => 'widget',
			'default' => '#FFFFFF',
			'class' => 'hexcolor'
		),																																				
	);
		
    return apply_filters( 'katb_get_option_parameters', $options );
}

/** katb_testimonial_basics_display_in_code()
 * 
 * This function allows you to use display testimonials in code
 * 
 * It accepts arguments just like in the shortcode and displays accordingly
 *
 * @param string $group group used in database
 * @param string $by: order or date
 * @param string $number: all or a number
 * @param string $id: blank,random or id of the testimonial
 * @param boolean $use_gravatars: true=show gravatars
 * @param boolean $use_excerpts: true=use excerpts
 * @param integer $excerpt_chars = number of characters in excerpt
 * 
 * @return	html_string
 */
 function katb_testimonial_basics_display_in_code($group='all',$by='date',$number='all',$id='',$use_gravatars=0,$use_excerpts=0,$excerpt_chars=200){
 	//set up database table name for later use
	global $wpdb,$tablename;
	$tablename = $wpdb->prefix.'testimonial_basics';
	//get user options
	$katb_options = katb_get_options();
	//Initialize Strings
	$katb_html = '';
	$katb_error = '';
	//validate
	if($by != 'date' && $by != 'order') $by = 'date';
	if($number != 'all' && is_numeric($number) == false ) $number = 'all';
	if($id != 'random' && is_numeric($id) == false) $id = '';
	if($group == '') $group = 'all';
	if($use_gravatars != 0 && $use_gravatars != 1)$use_gravatars = 0;
	if($use_excerpts != 0 && $use_excerpts != 1 )$use_excerpts = 0;
	if(is_numeric($excerpt_chars) == false)$excerpt_chars = 400;
	//OK let's start by getting the testimonial data from the database
	if($id != '' && $id != 'random'){
		$id = intval($id);
		$katb_tdata = $wpdb->get_results("SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_id` = $id ",ARRAY_A );
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group == 'all' && $by == 'date' && $number == 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_date` DESC ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group == 'all' && $by == 'date' && $number != 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group == 'all' && $by == 'order' && $number == 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group == 'all' && $by == 'order' && $number != 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group != 'all' && $by == 'date' && $number == 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_date` DESC ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group != 'all' && $by == 'date' && $number != 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group != 'all' && $by == 'order' && $number == 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == '' && $group != 'all' && $by == 'order' && $number != 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == 'random' && $group == 'all' &&  $number == 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY RAND() ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == 'random' && $group == 'all' &&  $number != 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY RAND() LIMIT 0,$number ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == 'random' && $group != 'all' &&  $number == 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY RAND() ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	} elseif ($id == 'random' && $group != 'all' &&  $number != 'all') {
		$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY RAND() LIMIT 0,$number ",ARRAY_A);
		$katb_tnumber = $wpdb->num_rows;
	}
	if ( $katb_tnumber == 0 ) $katb_error = __('Testimonial not found. Please check your function call variables.','testimonial_basics');
	// Database queried
	//Lets prepare the return string
	if( $katb_error != '') {
		$katb_html = '<div class="katb_error">'.$katb_error.'</div>';
	} else {
		for ( $i = 0 ; $i < $katb_tnumber; $i++ ) {
			//set up hidden popup if excerpt is used	
			if ( $use_excerpts == 1 ) {
				$katb_html .= '<div class="katb_topopup" id="katb_from_code_'.$katb_tdata[$i]['tb_id'].'">';
				$katb_html .= '<div class="katb_close"></div>';
				$katb_html .= '<div class="katb_popup_text">'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div><br/>';
				$katb_html .= '<span class="katb_popup_meta">'.stripcslashes($katb_tdata[$i]['tb_name']);
				if ($katb_options['katb_show_date'] == 1) {
					$katb_date = $katb_tdata[$i]['tb_date'];
					$year = intval(substr($katb_date,0,4));
					$month = intval(substr($katb_date,5,2));
					$monthname = date("M", mktime(0, 0, 0, $month, 10));
					$day = intval(substr($katb_date,8,2));
					$katb_html .= ', '.$monthname.' '.$day.'\''.$year;
				}
				if ($katb_options['katb_show_location'] == 1) {
					if ( $katb_tdata[$i]['tb_location'] != "" ) $katb_html .= ', '.stripcslashes($katb_tdata[$i]['tb_location']);
				}
				$katb_html .= '</span></div>';
				$katb_html .= '<div class="katb_loader"></div>';
				$katb_html .= '<div class="katb_excerpt_popup_bg" id="katb_from_code_'.$katb_tdata[$i]['tb_id'].'_bg"></div>';
			}
			if ( $use_gravatars == 1 ) {
				$has_valid_avatar = katb_validate_gravatar($katb_tdata[$i]['tb_email']);
				If ( $has_valid_avatar == 1 ) {
					$katb_html .= '<span class="katb_p_avatar">'.get_avatar( $katb_tdata[$i]['tb_email'], $size = '60' ).'</span>';
				}
			}			
			if ($use_excerpts == 1) {
				$text = stripcslashes($katb_tdata[$i]['tb_testimonial']);
				$classID = 'katb_from_code_'.$katb_tdata[$i]['tb_id'];
				$text = katb_testimonial_excerpt_filter($excerpt_chars,$text,$classID);
				$katb_html .= '<div class="katb_p_test" >'.$text.'</div>';
			} else {
					$katb_html .= '<div class="katb_p_test" >'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div>';
			}
			$katb_html .= '<div class="katb_p_authorstrip"><b>'.stripcslashes($katb_tdata[$i]['tb_name']).'</b>';
			if ($katb_options['katb_show_date'] == 1) {
				$katb_date = $katb_tdata[$i]['tb_date'];
				$year = intval(substr($katb_date,0,4));
				$month = intval(substr($katb_date,5,2));
				$monthname = date("M", mktime(0, 0, 0, $month, 10));
				$day = intval(substr($katb_date,8,2));
				$katb_html .= ', '.$monthname.' '.$day.'\''.$year;
			}
			if ($katb_options['katb_show_location'] == 1) {
				if ( $katb_tdata[$i]['tb_location'] != "" ) $katb_html .= ', '.stripcslashes($katb_tdata[$i]['tb_location']);
			}
			if ($katb_options['katb_show_website'] == 1) {
				if ( $katb_tdata[$i]['tb_url'] != "" ) $katb_html .= ', <a href="'.esc_url($katb_tdata[$i]['tb_url']).'" title="Testimonial_author_site" target="_blank" >'.$katb_tdata[$i]['tb_url'].'</a>';
			}
			$katb_html .= '</div><br/>';
		}
	}	
	return $katb_html;
}

/**
 * Get Testimonial Basics HTML Filters
 * 
 * Array that holds all of the parameters
 * for Testimonial Basics Plugin Options. 
 *
 * @return	array	$options	all elements for each option
 */
function katb_allowed_html() {
	
	$allowed_html = array (
		'p' => array(),
    	'br' => array(),
		'i' => array(),
		'h1' => array(),
		'h2' => array(),
		'h3' => array(),
		'h4' => array(),
		'h5' => array(),
		'h6' => array(),
		'em' => array(),
		'strong' => array(),
		'q' => array(),
	);
		
    return apply_filters( 'katb_allowed_html', $allowed_html );
}

/**
 * Test if Gravatar Exists
 * 
 * source : http://codex.wordpress.org/Using_Gravatars
 *
 * @return	boolean	$has_valid_avatar
 */
function katb_validate_gravatar($email) {
	// Craft a potential url and test its headers
	$hash = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers($uri);
	if (!preg_match("|200|", $headers[0])) {
		$has_valid_avatar = FALSE;
	} else {
		$has_valid_avatar = TRUE;
	}
	return $has_valid_avatar;
}

/**
 * Gets the current page url for use in a redirect after the testimonial has been submitted
 * @link http://kovshenin.com/2012/current-url-in-wordpress/
 */ 
function katb_current_page_url() {
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	return $current_url;
}

/**
 * This function checks to see if the main input form has submitted a testimonial.
 * It uses the add_action('parse_request','katb_check_for_submitted_testimonial') hook.
 * I don't know why this hook works. I was looking for a different approach to the form 
 * submission and check within the same function as this method was causein duplicate 
 * submissions in some cases. So I took a look at the user-submitted-posts plugin and 
 * adopted their method whic separates the validation and data submission from the form re-load. 
 * I didn't really copy their method just adapted it to a shortcode form submission.
 * The parse_request action hook was used and the documentation in WordPress.org is not very good.
 * It simply says "This action hook is executed at the end of WordPress's built-in request parsing 
 * method in the main WP() class." Anyway it works. 
 */
//check for submitted testimonial
function katb_check_for_submitted_testimonial() {
	global $wpdb,$tablename;
	global $katb_author,$katb_email,$katb_website,$katb_location,$katb_testimonial,$katb_input_error,$katb_input_success;
	$tablename = $wpdb->prefix.'testimonial_basics';
	$katb_options = katb_get_options();
	$katb_allowed_html = katb_allowed_html();
	if ( isset ( $_POST['katb_submitted'] ) && wp_verify_nonce( $_POST['katb_main_form_nonce'],'katb_nonce_1')) {
	
		//Initialize error message
		$katb_input_error = '';
		//Initialize session variable used to check if testimonial was successfully submitted
		$_SESSION['katb_submitted'] = SHA1('false');
		//Initialize return string
		$html_string = '';
		//Validate-Sanitize Input
		//Set Defaults
		$katb_order = "";
		$katb_approved = 0;
		$katb_group = "";
		$katb_datetime = current_time('mysql');
		//Validate-Sanitize Author
		$katb_author = sanitize_text_field($_POST['tb_author']);
		$_POST['tb_author'] = '';
		if ($katb_author == "") {
			$katb_input_error .= '*'.__('Author is required','testimonial-basics').'*';
		}
		//Validate-Sanitize E-mail
		$katb_email = sanitize_email($_POST['tb_email']);
		if(!is_email($katb_email)) {
			$katb_input_error .= '*'.__('Valid email is required','testimonial-basics').'*';
		}
		//Validate-Sanitize Website
		$katb_website = trim($_POST['tb_website']);
		if ($katb_website != '')$katb_website = esc_url($katb_website);
		//Validate Location
		$katb_location = sanitize_text_field($_POST['tb_location']);
		//Validate-Sanitize Testimonial
		$katb_testimonial = wp_kses($_POST['tb_testimonial'],$katb_allowed_html);
		if ($katb_testimonial == "" ) {
			$katb_input_error .= '*'.__('Testimonial is required','testimonial-basics').'*';
		}
		//Captcha Check
		if ($katb_options['katb_use_captcha'] == TRUE || $katb_options['katb_use_captcha'] == 1 ) {
			$katb_captcha_entered = sanitize_text_field($_POST['verify']);
			if ($_SESSION['katb_pass_phrase'] !== sha1($katb_captcha_entered)){
				$katb_input_error .= '*'.__('Captcha is invalid - please try again','testimonial-basics').'*';
			}
		}
		//Validation complete
		if($katb_input_error == "") {
			//OK $katb_input_error is empty so let's update the database
			$values = array(
				'tb_date' => $katb_datetime,
				'tb_order' => $katb_order,
				'tb_approved' => $katb_approved,
				'tb_group' => $katb_group,
				'tb_name' => $katb_author,
				'tb_email' => $katb_email,
				'tb_location' => $katb_location,
				'tb_url' => $katb_website,
				'tb_testimonial' => $katb_testimonial
			);
			$formats_values = array('%s','%d','%d','%s','%s','%s','%s','%s','%s');
			$wpdb->insert($tablename,$values,$formats_values);
			$_SESSION['katb_submitted'] = SHA1('true');
			//send email
			if ( $katb_options['katb_contact_email'] != '' ) {
				$emailTo = $katb_options['katb_contact_email'];
			} else {
				$emailTo = get_option('admin_email');
			}
			$subject = __('You have received a testimonial!','testimonial-basics');
			$body = __('Name: ','testimonial-basics').' '.stripcslashes($katb_author)."<br/><br/>".__('Email: ','testimonial-basics').' '.stripcslashes($katb_email)."<br/><br/>".__('Comments: ','testimonial-basics')."<br/><br/>".stripcslashes($katb_testimonial);
			$headers = 'From: '.stripcslashes($katb_author).' <'.stripcslashes($katb_email).'>';
			add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
			wp_mail( $emailTo, $subject, $body, $headers );
			//Now empty the variables
			$katb_id = "";
			$katb_order = "";
			$katb_approved = "";
			$katb_date = "";
			$katb_time = "";
			$katb_author = "";
			$katb_website = "";
			$katb_location = "";
			$katb_testimonial = "";
			$katb_email = "";
			$redirect = katb_current_page_url();
			wp_redirect( $redirect );
			exit;
		}
	} else {
		$katb_id = "";
		$katb_order = "";
		$katb_approved = "";
		$katb_date = "";
		$katb_time = "";
		$katb_author = "";
		$katb_website = "";
		$katb_location = "";
		$katb_testimonial = "";
		$katb_email = "";
		$html_string = "";
	}
	/* ---------- Reset button is clicked ---------------- */
	if(isset($_POST['katb_reset'])) {
		$katb_author = "";
		$katb_email = "";
		$katb_website = "";
		$katb_location = "";
		$katb_testimonial = "";
	}
}
add_action ('parse_request', 'katb_check_for_submitted_testimonial');

/**
 * Dynamic Encerpt Function
 * 
 * modified from @link http://wordpress.org/support/topic/dynamic-the_excerpt?replies=22 
 * This function filters the content to the passed character length from where it was called.
 * It then returns the string with a post link so the user can click the link to go to the post
 * 
 * @param int $length length to filter to in characters, passed from function call
 * WordPress Functions - see Codex
 * @uses get_the_content() @uses apply_filters() @uses strip_shortcodes()
 * @uses get_permalink()
 * 
 */
function katb_testimonial_excerpt_filter($length,$text,$classID) { 
		
		$text = strip_shortcodes($text);
		$text = strip_tags($text,'<p></p><i></i><br/><br /><em></em><strong></strong><q></q><h1></h1><h2></h2><h3></h3><h4></h4><h5></h5><h6></h6>');
		
		$output = strlen($text);
		if($output > $length ) {
			$break_pos = strpos($text, ' ', $length);//find next space after desired length
			if($break_pos == '')$break_pos = $length;
			$text = substr($text,0,$break_pos);
			//$text = force_balance_tags($text);
			$text = $text.' <a href="#" class="katb_excerpt_more" data-id="'.$classID.'" > ...'.__('more','testimonial-basics').'</a>';
			$text = force_balance_tags($text);
		}
	
		return $text;
	}

?>