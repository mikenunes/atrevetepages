<?php
   /* This is uninstall.php for the plugin testimonial-basics */
   // If uninstall not called from WordPress exit this function
   if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
   // Delete Options
   delete_option( 'katb_testimonial_basics_options' );
   delete_option( 'widget_katb_display_testimonial_widget');
   delete_option('widget_katb_input_testimonial_widget');
   delete_option('katb_database_version');
   // remove any additionsl options and dadabase tables not removed above
 	global $wpdb;
	$tablename = $wpdb->prefix.'testimonial_basics';
	$wpdb->query("DROP TABLE IF EXISTS $tablename");
?>