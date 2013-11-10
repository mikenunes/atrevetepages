<?php
/**
 * Add sharing button
 */

function buddystreamLinkedinSharing(){
 global $bp;
    if (get_site_option("buddystream_linkedin_consumer_key")) {
        if (get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token',1)) {
            echo'<div class="linkedin_share_button"
            onclick="linkedin_addTag()">
            <img src="'.plugins_url().'/buddystream/extentions/linkedin/icon.png" title="'.__('to linkedin', 'buddystream_linkedin').'"> '.__('to linkedin', 'buddystream_linkedin').'
            </div>';
            
            $max_message = __("You\'ve reached the max. amount of characters for a update.  The Message will appear truncated on LinkedIn.", "buddystream_linkedin");
            echo '<div class="linkedin_share_counter">140</div>';
            
        }
    }
}


/**
 * If set linkenIn button
 */

add_action('bp_activity_entry_meta','BuddystreamLinkedinButton',1);
add_action('bp_group_forum_topic_meta', 'BuddystreamLinkedinButton',1);

function BuddystreamLinkedinButton() {
    if(get_site_option("buddystream_linkedin_share")){
        
    if(get_site_option("buddystream_linkedin_share_counter")){  
        $dataCount = 'data-counter="right"';
    }else{
        $dataCount = '';
    }
        
        echo '<div class="linkedin_share_container">
                    <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
                    <script type="IN/Share" data-url="'.bp_get_activity_thread_permalink().'" '.$dataCount.'></script>
              </div>';
    }   
}

/**
 * Post update to Linkedin
 */

function buddystreamLinkedinPostUpdate($content = "", $shortLink = "", $user_id = 0) {
    
    global $bp;
    
    $linkedin = new BuddystreamLinkedin;
    $linkedin->setConsumerKey(get_site_option("buddystream_linkedin_consumer_key"));
    $linkedin->setConsumerSecret(get_site_option("buddystream_linkedin_consumer_secret"));
    
    $linkedin->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-linkedin');
    
    $linkedin->setAccessToken(get_user_meta($user_id, 'buddystream_linkedin_token',1));
    $linkedin->setAccessTokenSecret(get_user_meta($user_id, 'buddystream_linkedin_tokensecret',1));
    
    $linkedin->setShortLink($shortLink);
    
    $linkedin->setPostContent($content);
    $linkedin->postUpdate();
}

/**
 * Add javascript and stylesheet file for Linkedin
 */
wp_enqueue_script('buddystreamlinkedin', plugins_url() . '/buddystream/extentions/linkedin/main.js');
wp_enqueue_style('buddystreamlinkedin', plugins_url() . '/buddystream/extentions/linkedin/style.css');


/**
 * 
 * Page loader functions 
 *
 */

function buddystream_linkedin()
{
    buddystreamPageLoader('linkedin');
}

function buddystream_linkedin_user_settings()
{
    buddystreamUserPageLoader('linkedin');
}

function buddystream_linkedin_settings_screen_title()
{
    __('LinkedIn', 'buddystream_linkedin');
}

function buddystream_linkedin_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}

?>
