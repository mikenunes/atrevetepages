<?php
/**
 * This file contains the shortcodes for displaying the testimonial in a content area.
 *
 *
 * @package		Testimonial Basics WordPress Plugin
 * @copyright	Copyright (c) 2012, Kevin Archibald
 * @license		http://www.gnu.org/licenses/quick-guide-gplv3.html  GNU Public License
 * @author		Kevin Archibald <www.kevinsspace.ca/contact/>
 */

/* ------------------ display testimonials shortcode ---------------------------------
 * useage : [katb_testimonial group="all" by="date" number="all" id=""] 
 * group : "all" or "group" where group is the identifier in the testimonial
 * by : "date" or "order"
 * number : "all" or input the number you want to display
 * id : "" or "random" or "id" where id is the id number of the testimonial
 * ------------------------------------------------------------------------- */

function katb_list_testimonials ( $atts) {
	//set up database table name for later use
	global $wpdb,$tablename;
	$tablename = $wpdb->prefix.'testimonial_basics';
	//get user options
	$katb_options = katb_get_options();
	//Initialize Strings
	$katb_html = '';
	$katb_error = "";
	//get shortcode variables
	extract(shortcode_atts(array(
		'group' => 'all',
	    'by' => 'date',
	    'number' => 'all',
	    'id' => '',
    ), $atts));
	//Sanitize data
	$group = sanitize_text_field($group);
	$by = sanitize_text_field($by);
	$number = sanitize_text_field($number);
	$id = sanitize_text_field($id);
	//Validate $by
	if ($by != 'date' && $by != 'order') $by = 'date';
	//OK let's start by getting the testimonial data from the database
	if ( $number == 'all' && $id == '' && $by == 'date' ) {
		if ($group == 'all') {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_date` DESC ",ARRAY_A);
		} else {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_date` DESC ",ARRAY_A);
		}
		$katb_tnumber = $wpdb->num_rows;
		if ( $katb_tnumber == 0 ) $katb_error = __('There are no approved testimonials to display!','testimonial_basics');
	} elseif ( $number == 'all' && $id == '' && $by == 'order' ) {
		if ($group == 'all') {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_order` = '0', `tb_order` ASC,`tb_date` DESC ",ARRAY_A);
		} else {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC ",ARRAY_A);
		}
		$katb_tnumber = $wpdb->num_rows;
		if ( $katb_tnumber == 0 ) $katb_error = __('There are no approved testimonials to display!','testimonial_basics');
	} elseif ( intval($number) > 0 && $id == '' && $by == 'date' ) {
		$number = intval( $number );
		if ($group == 'all') {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		} else {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		}
		$katb_tnumber = $wpdb->num_rows;
		if ( $katb_tnumber == 0 ) $katb_error = __('There are no approved testimonials to display!','testimonial_basics');
	} elseif ( intval($number) > 0 && $id == '' && $by == 'order' ) {
		$number = intval( $number );
		if ($group == 'all') {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		} else {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC LIMIT 0,$number ",ARRAY_A);
		}
		$katb_tnumber = $wpdb->num_rows;
		if ( $katb_tnumber == 0 ) $katb_error = __('There are no approved testimonials to display!','testimonial_basics');
	} elseif ($id != '' ) {
		if ( $id == 'random' ) {
			$katb_tdata2 = $wpdb->get_results( " SELECT `tb_id` FROM `$tablename` WHERE `tb_approved` = '1' ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;				
			if ( $katb_tnumber == 0 ) {
				$katb_error = __('There are no approved testimonials to display!','testimonial_basics');
			} else {	
				$rand = rand(0, $katb_tnumber-1);
				$random_id = $katb_tdata2[$rand]['tb_id'];
				$katb_tdata = $wpdb->get_results("SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_id` = $random_id ",ARRAY_A );
				$katb_tnumber = $wpdb->num_rows;
			}
		} elseif (intval($id != '')) {
			$id = intval($id);
			$katb_tdata = $wpdb->get_results("SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_id` = $id ",ARRAY_A );
			$katb_tnumber = $wpdb->num_rows;
			if ( $katb_tnumber == 0 ) $katb_error = __('Testimonial not found or approved. Please check your shortcode.','testimonial_basics');
		} else {
			//$id is an unknown
			$katb_error = __('Testimonial not found or approved. Please check your shortcode.','testimonial_basics');
		}
		
	} else {
		// something did not work
		$katb_error =  __('Testimonial not found. Please check your shortcode.','testimonial_basics');
	}
	// Database queried
	//Lets prepare the return string
	if( $katb_error != '') {
		$katb_html = '<div class="katb_error">'.$katb_error.'</div>';
	} else {
		if ( $katb_options['katb_use_formatted_display'] == 1 ) {	
			$katb_html .= '<div class="katb_test_wrap">';
		} else {
			$katb_html .= '<div class="katb_test_wrap_basic">';
		}
		for ( $i = 0 ; $i < $katb_tnumber; $i++ ) {
			//set up hidden popup if excerpt is used	
			if ( $katb_options['katb_use_excerpts'] == 1 ) {
				$katb_html .= '<div class="katb_topopup" id="katb_content_test_'.$katb_tdata[$i]['tb_id'].'">';
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
				$katb_html .= '<div class="katb_excerpt_popup_bg" id="katb_content_test_'.$katb_tdata[$i]['tb_id'].'_bg"></div>';
			}			
			if ( $katb_options['katb_use_formatted_display'] == 1 ) {
				$katb_html .= '<div class="katb_test_box">';
				if ( $katb_options['katb_use_gravatars'] == 1 ){
					$has_valid_avatar = katb_validate_gravatar($katb_tdata[$i]['tb_email']);
					If ( $has_valid_avatar == 1 ) {
						$katb_html .= '<span class="katb_avatar">'. get_avatar( $katb_tdata[$i]['tb_email'], $size = '60' ).'</span>';
					}
				}
				if ( $katb_options['katb_use_excerpts'] == 1 ) {
					$text = stripcslashes($katb_tdata[$i]['tb_testimonial']);
					$length = $katb_options['katb_excerpt_length'];
					$classID = 'katb_content_test_'.$katb_tdata[$i]['tb_id'];
					$text = katb_testimonial_excerpt_filter($length,$text,$classID);
					$katb_html .= '<div class="katb_test_text" >'.$text.'</div>';
				} else {
					$katb_html .= '<div class="katb_test_text" >'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div>';
				}		
				$katb_html .= '<span class="katb_test_meta"><br/>'.stripcslashes($katb_tdata[$i]['tb_name']);
			} else {
				$katb_html .= '<div class="katb_test_box_basic">';
				if ( $katb_options['katb_use_gravatars'] == 1 ) {
					$has_valid_avatar = katb_validate_gravatar($katb_tdata[$i]['tb_email']);
					If ( $has_valid_avatar == 1 ) {
						$katb_html .= '<span class="katb_avatar">'.get_avatar( $katb_tdata[$i]['tb_email'], $size = '60' ).'</span>';
					}
				}
				if ( $katb_options['katb_use_excerpts'] == 1 ) {
					$text = stripcslashes($katb_tdata[$i]['tb_testimonial']);
					$length = $katb_options['katb_excerpt_length'];
					$classID = 'katb_content_test_'.$katb_tdata[$i]['tb_id'];
					$text = katb_testimonial_excerpt_filter($length,$text,$classID);
					$katb_html .= '<div class="katb_test_tex_basic" >'.$text.'</div>';
				} else {
					$katb_html .= '<div class="katb_test_text_basic" >'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div>';
				}		
				$katb_html .= '<span class="katb_test_meta_basic"><strong>'.stripcslashes($katb_tdata[$i]['tb_name']).'</strong>';
			}
			if ($katb_options['katb_show_date'] == 1) {
				$katb_date = $katb_tdata[$i]['tb_date'];
				$year = intval(substr($katb_date,0,4));
				$month = intval(substr($katb_date,5,2));
				$monthname = date("M", mktime(0, 0, 0, $month, 10));
				$day = intval(substr($katb_date,8,2));
				$katb_html .= ', <i>'.$monthname.' '.$day.'\''.$year.'</i>';
			}
			if ($katb_options['katb_show_location'] == 1) {
				if ( $katb_tdata[$i]['tb_location'] != "" ) $katb_html .= ', <i>'.stripcslashes($katb_tdata[$i]['tb_location']).'</i>';
			}
			if ($katb_options['katb_show_website'] == 1) {
				if ( $katb_tdata[$i]['tb_url'] != "" ) $katb_html .= ', <i><a href="'.esc_url($katb_tdata[$i]['tb_url']).'" title="Testimonial_author_site" target="_blank" >Website</a></i>';
			}
			$katb_html .= '</span>';
			$katb_html .= '</div>';
		}
		$katb_html .= '</div>';
	}
	return $katb_html;
}
add_shortcode('katb_testimonial', 'katb_list_testimonials');

/** ----------- display random testimonials shortcode ------------------------
 * selects testimonials randomly and displays them
 * useage : [katb_random_testimonials group="all" number="all"] 
 * group : "all" or "group" where group is the identifier in the testimonial
 * number : "all" or input the number you want to display
 * ------------------------------------------------------------------------- */

function katb_display_random_testimonials( $atts ) {
	//set up database table name for later use
	global $wpdb,$tablename;
	$tablename = $wpdb->prefix.'testimonial_basics';
	//get user options
	$katb_options = katb_get_options();
	//Initialize Strings
	$katb_html = '';
	$katb_error = "";
	//get shortcode variables
	extract(shortcode_atts(array(
		'group' => 'all',
	    'number' => 'all',
    ), $atts));
	//Sanitize data
	$group = sanitize_text_field($group);
	$number = sanitize_text_field($number);
	//Lets get the testimonials
	$number = intval($number);
	//start by checking that there are testimonials there
	if ( $group == 'all' ) {
		if ( $number != 0 ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY RAND() LIMIT 0,$number ",ARRAY_A );
		} else {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY RAND() ",ARRAY_A );
		}
		$katb_tnumber = $wpdb->num_rows;				
		if ( $katb_tnumber == 0 ) {
			$katb_error = __('There are no approved testimonials to display!','testimonial_basics');
		}
	} else {
		if ( $number > 0 ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY RAND() LIMIT 0,$number ",ARRAY_A );
		} else {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY RAND() ",ARRAY_A );
		}
		$katb_tnumber = $wpdb->num_rows;				
		if ( $katb_tnumber == 0 ) {
			$katb_error = __('There are no approved testimonials in that group to display!','testimonial_basics');
		}
	}
	// Database queried
	//Lets prepare the return string
	if( $katb_error != '') {
		$katb_html = '<div class="katb_error">'.$katb_error.'</div>';
	} else {
		if ( $katb_options['katb_use_formatted_display'] == 1 ) {	
			$katb_html .= '<div class="katb_test_wrap">';
		} else {
			$katb_html .= '<div class="katb_test_wrap_basic">';
		}
		for ( $i = 0 ; $i < $katb_tnumber; $i++ ) {
			//set up hidden popup if excerpt is used	
			if ( $katb_options['katb_use_excerpts'] == 1 ) {
				$katb_html .= '<div class="katb_topopup" id="katb_content_test_'.$katb_tdata[$i]['tb_id'].'">';
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
				$katb_html .= '<div class="katb_excerpt_popup_bg" id="katb_content_test_'.$katb_tdata[$i]['tb_id'].'_bg"></div>';
			}			
			if ( $katb_options['katb_use_formatted_display'] == 1 ) {
				$katb_html .= '<div class="katb_test_box">';
				if ( $katb_options['katb_use_gravatars'] == 1 ){
					$has_valid_avatar = katb_validate_gravatar($katb_tdata[$i]['tb_email']);
					If ( $has_valid_avatar == 1 ) {
						$katb_html .= '<span class="katb_avatar">'. get_avatar( $katb_tdata[$i]['tb_email'], $size = '60' ).'</span>';
					}
				}
				if ( $katb_options['katb_use_excerpts'] == 1 ) {
					$text = stripcslashes($katb_tdata[$i]['tb_testimonial']);
					$length = $katb_options['katb_excerpt_length'];
					$classID = 'katb_content_test_'.$katb_tdata[$i]['tb_id'];
					$text = katb_testimonial_excerpt_filter($length,$text,$classID);
					$katb_html .= '<div class="katb_test_text" >'.$text.'</div>';
				} else {
					$katb_html .= '<div class="katb_test_text" >'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div>';
				}			
				$katb_html .= '<span class="katb_test_meta"><br/>'.stripcslashes($katb_tdata[$i]['tb_name']);
			} else {
				$katb_html .= '<div class="katb_test_box_basic">';
				if ( $katb_options['katb_use_gravatars'] == 1 ) {
					$has_valid_avatar = katb_validate_gravatar($katb_tdata[$i]['tb_email']);
					If ( $has_valid_avatar == 1 ) {
						$katb_html .= '<span class="katb_avatar">'.get_avatar( $katb_tdata[$i]['tb_email'], $size = '60' ).'</span>';
					}
				}
				if ( $katb_options['katb_use_excerpts'] == 1 ) {
					$text = stripcslashes($katb_tdata[$i]['tb_testimonial']);
					$length = $katb_options['katb_excerpt_length'];
					$classID = 'katb_content_test_'.$katb_tdata[$i]['tb_id'];
					$text = katb_testimonial_excerpt_filter($length,$text,$classID);
					$katb_html .= '<div class="katb_test_tex_basic" >'.$text.'</div>';
				} else {
					$katb_html .= '<div class="katb_test_text_basic" >'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div>';
				}		
				$katb_html .= '<span class="katb_test_meta_basic"><strong>'.stripcslashes($katb_tdata[$i]['tb_name']).'</strong>';
			}
			if ($katb_options['katb_show_date'] == 1) {
				$katb_date = $katb_tdata[$i]['tb_date'];
				$year = intval(substr($katb_date,0,4));
				$month = intval(substr($katb_date,5,2));
				$monthname = date("M", mktime(0, 0, 0, $month, 10));
				$day = intval(substr($katb_date,8,2));
				$katb_html .= ', <i>'.$monthname.' '.$day.'\''.$year.'</i>';
			}
			if ($katb_options['katb_show_location'] == 1) {
				if ( $katb_tdata[$i]['tb_location'] != "" ) $katb_html .= ', <i>'.stripcslashes($katb_tdata[$i]['tb_location']).'</i>';
			}
			if ($katb_options['katb_show_website'] == 1) {
				if ( $katb_tdata[$i]['tb_url'] != "" ) $katb_html .= ', <i><a href="'.esc_url($katb_tdata[$i]['tb_url']).'" title="Testimonial_author_site" target="_blank" >Website</a></i>';
			}
			$katb_html .= '</span>';
			$katb_html .= '</div>';
		}
		$katb_html .= '</div>';
	}
	return $katb_html;
}
add_shortcode('katb_random_testimonials', 'katb_display_random_testimonials');

/** ------------- display testimonial input form shortcode -------------------
 * displays the testimonial input form
 * useage : [katb_input_testimonials] 
 * ------------------------------------------------------------------------- */

function katb_display_input_form() {
	$katb_options = katb_get_options();
	global $katb_author,$katb_email,$katb_website,$katb_location,$katb_testimonial,$katb_input_error,$katb_input_success;
	if( $katb_input_error != '') echo '<span class="katb_error">'.__('There were errors so the testimonial was not added: ','testimonial-basics').$katb_input_error.'</span>';
	if( isset($_SESSION['katb_submitted']) && $_SESSION['katb_submitted'] == SHA1('true')) {
		echo '<span class="katb_test_sent">'.__('Submitted - Thankyou!','testimonial-basics').'</span>';
		$_SESSION['katb_submitted'] = SHA1('false');
	}
	$html_string = '';	
	if ($katb_options['katb_include_input_title'] == 1){
		$html_string .= "<h1>".esc_attr(stripcslashes($katb_options['katb_input_title']))."</h1>";
	}
	if ($katb_options['katb_include_email_note'] == 1) {
		$html_string .= '<p>'.esc_attr(stripcslashes($katb_options['katb_email_note'])).'</p>';
	}
	$html_string .= '<div class="katb_input_style">';
	$html_string .= '<form method="POST" action="#">';
	$html_string .= '<label>*'.__('Author','testimonial-basics').' : </label><input type="text"  maxlength="100" name="tb_author" value="'.esc_attr(stripcslashes($katb_author)).'" /><br/>';
	$html_string .= '<label>*'.__('Email','testimonial-basics').'  : </label><input type="text"  maxlength="100" name="tb_email" value="'.esc_attr(stripcslashes($katb_email)).'" /><br/>';
	$html_string .= '<label>'.__('Website','testimonial-basics').'  : </label><input type="text"  maxlength="100" name="tb_website" value="'.esc_url(stripcslashes($katb_website)).'" /><br/>';
	$html_string .= '<label>'.__('Location','testimonial-basics').' : </label><input type="text"  maxlength="100" name="tb_location" value="'.esc_attr(stripcslashes($katb_location)).'" /><br/>';
	$html_string .= '<label>*'.__('Testimonial','testimonial-basics').' : </label><br/><textarea rows="5" name="tb_testimonial" >'.wp_kses_post(stripcslashes($katb_testimonial)).'</textarea>';
	if ( $katb_options['katb_show_html_content'] == TRUE || $katb_options['katb_show_html_content'] == 1 ) {
		$html_string .= '<p>HTML '.__('Allowed','testimonial-basics').': <code>p br i em strong q h1-h6</code></p><br/>';
	}
	if ($katb_options['katb_use_captcha'] == TRUE || $katb_options['katb_use_captcha'] == 1 ) {
		$html_string .= '<div class="katb_captcha">';
		$html_string .=	'<label for="verify_main">'.__('Verification','testimonial-basics').': </label>';
		$html_string .=	'<input type="text" id="verify_main" name="verify" value="'.__('Enter Captcha Letters','testimonial-basics').'" onclick="this.select();" />';
		$html_string .= '<img src="'.plugin_dir_url(__FILE__).'katb_captcha.php" alt="Verification Captcha" />';
		$html_string .= '</div>';
	}
	$html_string .= '<input class="katb_submit" type="submit" name="katb_submitted" value="'.__('Submit','testimonial-basics').'" />';
	$html_string .= '<input class="katb_reset" type="submit" name="katb_reset" value="'.__('Reset','testimonial-basics').'" />';
	$html_string .= wp_nonce_field("katb_nonce_1","katb_main_form_nonce",false,false);
	$html_string .= '</form>';
	$html_string .= '</div>';
	$html_string .= '<div class="katb_clearboth"></div><p>* '.__('Required','testimonial-basics').'</p>';
	if ($katb_options['katb_use_gravatars'] == 1 || $katb_options['katb_widget_use_gravatars'] == 1 ) {
		$html_string .= '<p>'.__('Add a photo? ','testimonial-basics').'<a href="https://en.gravatar.com/" title="Gravatar Site" target="_blank" ><img class="gravatar_logo" src="'.plugin_dir_url(__FILE__).'Gravatar80x16.jpg" alt="Gravatar Website" title="Gravatar Website" /></a></p>';
	}
	return $html_string;
}
add_shortcode('katb_input_testimonials', 'katb_display_input_form');

?>