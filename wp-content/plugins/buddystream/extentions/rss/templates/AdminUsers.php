<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />

<?php echo buddystreamTabLoader('rss'); ?>


<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          delete_user_meta($_GET['user_id'], "buddystream_rss_feeds");

          echo ' <div id="message" class="buddystream_info_box_green fade">
          ' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_rss') . '
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="buddystream_info_box_green fade">
            ' . __('Are you sure ?', 'buddystream_rss') . '
            <a href="?page=buddystream_rss&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=buddystream_rss&settings=users">No</a>
            </div>';
      }
  }
}
?>

<div class="buddystream_info_box">
<?php
echo __('rss users description', 'buddystream_rss');

//echo __('Below is a list of users whom are using rss.You may reset their rss settings here. <br>
//<b>Note: </b>  When you reset a user, they will need to input their rss username again.
//Any rss video history that has already been imported WILL NOT be deleted. ', 'buddystream_rss');
?></div>


<table class="buddystream_table" cellspacing="0">

  <tr class="header">
      <td width="30"></td>
      <td><?php _e('Username', 'buddystream_rss'); ?></td>
      <td><?php _e('Item history', 'buddystream_rss'); ?></td>
      <td><?php _e('Reset user', 'buddystream_rss'); ?></td>
  </tr>
  
<?php
//get all users who have set-up there rss
          $rowClass = "even";
          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystreams_rss_feeds';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                //  get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];
                  
                //  count imported history
                  $imported_history = count($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='rss';")));
                  echo "
                    <tr class='".$rowClass."'>
                        <td>" . get_avatar($user_data->ID, 32) . "</td>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td>" . $imported_history . "</td>
                        <td><a href='?page=buddystream_rss&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></td>
                   </tr>
                    ";
              }
              
              if($rowClass == "even"){
                  $rowClass = "odd";
              }else{
                  $rowClass = "even";
              }
          }
        ?>
      </tbody>
  </table>