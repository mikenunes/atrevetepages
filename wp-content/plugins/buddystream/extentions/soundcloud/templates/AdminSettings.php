<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url();?>/buddystream/extentions/default/slickswitch.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php echo buddystreamTabloader('soundcloud'); ?>

<?php

$arraySwitches = array(
    'buddystream_soundcloud_hide_sitewide',
    'buddystream_soundcloud_tracks'
);

  if ($_POST) {
      update_site_option('soundcloud_client_id', trim(strip_tags($_POST['soundcloud_client_id'])));
      update_site_option('soundcloud_client_secret', trim(strip_tags($_POST['soundcloud_client_secret'])));
      update_site_option('soundcloud_user_settings_maximport', trim(strip_tags(strtolower($_POST['soundcloud_user_settings_maximport']))));
     
      foreach($arraySwitches as $switch){
        update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
      }
     
      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_soundcloud') . '</div>';
   }
?>


        <div class="buddystream_info_box">
         <?php echo str_replace("#ROOTDOMAIN",$bp->root_domain."/?buddystream_auth=soundcloud",__('soundcloud settings description','buddystream_soundcloud')); ?>
      </div>

      <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">
            
            <tr class="header">
                <td colspan="2"><?php _e('Soundcloud API', 'buddystream_soundcloud');?></td>
            </tr>
              
            <tr valign="top" <? if(get_site_option('soundcloud_client_id')==""){ echo 'class="buddystream_error_box"'; }?>>
                <td><?php _e('Client ID:', 'buddystream_soundcloud');?></td>
                <td><input type="text" name="soundcloud_client_id" value="<?php echo get_site_option('soundcloud_client_id'); ?>" size="50" /></td>
              </tr>

              <tr valign="top" <? if(get_site_option('soundcloud_client_secret')==""){ echo 'class="buddystream_error_box"'; }?> class="odd">
                  <td><?php _e('Client secret key:', 'buddystream_soundcloud');?></td>
                  <td><input type="text" name="soundcloud_client_secret" value="<?php echo get_site_option('soundcloud_client_secret'); ?>" size="50" /></td>
              </tr>

              <? if(get_site_option('soundcloud_client_id') && get_site_option('soundcloud_client_secret')){ ?>

            <tr class="header">
                <td colspan="2"><?php _e('User options', 'buddystream_soundcloud');?></td>
            </tr>

            <tr valign="top">
                <td><?php _e( 'Hide soundcloud on the sidewide activity stream?', 'buddystream_soundcloud' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_soundcloud_hide_sitewide" id="buddystream_soundcloud_hide_sitewide"/></td>
            </tr>
            
            <tr class="odd">
                <td><?php _e( 'Show  Soundcloud tracks (album) on user profile page?', 'buddystream_soundcloud' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_soundcloud_tracks" id="buddystream_soundcloud_tracks"/></td>
            </tr>

            <tr valign="top">
                <td><?php _e('Maximum tracks to be imported per user, per day (empty = unlimited tracks import):', 'buddystream_soundcloud'); ?></td>
                <td>
                    <input type="text" name="soundcloud_user_settings_maximport" value="<?php echo get_site_option('soundcloud_user_settings_maximport'); ?>" size="5" />
                </td>
            </tr>

            <? } ?>

        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
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