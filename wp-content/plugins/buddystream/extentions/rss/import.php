<?php
/**
 * Import starter
 */

function BuddystreamRssImportStart(){
    $importer = new BuddyStreamRssImport();
    $importer->doImport();
}

/**
 * Rss Import Class
 */
class BuddyStreamRssImport {

    public function doImport() {

        global $bp, $wpdb;
        $itemCounter = 0;

            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='buddystream_rss_feeds'
                        order by meta_value;"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {
                    
                    $import = 1;
                    
                   //daycounter reset
                    if (get_user_meta($user_meta->user_id, 'buddystream_rss_counterdate') != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'buddystream_rss_daycounter', 1);
                        update_user_meta($user_meta->user_id, 'buddystream_rss_counterdate', date('d-m-Y'));
                    }else{
                        update_user_meta($user_meta->user_id, 'buddystream_rss_counterdate', date('d-m-Y'));
                    }

                    //max items per day
                    if (get_site_option(
                            'buddystream_rss_user_settings_maximport'
                        ) != '') {

                        if (get_user_meta(
                                $user_meta->user_id,
                                'buddystream_rss_daycounter'
                            ,1) <= get_site_option(
                                'buddystream_rss_user_settings_maximport'
                            )
                        ) {

                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }
                    
                    if ($import == 1 && get_user_meta($user_meta->user_id, 'buddystream_rss_feeds',1) != "") {
                        
                        $feeds = get_user_meta($user_meta->user_id, 'buddystream_rss_feeds',1);
                        $feeds = explode("\n",$feeds);
                        
                        if(is_array($feeds)){
                            
                            foreach($feeds as $feed) {

                                if(!empty($feed)){
                                
                                $rss = new BuddyStreamRss();
                                $rss->setFeedUrl($feed);
                                $items = $rss->getItems();

                                if (is_array($items)) {
                                    foreach ($items as $item) {
                                        
                                        $filter1 = 1;
                                        $filter2 = 1;

                                        //max items
                                        $max = 1;
                                        if (get_site_option('buddystream_rss_user_settings_maximport') != '') {
                                            if (get_user_meta($user_meta->user_id,'buddystream_rss_daycounter',1) <= get_site_option('buddystream_rss_user_settings_maximport')) {
                                                $max = 0;
                                            }
                                        }else{
                                            $max = 0;
                                        }
                                        
                                        
                                        //good filters
                                        if(get_site_option('buddystream_rss_filter') or get_user_meta($user_meta->user_id, 'buddystream_rss_filtergood', 1)){
                                           foreach(explode(",",get_site_option('buddystream_rss_filter') . get_user_meta($user_meta->user_id, 'buddystream_rss_filtergood', 1) ) as $filter){
                                                if(preg_match("/".$filter."/i",strtolower($item->get_description()))){
                                                    $filter1 = 1;
                                                }else{
                                                    $filter1 = 0;
                                                }
                                           }
                                        }
                                        
                                        //deny when having one of the badfilters in it
                                        if((get_site_option('buddystream_rss_filterexplicit') or get_user_meta($user_meta->user_id, 'buddystream_rss_filtergood', 1)) && $filter1==1){
                                            foreach(explode(",",get_site_option('buddystream_rss_filterexplicit') . get_user_meta($user_meta->user_id, 'buddystream_rss_filterbad', 1)) as $filter){
                                                if(preg_match("/".$filter."/i",strtolower($item->get_description()))){
                                                    $filter2 = 0;
                                                }
                                                if(preg_match("/".$filter."/i",strtolower($item->get_title()))){
                                                    $filter2 = 0;
                                                }
                                            }
                                        }

                                        if($filter1 == 1 && $filter2 == 1){
                                        $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $user_meta->user_id."_".md5($item->get_permalink())),'show_hidden' => true));
                                        if (!$activity_info['activities'][0]->id) {

                                            $description = strip_tags($item->get_description());
                                            if(strlen($description) > 200){
                                               $description = substr($description,0,200)."... <br><br> <a href='".$item->get_permalink()."' target='_blank' rel='external'>read more</a>";
                                            }

                                            $content =
                                                '<div class="rss_container_message">
                                                       <b>'.$item->get_title().'</b><br>
                                                       '.$description.'
                                                  </div>';

                                             buddystreamCreateActivity(array(
                                                 'user_id'       => $user_meta->user_id,
                                                 'extention'     => 'rss',
                                                 'type'          => 'item',
                                                 'content'       => $content,
                                                 'item_id'       => $user_meta->user_id."_".md5($item->get_permalink()),
                                                 'raw_date'      => gmdate('Y-m-d H:i:s',strtotime($item->get_date())),
                                                 'actionlink'    => $item->get_permalink()
                                                )
                                             );
                                             $itemCounter++;
                                        }
                                    }
                                    }
                                }
                            }
                            }
                        }
                    }
                }
                    //add record to the log
                    buddystreamLog("Rss imported ".$itemCounter." items.");
        }
    }
}