<?php

/**
 * Add Facebook sharing button
 */
function buddystreamFacebookSharing(){
    global $bp;
    if (get_site_option("facestream_application_id")) {
        if (get_user_meta($bp->loggedin_user->id, 'facestream_session_key',1)) {
            echo '<div class="facebook_share_button"
                    onclick="facebook_addTag()">
                  <img src="'.plugins_url().'/buddystream/extentions/facebook/icon.png" title="'.__('to facebook', 'buddystream_facebook').'"> '.__('to facebook', 'buddystream_facebook').'
                  </div>';
        }
    }
}

/**
 * Post update to Facebook
 */
function buddystreamFacebookPostUpdate($content = "", $shortLink = "", $user_id = 0) {

    include_once("facebook.php");
    $facebook = new BuddystreamFacebook();
    $facebook->setCallbackUrl(get_site_url().'/?social=facebook');
    $facebook->setApplicationKey(get_site_option("facestream_application_id"));
    $facebook->setApplicationId(get_site_option("facestream_application_id"));
    $facebook->setApplicationSecret(get_site_option("facestream_application_secret"));
    $facebook->setAccessToken(get_user_meta($user_id, 'facestream_session_key',1));
    $facebook->setUserId(get_user_meta($user_id, 'facestream_user_id',1));
    $facebook->setPostContent(str_replace("#twitter", "", $content));
    $facebook->setShortLink($shortLink);
    $facebook->postUpdate();
}


/**
 * Add javascript and stylesheet file for Facebook
 */
wp_enqueue_style('buddystreamfacebook', plugins_url() . '/buddystream/extentions/facebook/style.css');
wp_enqueue_script('buddystreamfacebook', plugins_url() . '/buddystream/extentions/facebook/main.js');


/**
 * If set add Facebook like button
 */

add_action('bp_activity_entry_meta','BuddystreamFacebookLike',9);
function BuddystreamFacebookLike() {
    if(get_site_option("buddystream_facebook_like")){
        echo '<div class="facebook_like_container"><iframe src="http://www.facebook.com/plugins/like.php?href='.bp_get_activity_thread_permalink().'&amp;layout='.get_site_option("buddystream_facebook_like_type").'&amp;show-faces='.get_site_option("buddystream_facebook_like_faces").'&amp;action=like&amp;colorscheme='.get_site_option("buddystream_facebook_like_scheme").'" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:50px; margin:0px; padding:0px; height:20px"></iframe></div>';
    }
}


/**
 * 
 * Page loader functions 
 *
 */


function buddystream_facebook(){
    buddystreamPageLoader('facebook');
}


/**
 * User pages
 */

function buddystream_facebook_profile_navigation(){
    
     global $bp;
     if(get_site_option('buddystream_facebook_album') == 'on' && get_user_meta($bp->displayed_user->id, 'facestream_session_key',1)){
     
          bp_core_new_nav_item( 
                array(
                    'name' => __( 'Facebook album', 'buddystream_facebook' ),
                    'slug' => 'facebookalbum',
                    'position' => 80,
                    'screen_function' => 'buddystream_facebook_album'
                )
          );     
      }
}
buddystream_facebook_profile_navigation();

/**
 * Album
 */

function buddystream_facebook_album(){
    buddystreamUserPageLoader('facebook','album');
}

function buddystream_facebook_album_screen_title()
{
    __('Facebook', 'buddystream_facebook');
}

function buddystream_facebook_album_screen_content()
{
    global $bp;
    include "templates/UserAlbum.php";
}

/**
 * User settings
 */

function buddystream_facebook_user_settings()
{
   buddystreamUserPageLoader('facebook','settings');
}

function buddystream_facebook_settings_screen_title()
{
    __('Facebook', 'buddystream_facebook');
}

function buddystream_facebook_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}

?>
