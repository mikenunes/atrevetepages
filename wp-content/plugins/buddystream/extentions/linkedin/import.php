<?php

/**
 * Import starter
 */

function BuddystreamLinkedinImportStart(){
    //check if api server is online
    if(buddystreamCheckNetwork("http://www.linkedin.com")) {
        $importer = new BuddyStreamLinkedinImport();
        $importer->doImport();
     }else{
        buddystreamLog(__("LinkedIn API server offline at the moment.","buddystream_linkedin"),"error");
    }  
}

/**
 * Linkedin Import Class
 */

class BuddyStreamLinkedinImport{

   //do the import 
   public function doImport() {
        
        global $bp, $wpdb;
        
        //item counter for in the logs
        $itemCounter = 0;
 
        if (get_site_option("buddystream_linkedin_consumer_key")) {
            if (get_site_option('buddystream_linkedin_user_settings_syncbp') == 0) {
            
                $user_metas = $wpdb->get_results(
                        $wpdb->prepare("SELECT user_id FROM $wpdb->usermeta where meta_key='buddystream_linkedin_token'")
                );

                if ($user_metas) {
                 foreach ($user_metas as $user_meta) {
                     
                    //always start with import = 1
                    $import = 1;

                    //daycounter reset
                    if (get_user_meta($user_meta->user_id, 'buddystream_linkedin_counterdate', 1) != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'buddystream_linkedin_daycounter', 1);
                        update_user_meta($user_meta->user_id, 'buddystream_linkedin_counterdate', date('d-m-Y'));
                    }

                    //max tweets per day
                    if (get_site_option('buddystream_linkedin_user_settings_maximport') != '') {
                        if (get_user_meta($user_meta->user_id, 'buddystream_linkedin_daycounter',1) <= get_site_option('buddystream_linkedin_user_settings_maximport')) {
                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }
                    
                    if ($import == 1  && get_user_meta($user_meta->user_id, 'buddystream_linkedin_synctoac', 1) == "1") {
                        
                        //LINKEDIN
                        
                        $linkedin = new BuddystreamLinkedin();
                        $linkedin->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-linkedin');
                        $linkedin->setConsumerKey(get_site_option("buddystream_linkedin_consumer_key"));
                        $linkedin->setConsumerSecret(get_site_option("buddystream_linkedin_consumer_secret"));
                        $linkedin->setAccessToken(get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_token',1));
                        $linkedin->setAccessTokenSecret(get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_tokensecret',1));
                        $linkedin->setGoodFilters(get_site_option('buddystream_linkedin_filter') . get_user_meta($user_meta->user_id, 'buddystream_linkedin_filtergood', 1));
                        $linkedin->setBadFilters(get_site_option('buddystream_linkedin_filterexplicit') . get_user_meta($user_meta->user_id, 'tweettream_filterbad', 1));
                        $items = $linkedin->getShares();
                        
                        if (is_array($items)) {
                            
                            //go through items
                            foreach ($items as $item) {

                                //max items
                                $max = 1;
                                if (get_site_option('buddystream_linkedin_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'buddystream_linkedin_daycounter', 1) <= get_site_option('buddystream_linkedin_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }
                                
                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => "linkedin_".$item->{'update-key'}),'show_hidden' => true));
                                if (!$activity_info ['activities'][0]->id && $max == 0) {
                                    
                                    //convert timestamp
                                    $timeStamp = $item->timestamp;
                                    $timeStamp = substr($timeStamp, 0,10);
                                    
                                    
                                    $activity =array(
                                         'user_id'       => $user_meta->user_id,
                                         'extention'     => 'linkedin',
                                         'type'          => 'status',
                                         'content'       => $item->{'update-content'}->{'person'}->{'current-status'}->asXml(),
                                         'item_id'       => "linkedin_".$item->{'update-key'},
                                         'raw_date'      => gmdate('Y-m-d H:i:s',$timeStamp),
                                         'actionlink'    => trim($item->{'update-content'}->{'person'}->{'site-standard-profile-request'}->{'url'}."")

                                     );
                                   
                                    buddystreamCreateActivity($activity);
                                    
                                    $itemCounter++;
                                }
                            }
                        }
                    }                    
                }
            }
        }
        }
        
    //add record to the log
    buddystreamLog("LinkedIn imported ".$itemCounter." items.");
    
    }
}