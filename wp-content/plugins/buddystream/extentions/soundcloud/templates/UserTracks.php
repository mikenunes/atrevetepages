<h3><?php _e('Soundcloud Tracks', 'buddystream_soundcloud')?></h3>
<?php if( bp_has_activities('object=soundcloud&per_page=5')): ?>
    <div class="buddystream_soundcloud_album">
        <div class="buddystream_soundcloud_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>
    <?php    
        while ( bp_activities() ) { 
            bp_the_activity(); 
            $content = bp_get_activity_content_body();
            echo $content."<br/>";
        }
    ?>
    </div>
    <div class="buddystream_soundcloud_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>

<?php else: ?>
    <?php  _e('User has no tracks (yet)','buddystream_soundcloud'); ?>
<?php endif; ?>