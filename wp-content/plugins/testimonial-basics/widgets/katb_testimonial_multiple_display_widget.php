<?php
/*
Plugin Name: Testimonial Basics Multiple Display Widget
Plugin URI: http://kevinsspace.ca/testimonial-basics-wordpress-plugin/
Description: A plugin to display multiple testimonials
Version: 3.0.0
Author: Kevin Archibald
Author URI: http://kevinsspace.ca/
License: GPLv3
 */
 
 // use widgets_init action hook to execute custom function
 add_action ( 'widgets_init','katb_multiple_display_register_widget' );

//register our widget 
 function katb_multiple_display_register_widget() {
 	register_widget ( 'katb_multiple_display_testimonial_widget' );
 }
 
 //widget class
class katb_multiple_display_testimonial_widget extends WP_Widget {

    //process the new widget
    function katb_multiple_display_testimonial_widget() {
        $widget_ops = array( 
			'classname' => 'katb_multiple_display_widget_class', 
			'description' => __('Display multiple testimonials.','testimonial-basics') 
			); 
        $this->WP_Widget( 'katb_multiple_display_testimonial_widget', __('Testimonial Multiple Display Widget','testimonial-basics'), $widget_ops );
    }
 	
 	// Form for widget setup
 	function form ( $instance ) {
 		$katb_multiple_display_defaults = array(
			'katb_multiple_title' => 'Testimonials',
			'katb_multiple_group' => 'all',
			'katb_multiple_by' => 'date',
			'katb_multiple_number' => 'all'
		);
		$instance = wp_parse_args( (array) $instance, $katb_multiple_display_defaults );
		$title = $instance['katb_multiple_title'];
		$group = $instance['katb_multiple_group'];
		$by = $instance['katb_multiple_by'];
		$number = $instance['katb_multiple_number'];
		?>
		<p>Title : <input class="widefat" id="<?php echo $this->get_field_id('katb_multiple_title'); ?>" name="<?php echo $this->get_field_name('katb_multiple_title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p>Group : <input class="widefat" id="<?php echo $this->get_field_id('katb_multiple_group'); ?>" name="<?php echo $this->get_field_name('katb_multiple_group'); ?>" type="text" value="<?php echo $group; ?>" /></p>
		<p>By : <input class="widefat" id="<?php echo $this->get_field_id('katb_multiple_by'); ?>" name="<?php echo $this->get_field_name('katb_multiple_by'); ?>" type="text" value="<?php echo $by; ?>" /></p>
		<p>Number : <input class="widefat" id="<?php echo $this->get_field_id('katb_multiple_number'); ?>" name="<?php echo $this->get_field_name('katb_multiple_number'); ?>" type="text" value="<?php echo $number; ?>" /></p>
		<?php	
	}
	
	//save the widget settings
	function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['katb_multiple_title'] = strip_tags( $new_instance['katb_multiple_title'] );
		$instance['katb_multiple_group'] = strip_tags( $new_instance['katb_multiple_group'] );
		$instance['katb_multiple_by'] = strip_tags( $new_instance['katb_multiple_by'] );
		$instance['katb_multiple_number'] = strip_tags( $new_instance['katb_multiple_number'] );
		//white list options
		if($instance['katb_multiple_group'] == '')$instance['katb_multiple_group'] = 'all';
		if($instance['katb_multiple_by'] != 'random' && $instance['katb_multiple_by'] != 'date' && $instance['katb_multiple_by'] != 'order') $instance['katb_multiple_by'] = 'random';
		if($instance['katb_multiple_number'] != 'all' && is_numeric($instance['katb_multiple_number']) == FALSE)$instance['katb_multiple_number'] = 'all';
		return $instance;
	}
	
	//display the widget
    function widget($args, $instance) {
    	$katb_options = katb_get_options();
    	extract ( $args);
		echo $before_widget;
		$title = apply_filters( 'widget_title', $instance['katb_multiple_title'] );
		$group = esc_attr($instance['katb_multiple_group']);
		$by = esc_attr($instance['katb_multiple_by']);
		$number = esc_attr($instance['katb_multiple_number']);
		if ( !empty( $title )) { echo $before_title.$title.$after_title;}
		global $wpdb,$tablename;
		$tablename = $wpdb->prefix.'testimonial_basics';
		$katb_multiple_widget_error = "";
		//Go get the testimonials
		if ( $group == 'all' && $by == 'date' && $number == 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_date` DESC ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group == 'all' && $by == 'order' && $number == 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group == 'all' && $by == 'random' && $number == 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY RAND() ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group == 'all' && $by == 'date' && $number != 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_date` DESC LIMIT 0,$number ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group == 'all' && $by == 'order' && $number != 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY `tb_order` = '0',`tb_order` ASC LIMIT 0,$number ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group == 'all' && $by == 'random' && $number != 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' ORDER BY RAND() LIMIT 0,$number ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group != 'all' && $by == 'date' && $number == 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_date` DESC ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group != 'all' && $by == 'order' && $number == 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group != 'all' && $by == 'random' && $number == 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY RAND() ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group != 'all' && $by == 'date' && $number != 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_date` DESC LIMIT 0,$number ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group != 'all' && $by == 'order' && $number != 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY `tb_order` = '0',`tb_order` ASC,`tb_date` DESC LIMIT 0,$number ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		} elseif ( $group != 'all' && $by == 'random' && $number != 'all' ) {
			$katb_tdata = $wpdb->get_results( " SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_group` = '$group' ORDER BY RAND() LIMIT 0,$number ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
		}
		if ( $katb_tnumber == 0 ) $katb_multiple_widget_error = __('There are no approved testimonials to display!','testimonial_basics');	

		// Database queried
		//Lets display the selected testimonial
		$katb_html = '';
		if( $katb_multiple_widget_error != '') {
			$katb_html .= '<div class="katb_display_widget_error">'.$katb_multiple_widget_error.'</div>';
		} else {
			for ( $i = 0 ; $i < $katb_tnumber; $i++ ) {
				//set up hidden popup if excerpt is used	
				if ( $katb_options['katb_use_widget_excerpts'] == 1 ) {
					$katb_html .= '<div class="katb_topopup" id="katb_widget_multiple_'.$katb_tdata[$i]['tb_id'].'">';
					$katb_html .= '<div class="katb_close"></div>';
					$katb_html .= '<div class="katb_popup_text">'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div><br/>';
					$katb_html .= '<span class="katb_popup_meta">'.stripcslashes($katb_tdata[$i]['tb_name']);
					if ($katb_options['katb_widget_show_date'] == 1) {
						$katb_date = $katb_tdata[$i]['tb_date'];
						$year = intval(substr($katb_date,0,4));
						$month = intval(substr($katb_date,5,2));
						$monthname = date("M", mktime(0, 0, 0, $month, 10));
						$day = intval(substr($katb_date,8,2));
						$katb_html .= ', '.$monthname.' '.$day.'\''.$year;
					}
					if ($katb_options['katb_widget_show_location'] == 1) {
						if ( $katb_tdata[$i]['tb_location'] != "" ) $katb_html .= ', '.stripcslashes($katb_tdata[$i]['tb_location']);
					}
					$katb_html .= '</span></div>';
					$katb_html .= '<div class="katb_loader"></div>';
					$katb_html .= '<div class="katb_excerpt_popup_bg" id="katb_widget_multiple_'.$katb_tdata[$i]['tb_id'].'_bg"></div>';
				}
				if ( $katb_options['katb_widget_use_formatted_display'] == 1 ) {
					$katb_html .= '<div class="katb_widget_multiple_box">';
					if ( $katb_options['katb_widget_use_gravatars'] == 1 ){
						$has_valid_avatar = katb_validate_gravatar($katb_tdata[$i]['tb_email']);
						If ( $has_valid_avatar == 1 ) {
							$katb_html .= '<span class="katb_widget_avatar">'. get_avatar( $katb_tdata[$i]['tb_email'], $size = '60' ).'</span>';
						}
					}
					if ( $katb_options['katb_use_widget_excerpts'] == 1 ) {
						$text = stripcslashes($katb_tdata[$i]['tb_testimonial']);
						$length = $katb_options['katb_widget_excerpt_length'];
						$classID = 'katb_widget_multiple_'.$katb_tdata[$i]['tb_id'];
						$text = katb_testimonial_excerpt_filter($length,$text,$classID);
						$katb_html .= '<div class="katb_widget_multiple_text" >'.$text.'</div>';
					} else {
						$katb_html .= '<div class="katb_widget_multiple_text" >'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div>';
					}	
					$katb_html .= '<span class="katb_widget_multiple_meta">'.stripcslashes($katb_tdata[$i]['tb_name']);
				} else {
					$katb_html .= '<div class="katb_widget_multiple_box_basic">';
					if ( $katb_options['katb_widget_use_gravatars'] == 1 ) {
						$has_valid_avatar = katb_validate_gravatar($katb_tdata[$i]['tb_email']);
						If ( $has_valid_avatar == 1 ) {
							$katb_html .= '<span class="katb_widget_avatar">'.get_avatar( $katb_tdata[$i]['tb_email'], $size = '60' ).'</span>';
						}
					}
					if ( $katb_options['katb_use_widget_excerpts'] == 1 ) {
						$text = stripcslashes($katb_tdata[$i]['tb_testimonial']);
						$length = $katb_options['katb_widget_excerpt_length'];
						$classID = 'katb_widget_multiple_'.$katb_tdata[$i]['tb_id'];
						$text = katb_testimonial_excerpt_filter($length,$text,$classID);
						$katb_html .= '<div class="katb_widget_multiple_text_basic" >'.$text.'</div>';
					} else {
						$katb_html .= '<div class="katb_widget_multiple_text_basic" >'.stripcslashes($katb_tdata[$i]['tb_testimonial']).'</div>';
					}
					$katb_html .= '<span class="katb_widget_multiple_meta_basic"><strong>'.stripcslashes($katb_tdata[$i]['tb_name']).'</strong>';
				}
				if ($katb_options['katb_widget_show_date'] == 1) {
					$katb_date = $katb_tdata[$i]['tb_date'];
					$year = intval(substr($katb_date,0,4));
					$month = intval(substr($katb_date,5,2));
					$monthname = date("M", mktime(0, 0, 0, $month, 10));
					$day = intval(substr($katb_date,8,2));
					$katb_html .= ', <i>'.$monthname.' '.$day.'\''.$year.'</i>';
				}
				if ($katb_options['katb_widget_show_location'] == 1) {
					if ( $katb_tdata[$i]['tb_location'] != "" ) $katb_html .= ', <i>'.stripcslashes($katb_tdata[$i]['tb_location']).'</i>';
				}
				if ($katb_options['katb_widget_show_website'] == 1) {
					if ( $katb_tdata[$i]['tb_url'] != "" ) $katb_html .= ', <i><a href="'.esc_url($katb_tdata[$i]['tb_url']).'" title="Testimonial_author_site" target="_blank" >Website</a></i>';
				}
				$katb_html .= '</span>';
				$katb_html .= '</div>';
			}
		}
		echo $katb_html;
		echo $after_widget; 
    }
}
?>