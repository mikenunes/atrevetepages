<?php
if ($_POST) {
    
    update_user_meta($bp->loggedin_user->id, 'buddystream_rss_feeds', $_POST['buddystream_rss_feeds']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_rss_filtergood', $_POST['buddystream_rss_filtergood']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_rss_filterbad', $_POST['buddystream_rss_filterbad']);
    
    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_lang') . '</p>
        </div>';
    }

    $buddystream_rss_feeds      = get_user_meta($bp->loggedin_user->id, 'buddystream_rss_feeds',1);
    $buddystream_rss_filtergood = get_user_meta($bp->loggedin_user->id, 'buddystream_rss_filtergood',1);
    $buddystream_rss_filterbad  = get_user_meta($bp->loggedin_user->id, 'buddystream_rss_filterbad',1);
    
    if ($buddystream_rss_feeds) {
      do_action('buddystream_rss_activated');
    }
?>

    <form id="settings_form" action="<?php echo  $bp->loggedin_user->domain.BP_SETTINGS_SLUG; ?>/buddystream-rss/" method="post">
        <h3><?php echo __('Rss Settings', 'buddystream_lang')?></h3>
        
        
        <h5><?php _e('Feeds (new line per feed)', 'buddystream_rss');?></h5>
        <textarea name="buddystream_rss_feeds" rows="5" cols="60"><?php echo $buddystream_rss_feeds; ?></textarea>
        <br/><br/>
        
        <h5><?php _e('Filters', 'buddystream_rss');?></h5>
        <?php _e('rss user filters description','buddystream_rss'); ?>
        <br/><br/>

        <h5><?php _e('Good Filter (separate words with commas)', 'buddystream_rss');?></h5>
        <input type="text" name="buddystream_rss_filtergood" value="<?php echo $buddystream_rss_filtergood;?>" size="50" />
        <br/><br/>

        <h5><?php _e('Bad Filter (separate words with commas)', 'buddystream_rss');?></h5>
        <input type="text" name="buddystream_rss_filterbad" value="<?php echo $buddystream_rss_filterbad;?>" size="50" />
        <br/>
       
       
       <input type="submit" value="<?php echo __('Save settings', 'buddystream_lang');?>">
    </form>