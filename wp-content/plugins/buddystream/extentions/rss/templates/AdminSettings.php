<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url();?>/buddystream/extentions/default/slickswitch.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php echo buddystreamTabLoader('rss'); ?>

<?php
 $arraySwitches = array(
        'buddystream_rss_hide_sitewide'
    );

  if ($_POST) {
      update_site_option('buddystream_rss_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_rss_user_settings_maximport']))));

       foreach($arraySwitches as $switch){
        update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
      }
      
      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_rss') . '</div>';
   }
?>

        <div class="buddystream_info_box">
              <?php 
              _e('rss settings description','buddystream_rss');
              //echo __('A YouTube API Key or connection is NOT required to get the users video histories. '); ?>
        </div>

      <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">          
            
            <tr class="header">
                <td colspan="2"><?php _e('User options', 'buddystream_rss');?></td>
            </tr>

            <tr>
                <td><?php _e( 'Hide Rss items on the sitewide activity stream?', 'buddystream_rss' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_rss_hide_sitewide" id="buddystream_rss_hide_sitewide"/></td>
            </tr>

            <tr class="odd">
                <td><?php _e('Maximum number of items to import per user, per day (empty - unlimited):', 'buddystream_rss'); ?></td>
                <td><input type="text" name="buddystream_rss_user_settings_maximport" value="<?php echo get_site_option('buddystream_rss_user_settings_maximport'); ?>" size="5" /></td>
            </tr>
            
        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes','buddystream_rss') ?>" /></p>
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