<?php
/**
 * This is the admin file for Testimonial Basics
 *
 * @package		Testimonial Basics WordPress Plugin
 * @copyright	Copyright (c) 2012, Kevin Archibald
 * @license		http://www.gnu.org/licenses/quick-guide-gplv3.html  GNU Public License
 * @author		Kevin Archibald <www.kevinsspace.ca/contact/>
 */
/** -------- Admin Style  katb_testimonial_basics_admin.css	  --------------------------
 * This function loads the admin stylesheet and scripts for Testimonial 
 * Basics admin pages. The styles and scripts are only loaded if a 
 * Testimonial Basics admin page is loaded.
 * file:katb_testimonial_basics_admin.css is in /css and is usde to style the admin pages
 * file:katb_testimonial_basics_doc_ready.js is located in /js and contains scripts for 
 * display in the admin pages, and to display set up the color wheel useage
 * farbtastic :  is the jquery script for the color wheel
 * --------------------------------------------------------------------------------------- */ 
function katb_testimonial_basic_admin_style($hook) {
	//Only enqueue if the admin page is loaded  
	 if( 'testimonial-basics_page_katb_testimonial_basics_admin_options' != $hook  
	      && 'toplevel_page_katb_testimonial_basics_admin' != $hook 
		  && 'testimonial-basics_page_katb_testimonial_basics_admin_edit'!= $hook) return;
	//Page is loaded so go ahead
	global $wp_version;
	//$wp_version = 3.5;	
	wp_register_style( 'testimonial_basic_admin_style',plugins_url() . '/testimonial-basics/css/katb_testimonial_basics_admin.css',array(),'20120815','all' ); 
	wp_enqueue_style( 'testimonial_basic_admin_style' );
	//wp_enqueue_script( 'testimonial_basics_options_js', plugins_url() . '/testimonial-basics/js/katb_testimonial_basics_doc_ready.js', array('jquery'), '1', true );
	 //If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.
    if ( 3.5 <= $wp_version ){
        //Both the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
    }
    //If the WordPress version is less than 3.5 load the older farbtasic color picker.
    else {
        //As with wp-color-picker the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
        wp_enqueue_style( 'farbtastic' );
        wp_enqueue_script( 'farbtastic' );
    }
    //Load our custom javascript file
 	wp_enqueue_script( 'testimonial_basics_options_js', plugins_url() . '/testimonial-basics/js/katb_testimonial_basics_doc_ready.js', array('jquery'), '1', true );
}
add_action('admin_enqueue_scripts', 'katb_testimonial_basic_admin_style');

function katb_testimomial_basics_create_menu() {
	/** global variables to be used to display context senitive help material
	 * see: function katb_testimonial_basics_admin_page_help() below
	 */
	global $katb_testimonial_basics_admin_edit_help,$katb_testimonial_basics_admin_options_help;
	/** add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	 * $page_title - (string) (required) The text to be displayed in the title tags of the page when the menu 
	 *               is selected Default: None 
	 * $menu_title - string) (required) The on-screen name text for the menu Default: None
	 * $capability - (string) (required) The capability required for this menu to be displayed to the user. 
	 *                User levels are deprecated and should not be used here! Default: None
	 * $menu_slug - (string) (required) The slug name to refer to this menu by (should be unique for this menu). 
	 * $function - The function that displays the page content for the menu page.
	 * $icon_url - (string) (optional) The url to the icon to be used for this menu.
	 * $position - (integer) (optional) The position in the menu order this menu should appear.
	 */
	add_menu_page( 'Testimonial Basics', 'Testimonial Basics', 'manage_options', 'katb_testimonial_basics_admin','katb_testimonial_basics_introduction' );
	/** add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	 * $parent_slug - (string) (required) The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * $page_title - (string) (required) The text to be displayed in the title tags of the page when the menu is selected 
	 * $menu_title - (string) (required) The text to be used for the menu 
	 * $capability - (string) (required) The capability required for this menu to be displayed to the user.
	 * $menu_slug - (string) (required) The slug name to refer to this menu by (should be unique for this menu). If you want to NOT 
	 *              duplicate the parent menu item, you need to set the name of the $menu_slug exactly the same as the parent slug. 
	 * $function - (string / array) (optional) The function to be called to output the content for this page.
	 */
	 //Get user options
	$katb_options = katb_get_options();
	if($katb_options['katb_admin_access_level'] == 'Editor') {
		$edit_testimonial_capability = 'moderate_comments';
	} else {
		$edit_testimonial_capability = 'manage_options';
	}
	$katb_testimonial_basics_admin_options_help = add_submenu_page('katb_testimonial_basics_admin','Testimonial Basics Options','Options','manage_options','katb_testimonial_basics_admin_options','katb_testimonial_basics_options_page');
	$katb_testimonial_basics_admin_edit_help = add_submenu_page('katb_testimonial_basics_admin','Testimonial Basics Edit Testimonials','Edit Testimonials',$edit_testimonial_capability,'katb_testimonial_basics_admin_edit','katb_testimonial_basics_edit_page');
}
add_action( 'admin_menu', 'katb_testimomial_basics_create_menu' );

/** ----------------- katb_testimonial_basics_introduction -------------------------
 * Called by add_menu_page. Sets up the Testimonial Basics Option Page
 * ---------------------------------------------------------------------------------- */
function katb_testimonial_basics_introduction (){ ?>
	<?php screen_icon( 'plugins' ); ?>
	<h2>Testimonial Basics</h2>
	<div class="katb_paypal"><?php _e('Show your appreciation!','testimonial-basics') ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
		">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynow_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
	<p><?php _e('Author Site : ','testimonial-basics'); ?><a href="http://www.kevinsspace.ca" target="_blank" >www.kevinsspace.ca</a>&nbsp;&nbsp;&nbsp; 
		<?php _e('Plugin Site : ','testimonial-basics'); ?><a href="http://www.kevinsspace.ca/testimonial-basics-wordpress-plugin/" target="_blank" >www.kevinsspace.ca/testimonial-basics-wordpress-plugin/</a></p>

	<h3><?php _e('Introduction','testimonial-basics'); ?></h3>

	<p><?php _e('Testimonial Basics facilitates easy management of customer testimonials.','testimonial-basics'); echo ' ';
			_e('The user can set up an input form in a page or in a widget, and display all or selected testimonials in a page or a widget.','testimonial-basics'); ?></p>

	<p><?php _e('If you like the program show your appreciation, buy me a coffee, beer, or a bottle of wine (red please!).','testimonial-basics'); echo ' ';
		_e('Or just head to the website link above and put in a testimonial, or send me an e-mail, pats on the back are pretty nice too!','testimonial-basics'); ?></p>

	<p><?php _e('I plan to do updates if required, so also contact me if you find any problems, or have suggestions for improvements.','testimonial-basics'); ?></p>

	<p><?php _e('I briefly discuss the use of the plugin below. For detailed documentation, visit the plugin site.','testimonial-basics'); ?></p>

	<p><?php _e('I hope you enjoy Testimonial Basics!','testimonial-basics'); ?></p>

	<h3><?php _e("Visitor Input Form",'testimonial-basics'); ?></h3>
	<p><?php _e('You can set up a visitor input form very easily.','testimonial-basics'); echo ' ';
			_e('Simply include in your page content:','testimonial-basics'); echo ' '; ?>
			<code>[katb_input_testimonials]</code><br/>
			<?php _e('The following was used with older versions and still works with the latest version:','testimonial-basics');  echo ' '; ?>
			<code>&#60;!-- katb_input_form --&#62;</code>
			<?php echo ' '; _e('Note the space between the dash and the letters.','testimonial-basics'); echo ' ';
			_e('It will not work without the spaces.','testimonial-basics'); echo ' ';
			_e('IMPORTANT : Make sure you set up the page using the "HTML" editor and not the "Visual" editor.','testimonial-basics'); ?></p>

	<h3><?php _e('Visitor Input Widget','testimonial-basics'); ?></h3>
	<p><?php _e('You can also use a widget as a user input form.','testimonial-basics'); echo ' ';
			_e('Go to "Appearance" => "Widgets" and drag the widget to the widgetized area.','testimonial-basics'); ?></p>

	<h3><?php _e('Displaying Testimonials','testimonial-basics'); ?></h3>
	<p><?php _e('You can display testimonials in the content of a page using shortcodes or you can use widgets to display testimonials.','testimonial-basics'); ?></p>
			
	<h4><?php _e('Shortcode','testimonial-basics'); ?></h4>
	<p><?php _e('To display testimonials create a new page and enter the following shortcode :','testimonial-basics'); echo ' '; ?><br/>
	<code>[katb_testimonial group="all" by="date" number="5" id=""]</code></p>

		<ol>
			<li><?php _e('Options for','testimonial-basics'); echo ' "group" : "all" - ';_e('ignores groups','testimonial-basics');echo ',"group_name"- ';_e('display only this grouping','testimonial-basics'); ?></li>
			<li><?php _e('Options for','testimonial-basics'); echo ' "by" : "order" - ';_e('display lowest to highest','testimonial-basics');echo ',"date"- ';_e('display most recent first','testimonial-basics'); ?></li>
			<li><?php _e('Options for','testimonial-basics'); echo ' "number" : "all" - ';_e('displays all testimonials, or put in the number of testimonials you want to display','testimonial-basics'); ?></li>
			<li><?php _e('Options for','testimonial-basics');echo' "id" : "" - ';_e('leave blank for multiple testimonials','testimonial-basics');echo ', "ID" - ';_e('put in testimonial ID','testimonial-basics');echo ', "random" - ';_e('single random testimonial','testimonial-basics'); ?></li>
		</ol>

	<p><?php _e('Note that if you put id="3" for example or id="random", then the "by" and "number" attributes are ignored.','testimonial-basics');echo' ';
			_e('The id property must be blank to display (ie id="") multiple testimonials.','testimonial-basics'); ?></p>
			
	<p><?php _e('To display multiple testimonials randomly :','testimonial-basics') ?></p>
	<code>[katb_random_testimonials group="all" number="all"]</code>

		<ol>
			<li><?php _e('Options for','testimonial-basics'); echo ' "group" : "all" - ';_e('ignores groups','testimonial-basics');echo ',"group_name"- ';_e('display only this grouping','testimonial-basics'); ?></li>
			<li><?php _e('Options for','testimonial-basics'); echo ' "number" : "all" - ';_e('displays all testimonials, or put in the number of testimonials you want to display','testimonial-basics'); ?></li>
		</ol>
		
	<h4><?php _e('Single Testimonial Display Widget','testimonial-basics'); ?></h4>
	
	<p><?php _e('You can also use a widget to display a testimonial.','testimonial-basics');echo' ';
			_e('The widget will display a selected testimonial or can randomly pick a testimonial to display when a page reloads.','testimonial-basics');echo' ';
			_e('You can input a title and the ID number of the testimonial you want to display or "random" to display a random testimonial from the approved list.','testimonial-basics'); ?></p>
	
	<h4><?php _e('Multiple Testimonial Display Widget','testimonial-basics'); ?></h4>		
	
	<p><?php _e('You can also use a widget to display multiple testimonials.','testimonial-basics');echo' '; ?>
		<ol>
			<li><?php _e('Drag the widget to a widetized area and enter a title','testimonial-basics') ?></li>
			<li><?php _e('Options for','testimonial-basics'); echo ' "Group" : "all" - ';_e('ignores groups','testimonial-basics');echo ',"group_name"- ';_e('display only this grouping','testimonial-basics'); ?></li>
			<li><?php _e('Options for','testimonial-basics'); echo ' "By" : "order" - ';_e('display lowest to highest','testimonial-basics');echo ',"date"- ';_e('display most recent first','testimonial-basics');echo ',"random"- ';_e('randomly select','testimonial-basics'); ?></li>
			<li><?php _e('Options for','testimonial-basics'); echo ' "number" : "all" - ';_e('displays all testimonials, or put in the number of testimonials you want to display','testimonial-basics'); ?></li>
		</ol>
	</p><br/>
		
<?php }

/** ----------------- katb_testimonial_basics_options_page -------------------------
 * Called by add_submenu_page. Sets up the Testimonial basics Option Page
 * ---------------------------------------------------------------------------------- */
function katb_testimonial_basics_options_page (){ ?>
	<?php screen_icon( 'plugins' ); ?>
	<h2>Testimonial Basics</h2>
	<div class="katb_paypal"><?php _e('Show your appreciation!','testimonial-basics') ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
		">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynow_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
	<p><?php _e('Author Site : ','testimonial-basics'); ?><a href="http://www.kevinsspace.ca" target="_blank" >www.kevinsspace.ca</a>&nbsp;&nbsp;&nbsp; 
		<?php _e('Plugin Site : ','testimonial-basics'); ?><a href="http://www.kevinsspace.ca/testimonial-basics-wordpress-plugin/" target="_blank" >www.kevinsspace.ca/testimonial-basics-wordpress-plugin/</a></p>
	<p><?php _e('Click the Help button for instructions or see the detailed documentation at the plugin site.','testimonial-basics'); ?></p>
	<form class="katb_options" action="options.php" method="post">
		<div id="katb_picker"></div>
		<?php 
			settings_fields('katb_testimonial_basics_options'); 
			do_settings_sections('katb_testimonial_basics_admin_options');
		?>
		<?php submit_button( __( 'Save Options', 'testimonial-basics' ), 'primary', 'katb_save_options', false ); ?>
		<?php submit_button( __( 'Reset Options', 'testimonial-basics' ), 'secondary', 'katb_reset_options', false ); ?>
	</form>
<?php }


function katb_testimonial_basics_admin_init (){//Register and define settings
	/**
	 * Register Plugin Settings
	 * 
	 * Register katb_testimonial_basics_options array to hold
	 * all Theme options.
	 * 
	 * @link	http://codex.wordpress.org/Function_Reference/register_setting	Codex Reference: register_setting()
	 * 
	 * @param	string		$option_group		Unique Settings API identifier; passed to settings_fields() call
	 * @param	string		$option_name		Name of the wp_options database table entry
	 * @param	callback	$sanitize_callback	Name of the callback function in which user input data are sanitized
	 * 
	 */
	register_setting('katb_testimonial_basics_options','katb_testimonial_basics_options','katb_validate_options');
	/**
	 * Call add_settings_section() for each Settings 
	 * 
	 * 
	 * @link	http://codex.wordpress.org/Function_Reference/add_settings_section	Codex Reference: add_settings_section()
	 * 
	 * @param	string		$sectionid	Unique Settings API identifier; passed to add_settings_field() call
	 * @param	string		$title		Title of the Settings page section
	 * @param	callback	$callback	Name of the callback function in which section text is output **not used here
	 * @param	string		$pageid		Name of the Settings page to which to add the section; passed to do_settings_sections()
	 */
	add_settings_section('katb_testimonial_basics_input','General Setup and Input Form Options','katb_input_section_callback','katb_testimonial_basics_admin_options');
	add_settings_section('katb_testimonial_basics_content','Testimonial Display Options','katb_content_section_callback','katb_testimonial_basics_admin_options');
	add_settings_section('katb_testimonial_basics_widget','Widget Testimonial Display Options','katb_widget_section_callback','katb_testimonial_basics_admin_options');
	/**
	 * Call add_settings_field() for each Setting Field
	 * 
	 * Loop through each Theme option, and add a new 
	 * setting field to the Theme Settings page for each 
	 * setting.
	 * 
	 * @link	http://codex.wordpress.org/Function_Reference/add_settings_field	Codex Reference: add_settings_field()
	 * 
	 * @param	string		$settingid	Unique Settings API identifier; passed to the callback function
	 * @param	string		$title		Title of the setting field
	 * @param	callback	$callback	Name of the callback function in which setting field markup is output
	 * @param	string		$pageid		Name of the Settings page to which to add the setting field; passed from add_settings_section()
	 * @param	string		$sectionid	ID of the Settings page section to which to add the setting field; passed from add_settings_section()
	 * @param	array		$args		Array of arguments to pass to the callback function
	 */
	$katb_option_parameters = katb_get_option_parameters();
	foreach ( $katb_option_parameters as $option ) {
		$optionname = $option['name'];
		$optiontitle = $option['title'];
		$optionsection = $option['section'];
		$optiontype = $option['type'];
		add_settings_field(
			// $settingid
			'katb_setting_' . $optionname,
			// $title
			$optiontitle,
			// $callback
			'katb_setting_callback',
			// $pageid
			'katb_testimonial_basics_admin_options',
			// $sectionid
			'katb_testimonial_basics_' . $optionsection,
			// $args
			$option
		);
	}
}
add_action('admin_init','katb_testimonial_basics_admin_init');

/** ----------------- Setting Section Callbacks ----------------------------------
 * These are empty functions because I did not want to add any html under the 
 * section titles but they were generating php errors
 *  * ------------------------------------------------------------------------------- */
 
 function katb_input_section_callback () {}
 function katb_content_section_callback () {}
 function katb_widget_section_callback () {}


/** ---------------- add_settings_field callback ---------------------------------
 * This function takes the $option array and uses it and other data to display the
 * current value of the settings field, a default is displayed if none is set in
 * the options table. The fields are displayed apropriately to the type as taken 
 * from katb_get_option_parameters()
 * -------------------------------------------------------------------------------- */
function katb_setting_callback( $option ) { //Callback for get_settings_field()
	$katb_options = katb_get_options();
	$katb_option_parameters = katb_get_option_parameters();
	$optionname = $option['name'];
	$optiontitle = $option['title'];
	$optiondescription = $option['description'];
	$fieldtype = $option['type'];
	$fieldname = 'katb_testimonial_basics_options[' . $optionname . ']';
	$katb_input_class = $option['class'];
	
	// Output checkbox form field markup checked( $katb_options[$optionname] );
	if ( 'checkbox' == $fieldtype ) {
		?>
		<input class="katb_options <?php echo $katb_input_class ?>"  type="checkbox" name="<?php echo $fieldname; ?>" <?php if( $katb_options[$optionname] == true ) echo 'checked="checked"'; ?> />
		<?php
	}
	// Output select form field markup
	else if ( 'select' == $fieldtype ) {
		$valid_options = array();
		$valid_options = $option['valid_options'];
		?>
		<select class="katb_options <?php echo $katb_input_class ?>" name="<?php echo $fieldname; ?>">
		<?php 
		foreach ( $valid_options as $valid_option ) {
			?>
			<option <?php selected( $valid_option == $katb_options[$optionname] ); ?> value="<?php echo $valid_option; ?>"><?php echo $valid_option; ?></option>
			<?php
		}
		?>
		</select>
		<?php 
	} 
	// Output text input form field markup
	else if ( 'text' == $fieldtype ) { ?>
		<input id="<?php echo $optionname; ?>" class="katb_options <?php echo $katb_input_class ?>" type="text" name="<?php echo $fieldname; ?>" value="<?php echo wp_filter_nohtml_kses( $katb_options[$optionname] ); ?>" />
	<?php } ?>
 
	<span class="description"><?php echo $optiondescription; ?></span>
<?php }
/** ---------------- katb_testimonial_basics_edit_page -----------------------------
 * called from the add_submenu_page
 * This is the edit testimonials section that displays all the testimonials and 
 * allows the user to add, edit,delete, and approve testimonials
 */
function katb_testimonial_basics_edit_page(){
	global $wpdb,$tablename;
	$tablename = $wpdb->prefix.'testimonial_basics';
	$katb_allowed_html = katb_allowed_html();
	//submit testimonial
	if ( isset($_POST['submitted']) && check_admin_referer('katb_nonce_3','katb_admin_form_nonce')) {
		//Validate Input
		$error = "";
		$katb_id = $_POST['tb_id'];
		// Order must be an integer
		$katb_order = trim($_POST['tb_order']);
		if ($katb_order != "") {
			if(is_numeric($katb_order) == FALSE) {
				$katb_order = "";
				$error .= '*'.__('Order must be a integer','testimonial-basics').'*';
			}
		}
		//Approved is either checked (1) or not checked (0)
		if (!isset($_POST['tb_approved'])){
			$katb_approved = 0;
		} else {
			$katb_approved = 1;
		}
		$katb_group = sanitize_text_field(trim($_POST['tb_group']));
		$katb_author = sanitize_text_field(trim($_POST['tb_author']));
		if ($katb_author == "") {
			$error .= '*'.__('Author is required','testimonial-basics').'*';
		}
		//website validation
		$katb_website = trim($_POST['tb_website']);
		if ( $katb_website != '' ) $katb_website = esc_url($_POST['tb_website']);
		if ( $katb_website == 'http://' ) $katb_website = '';
		//location validation
		$katb_location = sanitize_text_field(trim($_POST['tb_location']));
		$katb_email = sanitize_email(trim($_POST['tb_email']));
		if(!is_email($katb_email) && $katb_email != '') {
			$error .= '*'.__('Valid email is required','testimonial-basics').'*';
		}
		//Date Validation
		$katb_date = trim($_POST['tb_date']);
		if ($katb_date != "") {
			$year = intval(substr($katb_date,0,4));
			$month = intval(substr($katb_date,5,2));
			$day = intval(substr($katb_date,8,2));
			if( !checkdate( $month, $day, $year ) ){
				$error .= '*'.__('Date must be','testimonial-basics').' YYYY-MM-DD*';
			}
		}
		$katb_time = trim($_POST['tb_time']);
		//Time Validadtion
		if($katb_time != ""){
			$hour = intval(substr($katb_time,0,2));
			$min = intval(substr($katb_time,3,2));
			$sec = intval(substr($katb_time,6,2));
			if ( $hour < 0 || $hour > 23 || $min < 0 || $min > 59 || $sec < 0 || $sec > 59 || substr($katb_time,2,1) != ":" || substr($katb_time,5,1) != ":" ){
				$error .= '*'.__('Time must be','testimonial-basics').' HH:MM:SS*';
			}
		}
		if($katb_date == "" && $katb_time == "") {
			$katb_datetime = current_time('mysql');
		}elseif($katb_date != "" && $katb_time == ""){
			$katb_datetime = $katb_date.' 00:00:00';
		}elseif($katb_date == "" && $katb_time != ""){
			$katb_datetime = current_time('mysql');
		}else{
			$katb_datetime = $katb_date.' '.$katb_time;
		}
		$katb_date = substr($katb_datetime,0,10);
		$katb_time = substr($katb_datetime,11,8);
		//Sanitize testimonial - same html allowed as allowed in posts
		$katb_testimonial = wp_kses($_POST['tb_testimonial'],$katb_allowed_html);
		//$katb_testimonial = wp_kses_post( $_POST['tb_testimonial'] );
		if ($katb_testimonial == "" ) {
			$error .= '*'.__('Testimonial is required','testimonial-basics').'*';
		}
		//Validation complete
		if($error == "") {
			//OK $error is empty so let's update the database
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
			if($katb_id == ""){
				$wpdb->insert($tablename,$values,$formats_values);
				$katb_id = $wpdb->insert_id;

			}else{
				$where = array('tb_id' => $katb_id);
				$wpdb->update($tablename,$values,$where,$formats_values);
			}
			echo '<div id="message" class="updated">'.__('Testimonial added successfuly.','testimonial-basics').'</div>';
		} else {
			echo '<div id="message" class="error">'.__('Error,testimonial was not added','testimonial-basics').': '.$error.'</div>';
		}
	} else {
		$katb_id = "";
		$katb_order = "";
		$katb_approved = "";
		$katb_group = "";
		$katb_date = "";
		$katb_time = "";
		$katb_author = "";
		$katb_email = "";
		$katb_website = "";
		$katb_location = "";
		$katb_testimonial = "";
	}
	/* ---------- Reset button is clicked ---------------- */
	if(isset($_POST['reset'])) {
		$katb_id = "";
		$katb_order = "";
		$katb_approved = "";
		$katb_group = "";
		$katb_date = "";
		$katb_time = "";
		$katb_author = "";
		$katb_email = "";
		$katb_website = "";
		$katb_location = "";
		$katb_testimonial = "";
	}
	/* ---------------- Delete Button is clicked ------------- */
	if(isset($_POST['delete'])) {
		$katb_id = $_POST['tb_id'];
		if($katb_id == ""){
			echo '<div id="message" class="error">'.__('Error, no ID','testimonial-basics').'</div>';
		}else{}
		$wpdb->query(" DELETE FROM `$tablename` WHERE `tb_id`=$katb_id " );
		$katb_id = "";
		$katb_order = "";
		$katb_approved = "";
		$katb_group = "";
		$katb_date = "";
		$katb_time = "";
		$katb_author = "";
		$katb_email = "";
		$katb_website = "";
		$katb_location = "";
		$katb_testimonial = "";
		echo '<div id="message" class="updated">'.__('Testimonial was deleted.','testimonial-basics').'</div>';
	}
	if(isset($_POST['edit'])){
		$katb_id = $_POST['edit'];
		$edit_data = $wpdb->get_row("SELECT * FROM `$tablename` WHERE `tb_id` = $katb_id ",ARRAY_A );
		$katb_order = $edit_data['tb_order'];
		$katb_group = $edit_data['tb_group'];
		$katb_author = $edit_data['tb_name'];
		$katb_email = $edit_data['tb_email'];
		$katb_website = $edit_data['tb_url'];
		$katb_location = $edit_data['tb_location'];
		$katb_testimonial = $edit_data['tb_testimonial'];
		$katb_approved = $edit_data['tb_approved'];
		$katb_date = substr($edit_data['tb_date'],0,10);
		$katb_time = substr($edit_data['tb_date'],11,8);
	}
?>
	<div class="wrap">
		<?php screen_icon( 'plugins' ); ?>
		<h2>Testimonial Basics</h2>
		<div class="katb_paypal"><?php _e('Show your appreciation!','testimonial-basics') ?>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
			">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynow_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		</div>
		<p><?php _e('Author Site : ','testimonial-basics'); ?><a href="http://www.kevinsspace.ca" target="_blank" >www.kevinsspace.ca</a>&nbsp;&nbsp;&nbsp; 
			<?php _e('Plugin Site : ','testimonial-basics'); ?><a href="http://www.kevinsspace.ca/testimonial-basics-wordpress-plugin/" target="_blank" >www.kevinsspace.ca/testimonial-basics-wordpress-plugin/</a></p>
		<p><?php _e('Click the Help button for instructions or see the testimonial_basics_docs.html file included in the plugin docs folder.','testimonial-basics'); ?></p>
		<h3><?php _e('Enter or update a testimonial (*Required)','testimonial-basics'); ?></h3>
		<form class="katb_admin_form" method="POST" action="#">
			<?php wp_nonce_field("katb_nonce_3","katb_admin_form_nonce"); ?>
			<label class="katb_admin_id"><?php _e('ID : ','testimonial-basics'); ?></label><input size="5" maxlength="5" readonly="readonly" name="tb_id" value="<?php echo $katb_id; ?>" />
			<label><?php _e('Order : ','testimonial-basics'); ?></label><input size="5" maxlength="5" name="tb_order" value="<?php echo $katb_order ?>" />
			<label><?php _e('Approved : ','testimonial-basics'); ?></label><input type="checkbox" name="tb_approved" value="1"<?php if($katb_approved == 1) {echo ' checked="checked"';} ?> />
			<label><?php _e('Group : ','testimonial-basics'); ?></label><input  maxlength="20" size="25" name="tb_group" value="<?php echo stripcslashes($katb_group); ?>" />
			<br/><br/>
			<label class="katb_admin_date"><?php _e('Date (YYYY-MM-DD): ','testimonial-basics'); ?></label><input  maxlength="12" size="10" name="tb_date" value="<?php echo $katb_date; ?>" />
			<label class="katb_admin_time"><?php _e('Time (HH:MM:SS): ','testimonial-basics'); ?></label><input  maxlength="10" size="10" name="tb_time" value="<?php echo $katb_time; ?>" />
			<br/><br/>
			<label class="katb_admin_author"><?php _e('Author *: ','testimonial-basics'); ?></label><input  maxlength="100" size="40" name="tb_author" value="<?php echo stripcslashes($katb_author); ?>" />
			<label class="katb_admin_email"><?php _e('Email : ','testimonial-basics'); ?></label><input  maxlength="100" size="40" name="tb_email" value="<?php echo stripcslashes($katb_email); ?>" />
			<br/><br/>
			<label class="katb_admin_url"><?php _e('Website : ','testimonial-basics'); ?></label><input  maxlength="100" size="40" name="tb_website" value="<?php echo $katb_website; ?>" />
			<label class="katb_admin_location"><?php _e('Location : ','testimonial-basics'); ?></label><input  maxlength="100" size="40" name="tb_location" value="<?php echo stripcslashes($katb_location); ?>" />
			<br/><br/>
			<label class="katb_admin_test"><?php _e('Testimonial *: ','testimonial-basics'); ?></label>
			<textarea cols="101" rows="5" name="tb_testimonial" ><?php echo stripcslashes($katb_testimonial); ?></textarea>
			<span class="html_allowed">HTML Allowed: <code>&#60;p&#62;&#60;/p&#62;&#60;br/&#62;</code></span>
			<br/><br/><br/>
			<input type="submit" name="submitted" value="<?php _e('Save Testimonial','testimonial-basics') ?>" class="button-primary" />
			<input type="submit" name="reset" value="<?php _e('Reset','testimonial-basics') ?>" class="button-secondary" />
			<input type="submit" name="delete" value="<?php _e('Delete','testimonial-basics') ?>" class="button-highlighted" />
		</form>
		<h3>Testimonials</h3>
		<?php
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_id` ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		?>
		<table class="widefat">
			<thead>
				<tr>
					<th><?php _e('ID','testimonial-basics'); ?></th>
					<th><?php _e('Group','testimonial-basics'); ?></th>
					<th><?php _e('Order','testimonial-basics'); ?></th>
					<th><?php _e('Appr','testimonial-basics'); ?></th>
					<th><?php _e('Timestamp','testimonial-basics'); ?></th>
					<th><?php _e('Author','testimonial-basics'); ?></th>
					<th><?php _e('E-mail','testimonial-basics'); ?></th>
					<th><?php _e('Location','testimonial-basics'); ?></th>
					<th><?php _e('Website','testimonial-basics'); ?></th>
					<th><?php _e('Testimonial','testimonial-basics'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php _e('ID','testimonial-basics'); ?></th>
					<th><?php _e('Group','testimonial-basics'); ?></th>
					<th><?php _e('Order','testimonial-basics'); ?></th>
					<th><?php _e('Appr','testimonial-basics'); ?></th>
					<th><?php _e('Timestamp','testimonial-basics'); ?></th>
					<th><?php _e('Author','testimonial-basics'); ?></th>
					<th><?php _e('E-mail','testimonial-basics'); ?></th>
					<th><?php _e('Location','testimonial-basics'); ?></th>
					<th><?php _e('Website','testimonial-basics'); ?></th>
					<th><?php _e('Testimonial','testimonial-basics'); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php
					for ( $i = 0 ; $i < $katb_tnumber; $i++ ) {
						echo '<tr>';
						echo '<td>';echo '<form method="POST" action="#"><input type="submit" name="edit" value="'.$katb_tdata[$i]['tb_id'].'" class="button-secondary" /></form>';echo'</td>';
						echo '<td>';echo stripcslashes($katb_tdata[$i]['tb_group']);echo'</td>';
						echo '<td>';if( $katb_tdata[$i]['tb_order'] == 0 ){ echo ""; }else{ echo $katb_tdata[$i]['tb_order']; };echo'</td>';
						echo '<td>';if($katb_tdata[$i]['tb_approved']==1){echo "Y";}else{echo "N";};echo'</td>';
						echo '<td>';echo $katb_tdata[$i]['tb_date'];echo'</td>';
						echo '<td>';echo stripcslashes($katb_tdata[$i]['tb_name']);echo'</td>';
						echo '<td>';echo $katb_tdata[$i]['tb_email'];echo'</td>';
						echo '<td>';echo stripcslashes($katb_tdata[$i]['tb_location']);echo'</td>';
						echo '<td>';echo $katb_tdata[$i]['tb_url'];echo'</td>';
						echo '<td>';echo stripcslashes($katb_tdata[$i]['tb_testimonial']);echo'</td>';
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
	</div>
<?php } 

/** ---------------- katb_testimonial_basics_admin_page_help callback ---------------------------------
 * This function takes the $option arra and uses it and other data to display the
 * current value of the settings field, a default is displayed if none is set in
 * the options table. The fields are displayed apropriately to the type as taken 
 * from katb_get_option_parameters()
 * -------------------------------------------------------------------------------- */
function katb_testimonial_basics_admin_page_help( $contextual_help, $screen_id, $screen ){
	global $katb_testimonial_basics_admin_edit_help,$katb_testimonial_basics_admin_options_help;
	$contextual_help = '';	
	if ( $screen_id == $katb_testimonial_basics_admin_edit_help ) {
		$contextual_help .= '<h2>Testimonial Basics - '.__('Adding And Editing Testimonials','testimonial-basics').'</h2>';
		$contextual_help .= '<ul><li>'.__('To add a testimonial simply enter the data and click the "Save Testimonial" button','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('To edit a testimonial click the ID button for the testimonial you want to edit, make your changes and "Save Testimonial"','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Note that new testimonials that come in must be approved by clicking the approved box or they will not be displayed.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Enter an order number, and you can display testimonials lowest order number first.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Enter a group name up to 20 characters to allow you to display only the grouped testimonials.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('If you are using gravatars but do not want to display a particular author gravatar, delete the author email.','testimonial-basics').'</li></ul>';
		$contextual_help .= '<h4>Testimonial Basics - '.__('Detailed User Documentation','testimonial-basics').'</h4>';
		$contextual_help .= '<ul><li>'.__('Detailed user documentation is available at the plugin site.','testimonial-basics').'</li></ul>';
	} elseif ( $screen_id == $katb_testimonial_basics_admin_options_help ) {
		$contextual_help .= '<h2>Testimonial Basics - '.__('Options Help','testimonial-basics').'</h2>';
		$contextual_help .= '<h4>'.__('General Setup and Input Form Options','testimonial-basics').'</h4>';
		$contextual_help .= '<ul><li>'.__('Select the user role for permission to edit testimonials','testimonial-basics').'</li>';
		$contextual_help .= '<ul><li>'.__('An email is sent to this address (or admin email if left blank) to notify that a testimonial has been submitted','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Include a captcha in the input by selecting the "Use captcha on input forms" checkbox. ','testimonial-basics');
		$contextual_help .= __('If for any reason the Captcha is not working, disable it here.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Input Form Title: You can choose not to display one, or you can change the title.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Email note: You can choose not to display one, or you can change it to any text you want.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Keep the text you enter to a reasonable length or it may look funny.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Optionally include an html allowed strip on input forms.','testimonial-basics').'</li></ul>';
		$contextual_help .= '<h4>'.__('Testimonial Display Options','testimonial-basics').'</h4>';
		$contextual_help .= '<ul><li>'.__('You can use excerpts with the specified character length to display your testimonials.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Website, Date and Location are optional for display in the testimonial, just click them if you want to show them.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Gravatars: If selected, images associated with the author e-mail will be shown if one exists.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Italic: If selected, the content, not the author strip, will be displayed in italic style.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Formatted Display: If you want to use a basic display that will use your theme\'s fonts and colors, leave this box unchecked.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Custom Display: If the Formatted Display box is checked a 3D style is applied to the testimonials, which can be customized.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Custom Options: The custom options below the Formatted Display Box only apply if the box is checked.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Font: You can choose a font from the dropdown list.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Colors: Enter any hexdec color number preceded by a # mark or use the Color Wheel.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Color Wheel: Select the color input cell you want to change. ','testimonial-basics');
		$contextual_help .= __('Drag the dot on the outside Color Circle to select the basic color.','testimonial-basics');
		$contextual_help .= __('Drag the dot on the inside Color Box to select the saturation level.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Save Options: Make sure you save your settings before leaving the page.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('Reset Options: If you click this all options are reset to defaults so be CAREFUL!','testimonial-basics').'</li></ul>';
		$contextual_help .= '<h4>'.__('Widget Testimonial Display Options','testimonial-basics').'</h4>';
		$contextual_help .= '<ul><li>'.__('These options are essentially the same as the previous section.','testimonial-basics');
		$contextual_help .= __('The widget is much smaller than the content display, so you may want to have different display options.','testimonial-basics').'</li>';
		$contextual_help .= '<li>'.__('This section is also used to customize the display for the the multiple display widget','testimonial-basics').'</li></ul>';
		$contextual_help .= '<h4>Testimonial Basics - '.__('Detailed User Documentation','testimonial-basics').'</h4>';
		$contextual_help .= '<ul><li>'.__('Detailed user documentation is available at the plugin site.','testimonial-basics').'</li></ul>';
	}
	return $contextual_help;
}
add_filter ('contextual_help','katb_testimonial_basics_admin_page_help',10,3);

/**
 * Helper function for creating admin messages
 * src: http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area
 *
 * @param (string) $message The message to echo
 * @param (string) $msgclass The message class
 * @return echoes the message
 */	
function katb_show_msg($message, $msgclass = 'info') {
	echo "<div id='message' class='$msgclass'>$message</div>";
}

/**
 * Callback function for displaying admin messages
 *
* @return calls katb_show_msg()
*/
function katb_admin_msgs() {
	// check for our settings page - need this in conditional further down
	if(isset($_GET['page'])){
		$katb_settings_pg = strpos($_GET['page'], 'katb_testimonial_basics_admin_options');
	} else {
		$katb_settings_pg = FALSE;
	}
	// collect setting errors/notices: //http://codex.wordpress.org/Function_Reference/get_settings_errors
	$set_errors = get_settings_errors(); 
	
	//display admin message only for the admin to see, only on our settings page and only when setting errors/notices are returned!	
	if(current_user_can ('manage_options') && $katb_settings_pg !== FALSE && !empty($set_errors)){

		// have our settings succesfully been updated? 
		if($set_errors[0]['code'] == 'settings_updated' && isset($_GET['settings-updated'])){
			katb_show_msg("<p>" . $set_errors[0]['message'] . "</p>", 'updated');
		
		// have errors been found?
		}else{
			// there maybe more than one so run a foreach loop.
			foreach($set_errors as $set_error){
				// set the title attribute to match the error "setting title" - need this in js file
				katb_show_msg("<p class='setting-error-message' title='" . $set_error['setting'] . "'>" . $set_error['message'] . "</p>", 'error');
			}
		}
	}
}
add_action('admin_notices', 'katb_admin_msgs');

/**
 * Theme register_setting() sanitize callback
 * 
 * Validate and whitelist user-input data before updating Theme 
 * Options in the database. Only whitelisted options are passed
 * back to the database, and user-input data for all whitelisted
 * options are sanitized.
 * 
 * @link	http://codex.wordpress.org/Data_Validation	Codex Reference: Data Validation
 * 
 * @param	array	$input	Raw user-input data submitted via the Theme Settings page
 * @return	array	$input	Sanitized user-input data passed to the database
 */
function katb_validate_options( $input ) {

	// This is the "whitelist": current settings
	$valid_input = katb_get_options();
	// Get the array of option parameters
	$option_parameters = katb_get_option_parameters();
	// Get the array of option defaults
	$option_defaults = katb_get_option_defaults();
	//array for possible errors
	$katb_input_error = array();
	
	// Determine what type of submit was input
	if ( isset ( $_POST['katb_reset_options'] ) ) {
		$submittype = 'reset';
	} elseif ( isset ( $_POST['katb_save_options'])) {
		$submittype = 'submit';
	}
	

	foreach ( $option_parameters as $option_parameter ) {
		$setting = $option_parameter['name'];
		// If no option is selected, set the default
		$valid_input[$setting] = ( ! isset( $input[$setting] ) ? $option_defaults[$setting] : $input[$setting] );
		
		// If submit, validate/sanitize $input
		if ( 'submit' == $submittype ) {
			// Get the setting details from the defaults array
			$optiondetails = $option_parameters[$setting];
			// Get the array of valid options, if applicable
			$valid_options = ( isset( $optiondetails['valid_options'] ) ? $optiondetails['valid_options'] : false );
			
			// Validate checkbox fields
			if ( 'checkbox' == $optiondetails['type'] ) {
				// If input value is set and is true, return true; otherwise return false
				$valid_input[$setting] = ( ( isset( $input[$setting] ) && true == $input[$setting] ) ? true : false );
			}
			// Validate radio button fields
			else if ( 'radio' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[$setting] = ( array_key_exists( $input[$setting], $valid_options ) ? $input[$setting] : $valid_input[$setting] );
			}
			// Validate select fields
			else if ( 'select' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[$setting] = ( array_key_exists( $input[$setting], $valid_options ) ? $input[$setting] : $valid_input[$setting] );
			}
			// Validate text input and textarea fields
			else if ( ( 'text' == $optiondetails['type'] || 'textarea' == $optiondetails['type'] ) ) {
				// Validate no-HTML content
				if ( 'nohtml' == $optiondetails['class'] ) {
					// Pass input data through the wp_filter_nohtml_kses filter
					$valid_input[$setting] = wp_filter_nohtml_kses( $input[$setting] );
				}
				// Validate HTML content
				else if ( 'html' == $optiondetails['class'] ) {
					// Pass input data through the wp_filter_kses filter
					$valid_input[$setting] = wp_filter_kses( $input[$setting] );
				}
				else if ( 'url' == $optiondetails['class'] || 'img' == $optiondetails['class'] ) {
					//eliminate invalid and dangerous characters
					$valid_input[$setting] = esc_url($valid_input[$setting]);
				}
				else if ( 'email' == $optiondetails['class'] ) {
					if ( $valid_input[$setting] !== '' ){
						$valid_input[$setting] = sanitize_email( $valid_input[$setting] );
						If ( $valid_input[$setting] == '' ){
							add_settings_error(
								$setting, // setting title
								'testimonial_basics_email_error', // error ID
								'Please enter a valid e-mail - blank returned', // error message
								'error' // type of message
							);
						}
					}
					if ( $valid_input[$setting] !== '' && ! is_email($valid_input[$setting]) ) {
						$valid_input[$setting] = '';
						add_settings_error(
							$setting, // setting title
							'testimonial_basics_error', // error ID
							'Please enter a valid e-mail - blank returned', // error message
							'error' // type of message
						);						
					}
				}
				else if ( 'hexcolor' == $optiondetails['class'] ) {
					$valid_input[$setting] = trim($valid_input[$setting]); // trim whitespace
					if ($valid_input[$setting] == "") $valid_input[$setting] = $option_defaults[$setting];
					if(substr($valid_input[$setting],0,1) !== '#'){$valid_input[$setting] = '#' . $valid_input[$setting];}
					if(! preg_match('/^#[a-f0-9]{6}$/i', $valid_input[$setting])) {//hex color is valid
						$valid_input[$setting] = $option_defaults[$setting];
						add_settings_error(
							$setting, // setting title
							'testimonial_basics_hex_color_error', // error ID
							'Please enter a valid Hex Color Number-default returned.', // error message
							'error' // type of message
						);
					} 
				}
				else {
					// Catch all
					//Pass input data through the wp_filter_kses filter
					$valid_input[$setting] = wp_filter_kses( $input[$setting] );
				}
			}
		} 
		// If reset, reset defaults
		elseif ( 'reset' == $submittype ) {
			// Set $setting to the default value
			$valid_input[$setting] = $option_defaults[$setting];
		}
	}
	return $valid_input;		
}