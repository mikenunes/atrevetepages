<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('rss'); ?>

<?php
if ($_POST) {
   update_site_option('buddystream_rss_filter', trim(strip_tags(strtolower($_POST['buddystream_rss_filter']))));
   update_site_option('buddystream_rss_filter_show', trim(strip_tags($_POST['buddystream_rss_filter_show'])));
   update_site_option('buddystream_rss_filterexplicit', trim(strip_tags(strtolower($_POST['buddystream_rss_filterexplicit']))));
   echo '<div class="buddystream_info_box_green">' . __('Filters saved.', 'buddystream_rss') . '</div>';
}
?>
  
    <div class="buddystream_info_box">
     <?php _e('rss filters description','buddystream_rss'); ?>
    </div>

    <form method="post" action="">
        <table class="buddystream_table" cellspacing="0">
           
            <tr class="header">
                <td colspan="2"><?php _e('Rss filters (optional)', 'buddystream_rss');?></td>
            </tr>
            
            <tr>
                <td scope="row"><?php _e('Filters (comma seperated)', 'buddystream_rss');?></td>
                <td>
                    <input type="text" name="buddystream_rss_filter"value="<?php echo get_site_option('buddystream_rss_filter');?>" size="50" />
                </td>
            </tr>

            <tr class="odd">
                <td scope="row"><?php _e('Explicit words filters (comma seperated)', 'buddystream_rss');?></td>
                <td>
                    <input type="text" name="buddystream_rss_filterexplicit" value="<?php echo get_site_option('buddystream_rss_filterexplicit');?>" size="50" />
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>