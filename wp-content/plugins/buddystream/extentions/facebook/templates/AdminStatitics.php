<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('facebook'); ?>

<div class="buddystream_info_box">
    <?php _e('facebook statitics descriptiption','buddystream_facebook'); ?>
</div>

<table class="buddystream_table" cellspacing="0" cellpadding="0">
    
        <tr class="header">
            <td><?php _e('Statistics', 'buddystream_facebook'); ?></td>
            <td></td>
        </tr>
    
    <?php
       $count_users             = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_facestreamusers   = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='facestream_session_key';")));
       $perc_facestreamusers    = round(($count_facestreamusers / $count_users) * 100);
       $count_facebook          = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='facebook';")));
       $count_activity          = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_facebookupdates    = round(($count_facebook / $count_activity * 100));
       $average_facebook_day    = round($count_facebook / 24);
       $average_facebook_week   = $average_facebook_day * 7;
       $average_facebook_month  = $average_facebook_day * 30;
       $average_facebook_year   = $average_facebook_day * 365;

       echo "
        <tr>
            <td>".__('Amount of users:','buddystream_facebook')."</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Amount of user using Facebook integration:','buddystream_facebook')."</td>
            <td>" . $count_facestreamusers . "</td>
        </tr>
        <tr>
            <td>".__('Percentage of users using Facebook integration:','buddystream_facebook')."</td>
            <td>" . $perc_facestreamusers . "%</td>
        </tr>
        <tr class='odd'>
            <td>".__('Amount of activity updates:','buddystream_facebook')."</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>".__('Amount of Facebook items:','buddystream_facebook')."</td>
            <td>" . $count_facebook . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Percentage of Facebook items:','buddystream_facebook')."</td>
            <td>" . $perc_facebookupdates . "%</td>
        </tr>
        <tr>
            <td>".__('Average number of Facebook items imported per day:','buddystream_facebook')."</td>
            <td>" . $average_facebook_day . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Average number of Facebook items imported per week:','buddystream_facebook')."</td>
            <td>" . $average_facebook_week . "</td>
        </tr>
        <tr>
            <td>".__('Average number Facebook items imported per month:','buddystream_facebook')."</td>
            <td>" . $average_facebook_month . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Average number Facebook items imported per year','buddystream_facebook')."</td>
            <td>" . $average_facebook_year . "</td>
        </tr>
        ";
    ?>
   </tbody>
</table>