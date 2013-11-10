<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabloader('linkedin'); ?>

<div class="buddystream_info_box"><?php _e('linkedin statitics description','buddystream_linkedin');?></div>

<table class="buddystream_table" cellspacing="0">
      <tr class="header">
          <td><?php echo __('Statistics', 'buddystream_linkedin'); ?></td>
          <td></td>
      </tr>
 
    <?php
       $count_users             = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_buddystream_linkedinusers  = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_linkedin_token';")));
       $perc_buddystream_linkedinusers   = round(($count_buddystream_linkedinusers / $count_users) * 100);
       $count_items            = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='linkedin';")));
       $count_activity          = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_tweetupdates       = round(($count_items / $count_activity * 100));
       $average_items_day      = round($count_items / 24);
       $average_items_week     = $average_items_day * 7;
       $average_items_month    = $average_items_day * 30;
       $average_items_year     = $average_items_day * 365;

       echo "
        <tr>
            <td>".__('Amount of users:','buddystream_linkedin')."</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Amount of user LinkedIn intergration:','buddystream_linkedin')."</td>
            <td>" . $count_buddystream_linkedinusers . "</td>
        </tr>
        <tr>
            <td>".__('Percentage of users LinkedIn using intergration:','buddystream_linkedin')."</td>
            <td>" . $perc_buddystream_linkedinusers . "%</td>
        </tr>
        <tr class='odd'>
            <td>".__('Amount of activity updates:','buddystream_linkedin')."</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>".__('Amount of items updates:','buddystream_linkedin')."</td>
            <td>" . $count_items . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Percentage of items in activity updates:','buddystream_linkedin')."</td>
            <td>" . $perc_tweetupdates . "%</td>
        </tr>
        <tr>
            <td>".__('Average items import per day:','buddystream_linkedin')."</td>
            <td>" . $average_items_day . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Average items import per week:','buddystream_linkedin')."</td>
            <td>" . $average_items_week . "</td>
        </tr>
        <tr>
            <td>".__('Average items import per month:','buddystream_linkedin')."</td>
            <td>" . $average_items_month . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Average items import per year:','buddystream_linkedin')."</td>
            <td>" . $average_items_year . "</td>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

