<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/slickswitch.css';?>" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php echo buddystreamTabLoader('facebook'); ?>
<?php

$arraySwitches = array(
    'facestream_user_settings_syncbp',
    'facestream_user_settings_syncupdatesbp',
    'facestream_user_settings_synclinksbp',
    'facestream_user_settings_syncvideosbp',
    'facestream_user_settings_syncphotosbp',
    'buddystream_facebook_hide_sitewide',
    'buddystream_facebook_like',
    'buddystream_facebook_like_faces',
    'buddystream_facebook_album'
);

  if ($_POST) {

      update_site_option('facestream_application_id', trim(strip_tags($_POST['facestream_application_id'])));
      update_site_option('facestream_application_secret', trim(strip_tags($_POST['facestream_application_secret'])));
      update_site_option('facestream_user_settings_maximport', trim(strip_tags(strtolower($_POST['facestream_user_settings_maximport']))));
      update_site_option('buddystream_facebook_like_type', trim(strip_tags(strtolower($_POST['buddystream_facebook_like_type']))));
      update_site_option('buddystream_facebook_like_scheme', trim(strip_tags(strtolower($_POST['buddystream_facebook_like_scheme']))));
      
      foreach($arraySwitches as $switch){
        update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
      }
      
      //backward compatable
      $user_metas_old = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->usermeta where meta_key='facestream_session_key';"));
      foreach ($user_metas_old as $user_meta_old) {
        update_user_meta($user_meta_old->user_id, 'facestream_stamp',date('dmYHis'));
      }
      //backward compatable
      
      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_facebook') . '</div>';
   }
?>
    
      <div class="buddystream_info_box">  
          <?php _e('facebook settings description','buddystream_facebook'); ?>
          <a href="http://developers.facebook.com/setup/" target="_blanc" rel="external" title="Facebook">http://developers.facebook.com/setup/</a>
      </div>
        
      <form method="post" action="">
          
          <table class="buddystream_table" cellspacing="0">
            <tr class="header">
                <td colspan="2"><?php _e('Facebook API', 'buddystream_facebook');?></td>
            </tr>
              
              <tr  <? if(get_site_option('facestream_application_id')==""){ echo 'class="buddystream_error_box"'; } ?>>
                <td scope="row"><?php _e('Application ID:', 'buddystream_facebook');?></td>
                   <td>
                       <input type="text" name="facestream_application_id" value="<?php echo get_site_option('facestream_application_id'); ?>" size="50" />
                   </td>
              </tr>
           
              <tr class="odd" <? if(get_site_option('facestream_application_secret')==""){ echo 'class="buddystream_error_box"'; } ?>>
                  <td scope="row"><?php _e('Application secret:', 'buddystream_facebook');?></td>
                    <td>
                        <input type="text" name="facestream_application_secret" value="<?php echo get_site_option('facestream_application_secret'); ?>" size="50" />
                    </td>
              </tr>

              <? if(get_site_option('facestream_application_secret')!="" && get_site_option('facestream_application_id')!="") { ?>

                <tr class="header">
                    <td colspan="2"><?php _e('User options', 'buddystream_facebook');?></td>
                </tr>

                <tr>
                    <td><?php _e( 'Hide Facebook items on the sidewide activity stream?', 'buddystream_facebook' );?></td>
                    <td>
                        <input class="switch icons" type="checkbox" name="buddystream_facebook_hide_sitewide" id="buddystream_facebook_hide_sitewide"/>
                    </td>
                </tr>
                
                <tr class="odd">
                    <td><?php _e( 'Show  Facebook album on user profile page?', 'buddystream_facebook' );?></td>
                    <td><input class="switch icons" type="checkbox" name="buddystream_facebook_album" id="buddystream_facebook_album"/></td>
                </tr>
                
                <tr>
                    <td><?php _e('Allow users to sync to BuddyPress?', 'buddystream_facebook');?></td>
                    <td><input class="switch icons" type="checkbox" name="facestream_user_settings_syncbp" id="facestream_user_settings_syncbp"/></td>
                </tr>

                <tr class="odd">
                    <td><?php _e( 'Allow users to sync updates to BuddyPress?', 'buddystream_facebook' );?></td>
                    <td><input class="switch icons" type="checkbox" name="facestream_user_settings_syncupdatesbp" id="facestream_user_settings_syncupdatesbp"/></td>
                </tr>

                <tr>
                    <td><?php _e( 'Allow users to sync links to BuddyPress?', 'buddystream_facebook' );?></td>
                    <td><input class="switch icons" type="checkbox" name="facestream_user_settings_synclinksbp" id="facestream_user_settings_synclinksbp"/></td>
                </tr>

                <tr class="odd">
                    <td><?php _e( 'Allow users to sync photos to BuddyPress?', 'buddystream_facebook' );?></td>
                    <td><input class="switch icons" type="checkbox" name="facestream_user_settings_syncphotosbp" id="facestream_user_settings_syncphotosbp"/></td>
                </tr>

                <tr>
                    <td><?php _e( 'Allow users to sync videos to BuddyPress?', 'buddystream_facebook' );?></td>
                    <td><input class="switch icons" type="checkbox" name="facestream_user_settings_syncvideosbp" id="facestream_user_settings_syncvideosbp"/></td>
                </tr>

                <tr class="odd">
                    <td><?php _e('Maximum amount of Facebook items (total) to be imported per user, per day (empty = unlimited):', 'buddystream_facebook'); ?></td>
                    <td>
                        <input type="text" name="facestream_user_settings_maximport" value="<?php echo get_site_option('facestream_user_settings_maximport'); ?>" size="5" />
                    </td>
                </tr>
                
                <tr class="header">
                    <td colspan="2"><?php _e('Extra options', 'buddystream_facebook');?></td>
                </tr>
                
                 <tr >
                    <td><?php _e( 'Enable like buttons on the activity stream?', 'buddystream_facebook' );?></td>
                    <td>
                        <input class="switch icons" type="checkbox" name="buddystream_facebook_like" id="buddystream_facebook_like"/>
                    </td>
                </tr>
                
                
                <tr class="odd">
                    <td><?php _e('Show faces?', 'buddystream_facebook'); ?></td>
                    <td>
                        <input class="switch icons" type="checkbox" name="buddystream_facebook_like_faces" id="buddystream_facebook_like_faces"/>
                    </td>
                </tr>
                
               <tr >
                    <td><?php _e('Type:', 'buddystream_facebook'); ?></td>
                    <td>
                        <select name="buddystream_facebook_like_type">
                            <option value="standard" <?php if(get_site_option('buddystream_facebook_like_type') == "standard"){echo "selected";}?>><?php _e('standard','buddystream_facebook');?></option>
                            <option value="button_count" <?php if(get_site_option('buddystream_facebook_like_type') == "button_count"){echo "selected";}?>><?php _e('button count','buddystream_facebook');?></option>
                            <option value="box_count" <?php if(get_site_option('buddystream_facebook_like_type') == "box_count"){echo "selected";}?>><?php _e('box count','buddystream_facebook');?></option>
                        </select>
                    </td>
                </tr>
                
                <tr class="odd">
                    <td><?php _e('Color scheme:', 'buddystream_facebook'); ?></td>
                    <td>
                        <select name="buddystream_facebook_like_scheme">
                            <option value="light" <?php if(get_site_option('buddystream_facebook_like_scheme') == "light"){echo "selected";}?>><?php _e('light','buddystream_facebook');?></option>
                            <option value="dark" <?php if(get_site_option('buddystream_facebook_like_scheme') == "dark"){echo "selected";}?>><?php _e('dark','buddystream_facebook');?></option>
                        </select>
                    </td>
                </tr>
                
                
            <? } ?>
                
        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes','buddystream_facebook') ?>" /></p>
    </form>

<script type="text/javascript">
    $(".switch").slickswitch();
</script>

<?php
foreach($arraySwitches as $switch){
     if(get_site_option($switch)){
        echo'
        <script>
            $("#'.$switch.'").slickswitch("toggleOn"); 
        </script>
        ';
     }else{
        echo'
        <script>
            $("#'.$switch.'").slickswitch("toggleOff"); 
        </script>
        ';
     }
}
?>