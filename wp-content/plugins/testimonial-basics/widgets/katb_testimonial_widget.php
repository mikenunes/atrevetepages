<?php
/*
Plugin Name: Testimonial Basics Display Widget
Plugin URI: http://kevinsspace.ca/testimonial-basics-wordpress-plugin/
Description: A plugin to display a testimonial
Version: 3.0.0
Author: Kevin Archibald
Author URI: http://kevinsspace.ca/
License: GPLv3
 */
 
 // use widgets_init action hook to execute custom function
 add_action ( 'widgets_init','katb_display_register_widget' );

//register our widget 
 function katb_display_register_widget() {
 	register_widget ( 'katb_display_testimonial_widget' );
 }
 
 //widget class
class katb_display_testimonial_widget extends WP_Widget {

    //process the new widget
    function katb_display_testimonial_widget() {
        $widget_ops = array( 
			'classname' => 'katb_display_widget_class', 
			'description' => __('Display a random or selected testimonial.','testimonial-basics') 
			); 
        $this->WP_Widget( 'katb_display_testimonial_widget', __('Testimonial Display Widget','testimonial-basics'), $widget_ops );
    }
 	
 	// Form for widget setup
 	function form ( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : __('Testimonial','testimonial-basics');
		$type = isset($instance['type']) ? esc_attr($instance['type']) : 'random';
		?>
		<p>Title :
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p>Random or ID :
		<input class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="text" value="<?php echo $type; ?>" /></p>
		<?php	
	}
	
	//save the widget settings
	function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
		$instance['type'] = strip_tags( $new_instance['type'] );
		//only allow random or an integer
		if($instance['type'] != 'random' && is_numeric($instance['type']) == FALSE ) $instance['type'] = 'random';
	
		return $instance;
	}
	
	//display the widget
    function widget($args, $instance) {
    	$katb_options = katb_get_options();
    	extract ( $args);
		echo $before_widget;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$id = esc_attr($instance['type']);
		if ( !empty( $title )) { echo $before_title.$title.$after_title;}
		global $wpdb,$tablename;
		$tablename = $wpdb->prefix.'testimonial_basics';
		$katb_display_widget_error = "";
		
		if ( $id == 'random' || $id == 'Random') {
			$katb_tdata2 = $wpdb->get_results( " SELECT `tb_id` FROM `$tablename` WHERE `tb_approved` = '1' ",ARRAY_A);
			$katb_tnumber = $wpdb->num_rows;
			if ( $katb_tnumber == 0 ) {
				$katb_display_widget_error = __('There are no approved testimonials to display!','testimonial-basics');
			} else {
				$rand = rand(0, $katb_tnumber-1);
				$random_id = $katb_tdata2[$rand]['tb_id'];
				$katb_tdata = $wpdb->get_results("SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_id` = $random_id ",ARRAY_A );
				$katb_tnumber = $wpdb->num_rows;
			}
		} elseif (intval($id) != '') {
			//$id = intval($id);
			$katb_tdata = $wpdb->get_results("SELECT * FROM `$tablename` WHERE `tb_approved` = '1' AND `tb_id` = $id ",ARRAY_A );
			$katb_tnumber = $wpdb->num_rows;
			if ( $katb_tnumber == 0 ) $katb_display_widget_error = __('Could not find testimonial','testimonial-basics');
		} else {
			//$id is an unknown
			$katb_display_widget_error = __('Could not find testimonial','testimonial-basics');
		}
		// Database queried
		//Lets display the selected testimonial
		$katb_html = '';
		if( $katb_display_widget_error != '') {
			$katb_html .= '<div class="katb_display_widget_error">'.$katb_display_widget_error.'</div>';
		} else {
			//set up hidden popup if excerpt is used	
			if ( $katb_options['katb_use_widget_excerpts'] == 1 ) {
				$katb_html .= '<div class="katb_topopup" id="katb_widget_single_'.$katb_tdata[0]['tb_id'].'">';
				$katb_html .= '<div class="katb_close"></div>';
				$katb_html .= '<div class="katb_popup_text">'.stripcslashes($katb_tdata[0]['tb_testimonial']).'</div><br/>';
				$katb_html .= '<span class="katb_popup_meta">'.stripcslashes($katb_tdata[0]['tb_name']);
				if ($katb_options['katb_widget_show_date'] == 1) {
					$katb_date = $katb_tdata[0]['tb_date'];
					$year = intval(substr($katb_date,0,4));
					$month = intval(substr($katb_date,5,2));
					$monthname = date("M", mktime(0, 0, 0, $month, 10));
					$day = intval(substr($katb_date,8,2));
					$katb_html .= ', '.$monthname.' '.$day.'\''.$year;
				}
				if ($katb_options['katb_widget_show_location'] == 1) {
					if ( $katb_tdata[0]['tb_location'] != "" ) $katb_html .= ', '.stripcslashes($katb_tdata[0]['tb_location']);
				}
				$katb_html .= '</span></div>';
				$katb_html .= '<div class="katb_loader"></div>';
				$katb_html .= '<div class="katb_excerpt_popup_bg" id="katb_widget_single_'.$katb_tdata[0]['tb_id'].'_bg"></div>';
			}
			if ($katb_options['katb_widget_use_formatted_display'] == 1 ) {	
				$katb_html .= '<div class="katb_widget_test_wrap">';
				$katb_html .= '<div class="katb_widget_test_box">';
				if ( $katb_options['katb_widget_use_gravatars'] == 1 ){
					$has_valid_avatar = katb_validate_gravatar($katb_tdata[0]['tb_email']);
					If ( $has_valid_avatar == 1 ) {
						$katb_html .= '<span class="katb_widget_avatar">'. get_avatar( $katb_tdata[0]['tb_email'], $size = '50' ).'</span>';
					}
				}
				if ( $katb_options['katb_use_widget_excerpts'] == 1 ) {
					$text = stripcslashes($katb_tdata[0]['tb_testimonial']);
					$length = $katb_options['katb_widget_excerpt_length'];
					$classID = 'katb_widget_single_'.$katb_tdata[0]['tb_id'];
					$text = katb_testimonial_excerpt_filter($length,$text,$classID);
					$katb_html .= '<div class="katb_widget_text" >'.$text.'</div>';
				} else {
					$katb_html .= '<div class="katb_widget_text" >'.stripcslashes($katb_tdata[0]['tb_testimonial']).'</div>';
				}
				$katb_html .= '</div>';
				$katb_html .= '<span class="katb_widget_test_meta">'.stripcslashes($katb_tdata[0]['tb_name']);
			} else {
				$katb_html .= '<div class="katb_widget_test_wrap_basic">';
				$katb_html .= '<div class="katb_widget_test_box_basic">';
				if ( $katb_options['katb_widget_use_gravatars'] == 1 ){
					$has_valid_avatar = katb_validate_gravatar($katb_tdata[0]['tb_email']);
					If ( $has_valid_avatar == 1 ) {
						$katb_html .= '<span class="katb_widget_avatar">'. get_avatar( $katb_tdata[0]['tb_email'], $size = '50' ).'</span>';
					}
				}
				if ( $katb_options['katb_use_widget_excerpts'] == 1 ) {
					$text = stripcslashes($katb_tdata[0]['tb_testimonial']);
					$length = $katb_options['katb_widget_excerpt_length'];
					$classID = 'katb_widget_single_'.$katb_tdata[0]['tb_id'];
					$text = katb_testimonial_excerpt_filter($length,$text,$classID);
					$katb_html .= '<div class="katb_widget_text_basic" >'.$text.'</div>';
				} else {
					$katb_html .= '<div class="katb_widget_text_basic" >'.stripcslashes($katb_tdata[0]['tb_testimonial']).'</div>';
				}	
				$katb_html .= '</div>';
				$katb_html .= '<span class="katb_widget_test_meta_basic"><strong>'.stripcslashes($katb_tdata[0]['tb_name']).'</strong>';
			}
			if ($katb_options['katb_widget_show_date'] == 1) {
				$katb_date = $katb_tdata[0]['tb_date'];
				$year = intval(substr($katb_date,0,4));
				$month = intval(substr($katb_date,5,2));
				$monthname = date("M", mktime(0, 0, 0, $month, 10));
				$day = intval(substr($katb_date,8,2));
				$katb_html .= ', <i>'.$monthname.' '.$day.'\''.$year.'</i>';
			}
			if ($katb_options['katb_widget_show_location'] == 1) {
				if ( $katb_tdata[0]['tb_location'] != "" ) $katb_html .= ', <i>'.stripcslashes($katb_tdata[0]['tb_location']).'</i>';
			}
			if ($katb_options['katb_widget_show_website'] == 1) {
				if ( $katb_tdata[0]['tb_url'] != "" ) $katb_html .= ', <i><a href="'.esc_url($katb_tdata[0]['tb_url']).'" title="Testimonial_author_site" target="_blank" >Website</a></i>';
			}
			$katb_html .= '</span>';
			$katb_html .= '</div>';
		}
		echo $katb_html;
		echo $after_widget; 
    }
}
?>