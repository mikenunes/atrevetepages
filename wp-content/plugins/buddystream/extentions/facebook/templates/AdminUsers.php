<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('facebook'); ?>

<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          delete_user_meta($_GET['user_id'], "facestream_lastupdate");
          delete_user_meta($_GET['user_id'], "facestream_counterdate");
          delete_user_meta($_GET['user_id'], "facestream_tokensecret");
          delete_user_meta($_GET['user_id'], "facestream_synctoac");
          delete_user_meta($_GET['user_id'], "facestream_counterdate");
          delete_user_meta($_GET['user_id'], "facestream_daycounter");
          delete_user_meta($_GET['user_id'], "facestream_filtergood");
          delete_user_meta($_GET['user_id'], "facestream_filterbad");
          delete_user_meta($_GET['user_id'], "facestream_user_id");
          delete_user_meta($_GET['user_id'], "facestream_session_key");

          echo ' <div id="message" class="buddystream_info_box_green fade">
          ' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_facebook') . '
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="buddystream_info_box_green fade">
            ' . __('Are you sure ?', 'buddystream_facebook') . '
            <a href="?page=buddystream_facebook&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=buddystream_facebook&settings=users">No</a>
            </div>';
      }
  }
}
?>

<div class="buddystream_info_box">
<?php _e('facebook users description','buddystream_facebook'); ?>
</div>

<table class="buddystream_table" cellpadding="0" cellspacing="0">
    <tr class="header">
        <td width="30"></td>
        <td>Username</td>
        <td>E-mail</td>
        <td><?php _e('Facebook', 'buddystream_facebook'); ?></td>
        <td><?php _e('Items imported', 'buddystream_facebook'); ?></td>
        <td><?php _e('Reset user', 'buddystream_facebook'); ?></td>
    </tr>

        <?php
        $rowClass = "even";
        
          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='facestream_session_key';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                  //get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id='$user_meta->user_id';"));
                  $user_data = $user_data[0];
                  $facebook_profile = get_user_meta($user_data->ID, 'facestream_user_id',1);

                  //count imported items
                  if(get_user_meta($user_data->ID,'facestream_synctoac',1)){
                      $imported_items = count($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id='$user_meta->user_id' AND type='facebook';")));
                  }else{
                      $imported_items = __('Import turned off by user','buddystream_facebook');
                  }
                  
                  echo "
                    <tr class='".$rowClass."'>
                        <td>" . get_avatar($user_data->ID, 32) . "</td>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td><a href='mailto:" . $user_data->user_email . "' title='E-mail: " . $user_data->user_email . "'>" . $user_data->user_email . "</a></td>
                        <td><a href='http://www.facebook.com/profile.php?id=" . $facebook_profile . "' title='http://www.facebook.com/profile.php?id=" . $facebook_profile . "' target='_blanc'>http://www.facebook.com/profile.php?id=" . $facebook_profile . "</a></td>
                        <td>" . $imported_items . "</td>
                        <td><a href='?page=buddystream_facebook&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></td>
                     </tr>
                    ";
                  
                  if($rowClass == "even"){
                      $rowClass = "odd";
                  }else{
                      $rowClass = "even";
                  }
              }
          }
        ?>
    </tbody>
  </table>              