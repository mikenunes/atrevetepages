<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url();?>/buddystream/extentions/default/slickswitch.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php echo buddystreamTabloader('linkedin'); ?>

<?php

$arraySwitches = array(
    'buddystream_linkedin_user_settings_syncbp',
    'buddystream_linkedin_hide_sitewide',
    'buddystream_linkedin_share',
    'buddystream_linkedin_share_counter'
);

  if ($_POST) {
      update_site_option('buddystream_linkedin_consumer_key', trim(strip_tags($_POST['buddystream_linkedin_consumer_key'])));
      update_site_option('buddystream_linkedin_consumer_secret', trim(strip_tags($_POST['buddystream_linkedin_consumer_secret'])));
      update_site_option('buddystream_linkedin_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_linkedin_user_settings_maximport']))));
      
      foreach($arraySwitches as $switch){
         update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
      }

      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_linkedin') . '</div>';
   }
?>

        <div class="buddystream_info_box">
         <?php echo str_replace("#ROOTDOMAIN",$bp->root_domain,__('linkedin settings description', 'buddystream_linkedin')); ?>
        </div>

      <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">
            
            <tr class="header">
                <td colspan="2"><?php _e('Linkedin API','buddystream_linkedin');?></td>
            </tr>
              
            <tr>
                <td><?php _e('API key:', 'buddystream_linkedin');?></td>
                <td><input type="text" name="buddystream_linkedin_consumer_key" value="<?php echo get_site_option('buddystream_linkedin_consumer_key'); ?>" size="50" /></td>
             </tr>

              <tr class="odd">
                  <td><?php _e('Api secret:', 'buddystream_linkedin');?></td>
                   <td><input type="text" name="buddystream_linkedin_consumer_secret" value="<?php echo get_site_option('buddystream_linkedin_consumer_secret'); ?>" size="50" /></td>
              </tr>

              <?php if(get_site_option('buddystream_linkedin_consumer_key') && get_site_option('buddystream_linkedin_consumer_secret')){ ?>

            <tr class="header">
                <td colspan="2"><?php _e('User options','buddystream_linkedin');?></td>
            </tr>

            <tr>
                <td><?php _e( 'Hide linkedIn on the sidewide activity stream?', 'buddystream_linkedin' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_linkedin_hide_sitewide" id="buddystream_linkedin_hide_sitewide"/></td>
            </tr>

            <tr valign="top" class="odd">
                <td><?php _e('Allow users to sync LinkedIn to your website?', 'buddystream_linkedin');?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_linkedin_user_settings_syncbp" id="buddystream_linkedin_user_settings_syncbp"/></td>
            </tr>

            <tr valign="top">
                <td><?php _e('Maximum LinkedIn updates to be imported per user, per day (empty = unlimited tweets import):', 'buddystream_linkedin'); ?></th>
                <td><input type="text" name="buddystream_linkedin_user_settings_maximport" value="<?php echo get_site_option('buddystream_linkedin_user_settings_maximport'); ?>" size="5" /></td>
            </tr>
            
            <tr class="header">
                <td colspan="2"><?php _e('Extra options','buddystream_linkedin');?></td>
            </tr>
            
            <tr>
                <td><?php _e( 'Show LinkedIn share button?', 'buddystream_linkedin' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_linkedin_share" id="buddystream_linkedin_share"/></td>
            </tr>
            
             <tr class="odd">
                <td><?php _e('Show counter?', 'buddystream_linkedin'); ?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_linkedin_share_counter" id="buddystream_linkedin_share_counter"/></td>
            </tr>
            
           
            <?php } ?>

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