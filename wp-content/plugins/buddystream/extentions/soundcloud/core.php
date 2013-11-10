<?php

/**
 * Authorization from Soundcloud
 */

add_action('wp', 'buddystream_soundcloudCode',1);
function buddystream_soundcloudCode()
{
    global $bp;
    
    if (isset($_GET['buddystream_auth']) && $_GET['buddystream_auth'] == 'soundcloud') {
        
       $soundcloud = new BuddystreamSoundcloud(
           get_site_option("soundcloud_client_id"), 
           get_site_option("soundcloud_client_secret"),
           $bp->root_domain."/?buddystream_auth=soundcloud"
       );
            
        try {
            $soundcloudToken = $soundcloud->accessToken($_GET['code']);      
        } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
            buddystreamLog("Soundcloud : ".$e->getMessage(),'error');
        }

        if($soundcloudToken['access_token']){
            
            update_user_meta($bp->loggedin_user->id, 'soundcloud_access_token', $soundcloudToken['access_token']);
            update_user_meta($bp->loggedin_user->id, 'soundcloud_expires_in', $soundcloudToken['expires_in']+time());
            update_user_meta($bp->loggedin_user->id, 'soundcloud_refresh_token', $soundcloudToken['refresh_token']);
        }
        
        wp_redirect($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-soundcloud');
        exit;
    }
}

/**
 * Replace all track url into real players
 */

add_filter( 'bp_get_activity_content','BuddystreamSoundcloudPlayers',9);
add_filter( 'bp_get_activity_content_body','BuddystreamSoundcloudPlayers',9);
function BuddystreamSoundcloudPlayers($text) {
    
   return preg_replace(
          '#https://api.soundcloud.com/([a-z0-9_]+)/([a-z0-9_]+)#i', 
          '<embed allowscriptaccess="always" height="81" src=http://player.soundcloud.com/player.swf?url=https://api.soundcloud.com/\\1/\\2&enable_api=true&object_id=myPlayer" type="application/x-shockwave-flash" width="100%" name="myPlayer"></embed>',
          $text);   
}


/**
 * 
 * Page loader functions 
 *
 */


function buddystream_soundcloud(){
    buddystreamPageLoader('soundcloud');
}


/**
 * User pages
 */

function buddystream_soundcloud_profile_navigation(){
    
     global $bp;
     if(get_site_option('buddystream_soundcloud_tracks') == 'on' && get_user_meta($bp->displayed_user->id, 'soundcloud_access_token',1)){
     
          bp_core_new_nav_item( 
                array(
                    'name' => __( 'Soundcloud tracks', 'buddystream_soundcloud' ),
                    'slug' => 'soundcloudtracks',
                    'position' => 80,
                    'screen_function' => 'buddystream_soundcloud_tracks'
                )
          );     
      }
}
buddystream_soundcloud_profile_navigation();

/**
 * Tracks
 */

function buddystream_soundcloud_tracks(){
    buddystreamUserPageLoader('soundcloud','tracks');
}

function buddystream_soundcloud_tracks_screen_title()
{
    __('Soundcloud', 'buddystream_soundcloud');
}

function buddystream_soundcloud_tracks_screen_content()
{
    global $bp;
    include "templates/UserTracks.php";
}

/**
 * User settings
 */

function buddystream_soundcloud_user_settings()
{
   buddystreamUserPageLoader('soundcloud','settings');
}

function buddystream_soundcloud_settings_screen_title()
{
    __('Soundcloud', 'buddystream_soundcloud');
}

function buddystream_soundcloud_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}


?>