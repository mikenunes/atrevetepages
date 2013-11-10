<?php
/*
Plugin Name: BuddyPress Spam Link
Description: Adds the "Mark as spammer" link back to the admin bar, and adds "Spammer" to the activity stream's entry meta buttons.
Plugin URI: http://sillybean.net/
Version: 0.2
Author: Stephanie Leary
*/
 
// restore action moved to settings in 1.6
add_action( 'bp_actions', 'bp_core_action_set_spammer_status' );
 
// Add Mark as Spammer link to the admin bar
add_action( 'admin_bar_menu', 'mark_user_as_spammer' );
 
function mark_user_as_spammer() {
	global $wp_admin_bar, $bp;
	
	// Unique ID for the 'My Account' menu
	$bp->user_admin_menu_id = 'user-admin';
	
	// User Admin > Spam/unspam	
	if ( !bp_core_is_user_spammer( $bp->displayed_user->id ) ) : 
		$wp_admin_bar->add_menu( array(
			'parent' => $bp->user_admin_menu_id,
			'id'     => $bp->user_admin_menu_id . 'mark-as-spammer',
			'title'  => __( 'Mark as Spammer', 'buddypress' ),
			'href'   => wp_nonce_url( bp_displayed_user_domain() . 'admin/mark-spammer/', 'mark-unmark-spammer' ),
		) );
	else :
		$wp_admin_bar->add_menu( array(
			'parent' => $bp->user_admin_menu_id,
			'id'     => $bp->user_admin_menu_id . 'unmark-as-spammer',
			'title'  => __( 'Not a Spammer', 'buddypress' ),
			'href'   => wp_nonce_url( bp_displayed_user_domain() . 'admin/unmark-spammer/', 'mark-unmark-spammer' ),
		) );	
	endif;
}
 
// Add Spammer link button to activity stream
add_action( 'bp_activity_entry_meta', 'mark_activity_user_as_spammer' );
 
function mark_activity_user_as_spammer() {
	if ( current_user_can( 'edit_users' ) ) {
		$user_id = bp_get_activity_user_id();
		
		$href = wp_nonce_url( bp_get_activity_user_link() . 'admin/mark-spammer/', 'mark-unmark-spammer' );
		$label = __( 'Spammer', 'buddypress' );
			
		echo '<a class="button" href="' . $href . '">' . $label . '</a>';
	}
}
