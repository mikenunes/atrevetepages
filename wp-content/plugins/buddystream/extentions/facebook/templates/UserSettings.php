<?php

if($_GET['reset'] == 'true'){
    delete_user_meta($bp->loggedin_user->id,'facestream_session_key');
    delete_user_meta($bp->loggedin_user->id,'facestream_user_id');
    delete_user_meta($bp->loggedin_user->id,'facestream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'facestream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'facestream_filtermentions');
    delete_user_meta($bp->loggedin_user->id, 'facestream_filtergood');
    delete_user_meta($bp->loggedin_user->id, 'facestream_filterbad');
}

if (isset($_GET['code'])) {

  $facebook = new BuddystreamFacebook();
  $facebook->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-facebook');
  $facebook->setApplicationId(get_site_option("facestream_application_id"));
  $facebook->setApplicationSecret(get_site_option("facestream_application_secret"));
  $facebook->setCode($_GET['code']);

  $accessToken = $facebook->requestAccessToken();
  $facebook->setAccessToken($accessToken);

  update_user_meta($bp->loggedin_user->id,'facestream_session_key',$accessToken);
  update_user_meta($bp->loggedin_user->id,'facestream_user_id',$facebook->requestUser()->id);
  update_user_meta($bp->loggedin_user->id,'facestream_synctoac',1);

  //for other plugins
  do_action('buddystream_facebook_activated');
}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'facestream_synctoac', $_POST['facestream_synctoac']);
    update_user_meta($bp->loggedin_user->id, 'facestream_filtermentions', $_POST['facestream_filtermentions']);
    update_user_meta($bp->loggedin_user->id, 'facestream_filtergood', $_POST['facestream_filtergood']);
    update_user_meta($bp->loggedin_user->id, 'facestream_filterbad', $_POST['facestream_filterbad']);
    
    //achievement plugin
    update_user_meta($bp->loggedin_user->id, 'facestream_achievements', $_POST['facestream_achievements']);

    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_facebook') . '</p>
        </div>';
    }

    //put some options into variables
    $facestream_filtermentions  = get_user_meta($bp->loggedin_user->id, 'facestream_filtermentions',1);
    $facestream_filtergood      = get_user_meta($bp->loggedin_user->id, 'facestream_filtergood',1);
    $facestream_filterbad       = get_user_meta($bp->loggedin_user->id, 'facestream_filterbad',1);
    $facestream_synctoac        = get_user_meta($bp->loggedin_user->id, 'facestream_synctoac',1);

    //achievement plugin
    $facestream_achievements    = get_user_meta($bp->loggedin_user->id, 'facestream_achievements',1);

    if (get_usermeta($bp->loggedin_user->id, 'facestream_session_key')) {
        echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . BP_SETTINGS_SLUG .'/buddystream-facebook/" method="post">
        <h3>' . __('Facebook Settings', 'buddystream_facebook') . '</h3>';
    if (get_site_option('facestream_user_settings_syncbp') == 0) { ?>

    <h5><?php _e('Synchronize Facebook to my activity stream:', 'buddystream_facebook'); ?></h5>
    <input type="radio" name="facestream_synctoac" value="1" <?php if ($facestream_synctoac == 1) { echo 'checked'; } ?>>
    <label for="yes"><?php _e('Yes','buddsytream_lang');?> </label><br/>

    <input type="radio" name="facestream_synctoac" value="0" <?php if ($facestream_synctoac == 0) { echo 'checked'; } ?>>
    <label for="no"><?php _e('No','buddsytream_lang');?></label><br/>
    <?php } ?>
    
    <?php if (get_site_option('facestream_user_settings_syncbp') == 0) { ?>

    <br/><h5><?php _e('Filters', 'buddystream_facebook');?></h5>
    <?php _e('facebook user filters description','buddystream_facebook'); ?>
    <br/><br/>

    <h5><?php _e('Good Filter (separate words with commas)', 'buddystream_facebook');?></h5>
    <input type="text" name="facestream_filtergood" value="<?php echo $facestream_filtergood;?>" size="50" /><br/><br/>

    <h5><?php _e('Bad Filter (separate words with commas)', 'buddystream_facebook');?></h5>
    <input type="text" name="facestream_filterbad" value="<?php echo $facestream_filterbad;?>" size="50" /><br/>

    <?php if(defined('ACHIEVEMENTS_IS_INSTALLED')){ ?>
        <br/><h5><?php _e( 'Send achievements unlock to my facebook'  , 'buddystream_facebook' );?></h5>
        <input type="radio" name="facestream_achievements" value="1" <?php if(get_user_meta($bp->loggedin_user->id, 'facestream_achievements',1)==1){echo'checked';}?>> <label for="yes"><?php _e('Yes','buddsytream_lang');?></label><br/>
        <input type="radio" name="facestream_achievements" value="0" <?php if(get_user_meta($bp->loggedin_user->id, 'facestream_achievements',1)==0){echo'checked';}?>> <label for="no"><?php _e('No','buddsytream_lang');?></label><br/>
    <?php }
    }
        ?><br/>
           <input type="submit" value="<?php _e('Save settings', 'buddystream_facebook'); ?>" />
   </form>
   
   <br/><strong><a href="?reset=true"><?php _e('Remove my facebook settings', 'buddystream_facebook'); ?></a></strong>
   
<?php

      } else {
          if(buddystreamCheckNetwork("http://www.facebook.com")){
              echo '<b>' . __('Permission', 'buddystream_facebook') . '</b><br/>' . 
              _e('facebook user persmission description','buddystream_facebook');

              //facebook class
              $facebook = new Buddystreamfacebook;
              $facebook->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-facebook');
              $facebook->setApplicationId(get_site_option("facestream_application_id"));
              echo '<a href="' . $facebook->getRedirectUrl() . '">' . __('Authorize with facebook', 'buddystream_facebook') . '</a><br/><br/>';
          }else{
              _e('Facebook is currently offline please come back in a while.','buddystream_facebook');
          }
      }
?>