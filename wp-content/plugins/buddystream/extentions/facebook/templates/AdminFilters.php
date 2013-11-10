<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('facebook'); ?>

<?php
if ($_POST) {
   update_site_option('facestream_filter', trim(strip_tags(strtolower($_POST ['facestream_filter']))));
   update_site_option('facestream_filter_show', trim(strip_tags($_POST ['facestream_filter_show'])));
   update_site_option('facestream_filterexplicit', trim(strip_tags(strtolower($_POST ['facestream_filterexplicit']))));
   echo '<div class="buddystream_info_box_green">' . __('Filters saved.', 'buddystream_facebook') . '</div>';
}
?>
  
    <div class="buddystream_info_box">
     <?php _e('facebook filters description','buddystream_facebook'); ?>
    </div>

    <form method="post" action="">
        <table class="buddystream_table" cellspacing="0">
           
            <tr class="header">
                <td colspan="2"><?php _e('Facebook filters (optional)', 'buddystream_facebook');?></td>
            </tr>
            
            <tr>
                <td scope="row"><?php _e('Filters (comma seperated)', 'buddystream_facebook');?></td>
                <td>
                    <input type="text" name="facestream_filter"value="<?php echo get_site_option('facestream_filter');?>" size="50" />
                </td>
            </tr>

            <tr class="odd">
                <td scope="row"><?php _e('Explicit words filters (comma seperated)', 'buddystream_facebook');?></td>
                <td>
                    <input type="text" name="facestream_filterexplicit" value="<?php echo get_site_option('facestream_filterexplicit');?>" size="50" />
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>