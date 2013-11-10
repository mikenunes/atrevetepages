<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />

<?php echo buddystreamTabloader('soundcloud'); ?>

<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          
          delete_user_meta($_GET['user_id'], "soundcloud_access_token");

          echo ' <div id="message" class="buddystream_info_box_green fade">
          <p>' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_soundcloud') . '</p>
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="buddystream_info_box_green fade">
            <p>' . __('Are you sure ?', 'buddystream_soundcloud') . '
            <a href="?page=buddystream_soundcloud&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="buddystream_soundcloud">No</a></p>
            </div>';
      }
  }
}
?>

<div class="buddystream_info_box">
<?php _e('soundcloud users description','buddystream_soundcloud'); ?>
</div>

<table class="buddystream_table" cellspacing="0">
  <tr class="header">
      <td></td>
      <td><?php _e('Username', 'buddystream_soundcloud'); ?></td>
      <td><?php _e('E-mail', 'buddystream_soundcloud'); ?></td>
      <td><?php _e('Tracks imported', 'buddystream_soundcloud'); ?></td>
      <td><?php _e('Reset user', 'buddystream_soundcloud'); ?></td>
  </tr>
  
<?php
//get all users who have set-up there soundcloud
          $rowClass = "even";
          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='soundcloud_access_token';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                //  get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];

                //  count imported tracks
                  $imported_tracks = count($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='soundcloud';")));
                  echo "
                    <tr class='".$rowClass."'>
                        <td>" . get_avatar($user_data->ID, 32) . "</td>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td><a href='mailto:" . $user_data->user_email . "' title='E-mail: " . $user_data->user_email . "'>" . $user_data->user_email . "</a></td>
                        <td>" . $imported_tracks . "</td>
                        <td><a href='?page=buddystream_soundcloud&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></td>
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