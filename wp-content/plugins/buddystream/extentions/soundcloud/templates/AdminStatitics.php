<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />

<?php echo buddystreamTabloader('soundcloud'); ?>

<div class="buddystream_info_box"><?php _e('soundcloud statitics description','buddystream_soundcloud');?></div>

<table class="buddystream_table" cellspacing="0">
    <tr class="header">
        <td><?php echo __('Statistics', 'buddystream_soundcloud'); ?></td>
        <td></td>
    </tr>

    <?php
       $count_users             = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_soundcloudusers  = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='soundcloud_access_token';")));
       $perc_soundcloudusers   = round(($count_soundcloudusers / $count_users) * 100);
       $count_tracks            = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='soundcloud';")));
       $count_activity          = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_trackupdates       = round(($count_tracks / $count_activity * 100));
       $average_tracks_day      = round($count_tracks / 24);
       $average_tracks_week     = $average_tracks_day * 7;
       $average_tracks_month    = $average_tracks_day * 30;
       $average_tracks_year     = $average_tracks_day * 365;

       echo "
        <tr>
            <td>".__('Amount of users:','buddystream_soundcloud')."</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr>
            <td>".__('Amount of user Soundcloud intergration:','buddystream_soundcloud')."</td>
            <td>" . $count_soundcloudusers . "</td>
        </tr>
        <tr>
            <td>".__('Percentage of users Soundcloud using intergration:','buddystream_soundcloud')."</td>
            <td>" . $perc_soundcloudusers . "%</td>
        </tr>
        <tr>
            <td>".__('Amount of activity updates:','buddystream_soundcloud')."</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>".__('Amount of tracks updates:','buddystream_soundcloud')."</td>
            <td>" . $count_tracks . "</td>
        </tr>
        <tr>
            <td>".__('Percentage of tracks in activity updates:','buddystream_soundcloud')."</td>
            <td>" . $perc_trackupdates . "%</td>
        </tr>
        <tr>
            <td>".__('Average tracks import per day:','buddystream_soundcloud')."</td>
            <td>" . $average_tracks_day . "</td>
        </tr>
        <tr>
            <td>".__('Average tracks import per week:','buddystream_soundcloud')."</td>
            <td>" . $average_tracks_week . "</td>
        </tr>
        <tr>
            <td>".__('Average tracks import per month:','buddystream_soundcloud')."</td>
            <td>" . $average_tracks_month . "</td>
        </tr>
        <tr>
            <td>".__('Average tracks import per year:','buddystream_soundcloud')."</td>
            <td>" . $average_tracks_year . "</td>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

