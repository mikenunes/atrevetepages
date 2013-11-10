<?php



if($_GET['test'] == 'true'){
    

    echo "<pre>";
    
    global $bp;
    
    $linkedin = new BuddystreamLinkedin();
    $linkedin->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-linkedin');
    $linkedin->setConsumerKey(get_site_option("buddystream_linkedin_consumer_key"));
    $linkedin->setConsumerSecret(get_site_option("buddystream_linkedin_consumer_secret"));
    $linkedin->setAccessToken(get_user_meta(1, 'buddystream_linkedin_token',1));
    $linkedin->setAccessTokenSecret(get_user_meta(1, 'buddystream_linkedin_tokensecret',1));
    $connections = $linkedin->getFriends();
   
    foreach($connections as $connection)
    {  
        
       $data = array(
                'user_id'       => 1,
                'social_id'     => $connection->id,
                'first_name'    => strtolower($connection->{'first-name'}),
                'last_name'     => strtolower($connection->{'last-name'}),
                'gender'        => '',
                'bithday'       => '',
                'picture_url'   => $connection->{'picture-url'},
                'profile_url'   => $connection->{'site-standard-profile-request'}->url,
                'type'          => 'linkedin'
               );

      buddystreamAddFriend($data);
    }
    
    
}

if($_GET['reset'] == 'true'){
    delete_user_meta($bp->loggedin_user->id,'buddystream_linkedin_token');
    delete_user_meta($bp->loggedin_user->id,'buddystream_linkedin_tokensecret');
    delete_user_meta($bp->loggedin_user->id,'buddystream_linkedin_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_filtergood');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_filterbad');
}

if (isset($_GET['oauth_token'])) {

      $linkedin = new BuddystreamLinkedin();
      $linkedin->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-linkedin');
      $linkedin->setConsumerKey(get_site_option("buddystream_linkedin_consumer_key"));
      $linkedin->setConsumerSecret(get_site_option("buddystream_linkedin_consumer_secret"));

      $consumer = $linkedin->getConsumer();
      $token    = $consumer->getAccessToken($_GET, $linkedin->getLinkedinToken());

      update_user_meta($bp->loggedin_user->id,'buddystream_linkedin_token', $token->oauth_token);
      update_user_meta($bp->loggedin_user->id,'buddystream_linkedin_tokensecret', $token->oauth_token_secret);
      update_user_meta($bp->loggedin_user->id,'buddystream_linkedin_mention', $token->screen_name);
      update_user_meta($bp->loggedin_user->id,'buddystream_linkedin_synctoac', 1);

      //for other plugins
      do_action('buddystream_linkedin_activated');

  }

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_synctoac', $_POST['buddystream_linkedin_synctoac']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_filtergood', $_POST['buddystream_linkedin_filtergood']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_filterbad', $_POST['buddystream_linkedin_filterbad']);

    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_achievements', $_POST['buddystream_linkedin_achievements']);

    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_linkedin') . '</p>
        </div>';
    }

    //put some options into variables
    $buddystream_linkedin_synctoac       = get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_synctoac',1);
    $buddystream_linkedin_filtergood     = get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_filtergood',1);
    $buddystream_linkedin_filterbad      = get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_filterbad',1);

    //achievements plugin
    $buddystream_linkedin_achievements   = get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_achievements',1);

    if (get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token',1)) {
        echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-linkedin/" method="post">
        <h3>' . __('LinkedIn Settings', 'buddystream_linkedin') . '</h3>';
        ?>

        <?php if (get_site_option('buddystream_linkedin_user_settings_syncbp') == 0) { ?>

        <br/><h5><?php echo __('Synchronize LinkedIn updates to my activity stream?', 'buddystream_linkedin'); ?></h5>
        <input type="radio" name="buddystream_linkedin_synctoac" id="buddystream_linkedin_synctoac" value="1" <?php if ($buddystream_linkedin_synctoac == 1) { echo 'checked'; } ?> />
        <label for="yes"><?php echo __('Yes','buddystream_linkedin'); ?></label>
        
        <input type="radio" name="buddystream_linkedin_synctoac" id="buddystream_linkedin_synctoac" value="0" <?php if ($buddystream_linkedin_synctoac == 0) { echo 'checked'; } ?> />
        <label for="no"><?php echo __('No','buddystream_linkedin'); ?></label>

        <br/>
        <?php } ?>
        <?php if (get_site_option('buddystream_linkedin_user_settings_syncbp') == 0) { ?>

        <br/><h5><?php _e('Filters', 'buddystream_linkedin');?></h5>
        <?php _e('user settings', 'buddystream_linkedin'); ?><br />
        
        <br/><h5><?php echo __('Good Filter (separate words with commas)', 'buddystream_linkedin');?></h5>
        <input type="text" name="buddystream_linkedin_filtergood" value="<?php echo $buddystream_linkedin_filtergood;?>" size="50" />
        
        <br/><h5><?php echo __('Bad Filter (separate words with commas)', 'buddystream_linkedin'); ?></h5>
        <input type="text" name="buddystream_linkedin_filterbad" value="<?php echo $buddystream_linkedin_filterbad;?>" size="50" />

        <?php if(defined('ACHIEVEMENTS_IS_INSTALLED')){ ?>
            <br/><h5><?php echo __( 'Send achievements unlock to my LinkedIn'  , 'buddystream_linkedin' );?></h5>
    		<input type="radio" name="buddystream_linkedin_achievements" id="buddystream_linkedin_achievements" value="1" <?php if($buddystream_linkedin_achievements==1){echo'checked';}?>> <?php echo __('Yes','buddsytream_lang');?><br/>
    		<input type="radio" name="buddystream_linkedin_achievements" id="buddystream_linkedin_achievements" value="0" <?php if($buddystream_linkedin_achievements==0){echo'checked';}?>> <?php echo __('No','buddsytream_lang');?><br/>
    	<?php } ?>

        <?php } ?>

        <br/><input type="submit" value="<?php echo __('Save settings', 'buddystream_linkedin'); ?>">
        </form>
        
        <?php
         }else{
             if(buddystreamCheckNetwork("http://www.linkedin.com")){
                 echo '<h3>' . __('LinkedIn setup</h3>
                 You may setup you linkedIn intergration over here.<br/>
                 Before you can begin using LinkedIn with this site you must authorize on LinkedIn by clicking the link below.', 'buddystream_linkedin') . '<br/><br/>';

                 //buddystream linkedin rclass
                 $linkedin = new BuddystreamLinkedin;
                 $linkedin->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-linkedin');
                 $linkedin->setConsumerKey(get_site_option("buddystream_linkedin_consumer_key"));
                 $linkedin->setConsumerSecret(get_site_option("buddystream_linkedin_consumer_secret"));                 
                 $redirectUrl = $linkedin->getRedirectUrl();
                 
                 if($redirectUrl){
                 
                 echo '<a href="' . $linkedin->getRedirectUrl() . '">' . __('Click here to start authorization', 'buddystream_linkedin') . '</a><br/><br/>';
                 }else{
                     _e('There is a problem with the authentication service at this moment please come back in a while.','buddystream_linkedin');
                 }
             }else{
               _e('LinkedIn is offline currently please come back in a while.','buddystream_linkedin');
             }
      }