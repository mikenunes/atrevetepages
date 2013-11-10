<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />

<?php echo buddystreamTabLoader('rss'); ?>

<div class="buddystream_info_box">
    <?php _e('rss statitics description','buddystream_rss'); ?>
</div>


<table class="buddystream_table" cellspacing="0">
    
    <tr class="header">
        <td colspan="2"><?php _e('Statistics', 'buddystream_rss'); ?></td>
    </tr>
    
    <?php
       $count_users             = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_rss_users         = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_rss_feeds';")));
       $perc_rss_users          = round(($count_rss_users / $count_users) * 100);
       $count_history           = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='rss';")));
       $count_activity          = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_rssupdates         = round(($count_history / $count_activity * 100));
       $average_history_day     = round($count_history / 24);
       $average_history_week    = $average_history_day * 7;
       $average_history_month   = $average_history_day * 30;
       $average_history_year    = $average_history_day * 365;

       echo "
        <tr>
            <td>".__('Amount of users:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Amount of user using rss:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $count_rss_users . "</td>
        </tr>
        <tr>
            <td>".__('Percentage of users using rss:','buddystream_rss')."</td> 
            <td scope='row' class='column'>" . $perc_rss_users . "%</td>
        </tr>
       <tr class='odd'>
            <td>".__('Amount of activity updates:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>".__('Amount of rss items:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $count_history . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Percentage of rss items:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $perc_rssupdates . "%</td>
        </tr>
        <tr>
            <td>".__('Average number of rss items imported per day:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $average_history_day . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Average number of rss items imported per week:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $average_history_week . "</td>
        </tr>
        <tr>
            <td>".__('Average number of rss items imported per month:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $average_history_month . "</td>
        </tr>
        <tr class='odd'>
            <td>".__('Average number of rss items imported per year:','buddystream_rss')."</td>
            <td scope='row' class='column'>" . $average_history_year . "</td>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

