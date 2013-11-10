<h3><?php _e('Facebook Album', 'buddystream_facebook')?></h3>
<?php if( bp_has_activities('object=facebook&search_terms=facebook_photo&per_page=35')): ?>
    <div class="buddystream_facebook_album">
        <div class="buddystream_facebook_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>
    <?php    
        while ( bp_activities() ) { 
            bp_the_activity(); 
            $content = bp_get_activity_content_body();
            $content = strip_tags($content,"<img><a><div>");
            echo $content;
        }
    ?>
    </div>
    <div class="buddystream_facebook_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>

<?php else: ?>
    <?php  _e('User has no photo\'s (yet)','buddystream_facebook'); ?>
<?php endif; ?>

<div class="buddystream_facebook_album_hoverbox"></div>

<script>
        jQuery(document).ready(function() {
            
            jQuery(".bs_lightbox").attr("title","");
            jQuery(".buddystream_activity_container").mouseenter(function() {  
                
                parentPosition = jQuery(this).find('img').position();
                parentOffset = jQuery(this).find('img').offset();
                
                content = jQuery(this).find(".facebook_container_message").html();
                
                 jQuery('.buddystream_facebook_album_hoverbox').html(content);
                 jQuery('.buddystream_facebook_album_hoverbox').css('top', (parentOffset.top-145));
                 jQuery('.buddystream_facebook_album_hoverbox').css('left', parentPosition.left+110);
                 jQuery('.buddystream_facebook_album_hoverbox').show();
                
            }).mouseleave(function() {
                jQuery('.buddystream_facebook_album_hoverbox').hide();
            });
         });
</script>