<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabloader('linkedin'); ?>

<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_synctoac");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_lastupdate");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_checkboxon");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_counterdate");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_tokensecret");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_filtermentions");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_synctoac");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_counterdate");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_checkboxon");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_daycounter");;
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_filtergood");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_filterbad");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_filtertoactivity");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_filtertolinkedin");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_profilelink");
          delete_user_meta($_GET['user_id'], "buddystream_linkedin_token");

          echo ' <div id="message" class="buddystream_info_box fade">
          ' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_linkedin') . '
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="buddystream_info_box_green fade">
            ' . __('Are you sure ?', 'buddystream_linkedin') . '
            <a href="?page=buddystream_linkedin&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=buddystream_linkedin&settings=users">No</a>
            </div>';
      }
  }
}
?>
<div class="buddystream_info_box">
<?php _e('linkedin users description','buddystream_linkedin');?></div>

<table class="buddystream_table" cellspacing="0">
   <tr class="header">
      <td></th>
      <td><?php _e('Username', 'buddystream_linkedin'); ?></td>
      <td><?php _e('Email', 'buddystream_linkedin'); ?></td>
      <td><?php _e('Items imported', 'buddystream_linkedin'); ?></td>
      <td><?php _e('Reset user', 'buddystream_linkedin'); ?></td>
  </tr>
  
<?php
//get all users who have set-up there tweetstream
          $rowClass = "even";
          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_linkedin_token';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                  //get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];
                  
                  //count imported items
                  if(get_user_meta($user_meta->user_id, 'buddystream_linkedin_synctoac', 1)) {
                      $imported_items = count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='linkedin';"));
                  } else {
                      $imported_items = "Import turned off by user";
                  }
                  
                  echo "
                    <tr class='".$rowClass."'>
                        <td>" . get_avatar($user_data->ID, 32) . "</td>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td><a href='mailto:" . $user_data->user_email . "' title='E-mail: " . $user_data->user_email . "'>" . $user_data->user_email . "</a></td>
                        <td>" . $imported_items . "</td>
                        <td><a href='?page=buddystream_linkedin&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></td>
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
</div>

              
              