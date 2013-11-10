<?php
/**
 * Custom testimonial display styles in widget area
 *
 * This file is called by katb_functions.php and loads the user selections for background and text colors
 *
 * @package		Testimonial Basics WordPress Plugin
 * @copyright	Copyright (c) 2012, Kevin Archibald
 * @license		http://www.gnu.org/licenses/quick-guide-gplv3.html  GNU Public License
 * @author		Kevin Archibald <www.kevinsspace.ca/contact/>
 */

/* Get the user choices for the plugin options */
$katb_options = katb_get_options();
?>
<?php if ( $katb_options['katb_widget_use_italic_style'] == 1 || $katb_options['katb_widget_use_formatted_display'] ==1 ) { ?>
	<style type="text/css" >
		<?php if ( $katb_options['katb_widget_use_italic_style'] == 1 && $katb_options['katb_widget_use_formatted_display'] !=1 ) { ?>
			.katb_widget_text_basic,.katb_widget_multiple_text_basic {font-style: italic;}
		<?php } elseif( $katb_options['katb_widget_use_formatted_display'] ==1 ) {
			if ( $katb_options['katb_widget_use_italic_style'] == 1 ) { ?>
				.katb_widget_text,.katb_widget_multiple_text {font-style: italic;}
			<?php } ?>
			.katb_widget_test_wrap {
				<?php if ( $katb_options['katb_widget_font'] != 'default font' ) { ?>
					font-family: <?php echo $katb_options['katb_widget_font']; ?>;
				<?php } ?>
				background-color: <?php echo $katb_options['katb_widget_background_color']; ?>;
				color: <?php echo $katb_options['katb_widget_font_color']; ?>;
			}
			.katb_widget_multiple_box {
				<?php if ( $katb_options['katb_widget_font'] != 'default font' ) { ?>
					font-family: <?php echo $katb_options['katb_widget_font']; ?>;
				<?php } ?>
				background-color: <?php echo $katb_options['katb_widget_background_color']; ?>;
				color: <?php echo $katb_options['katb_widget_font_color']; ?>;
			}
			.katb_widget_test_meta,.katb_widget_multiple_meta {color: <?php echo $katb_options['katb_widget_author_location_color']; ?>;}
			.katb_widget_test_wrap a,.katb_widget_multiple_box a {color: <?php echo $katb_options['katb_widget_website_link_color']; ?>!important;}
			.katb_widget_test_wrap a:hover,.katb_widget_multiple_box a:hover {color: <?php echo $katb_options['katb_widget_website_link_hover_color']; ?>!important;}
		<?php } ?>
	</style>
<?php } ?>