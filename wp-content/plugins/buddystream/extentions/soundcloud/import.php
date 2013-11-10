<?php
/**
 * Import starter
 */

function BuddystreamSoundcloudImportStart(){
    //check if api server is online
    if(buddystreamCheckNetwork("http://soundcloud.com")){
        $importer = new BuddyStreamSoundcloudImport();
        $importer->doImport();
    }else{
        buddystreamLog(__("Soundcloud API server offline at the moment.","buddystream_soundcloud"),"error");
    }  
}

/**
 * Soundcloud Import Class
 */
class BuddyStreamSoundcloudImport{

    public function doImport() {
        
        global $bp, $wpdb;
        $itemCounter = 0;
 
        if (get_site_option("soundcloud_client_id")) {
        if (get_site_option('soundcloud_user_settings_syncbp') == 0) {
                        
            $user_metas = $wpdb->get_results($wpdb->prepare(
               "SELECT user_id
                FROM $wpdb->usermeta where
                meta_key='soundcloud_access_token'"
             )
            );
            
            if ($user_metas) {
                foreach ($user_metas as $user_meta) {
                    $import = 1;
                   
                    //daycounter reset
                    if (get_user_meta($user_meta->user_id, 'soundcloud_counterdate') != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'soundcloud_daycounter', 1);
                        update_user_meta($user_meta->user_id, 'soundcloud_counterdate', date('d-m-Y'));
                    }

                    //max tracks per day
                    if (get_site_option(
                            'soundcloud_user_settings_maximport'
                        ) != '') {

                        if (get_usermeta(
                                $user_meta->user_id,
                                'soundcloud_daycounter'
                            ) <= get_site_option(
                                'soundcloud_user_settings_maximport'
                            )
                        ) {
                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }
                    
                    
                    if ($import == 1) {
                        
                      $soundcloud = new BuddystreamSoundcloud(
                           get_site_option("soundcloud_client_id"), 
                           get_site_option("soundcloud_client_secret"),
                           $bp->root_domain."/?buddystream_auth=soundcloud"
                       );
                        
                        if (get_user_meta($user_meta->user_id, "soundcloud_expires_in", 1) < time()) {
                            $soundcloudToken = $soundcloud->accessTokenRefresh(get_user_meta($user_meta->user_id, "soundcloud_refresh_token", 1));
                            update_user_meta($user_meta->user_id, 'soundcloud_access_token', $soundcloudToken['access_token']);
                            update_user_meta($user_meta->user_id, 'soundcloud_expires_in', $soundcloudToken['expires_in']+time());
                            update_user_meta($user_meta->user_id, 'soundcloud_refresh_token', $soundcloudToken['refresh_token']);
                        }
                        
                        $soundcloud->setAccessToken(get_user_meta($user_meta->user_id, 'soundcloud_access_token',1));
                           
                            try {
                                $tracks = json_decode($soundcloud->get('me/favorites'), true);
                            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                                buddystreamLog("Soundcloud: ".$e->getMessage(), "error");
                            }
                            
                            if($tracks){
                                foreach($tracks as $track){
                                   
                                $max = 1;
                                if (get_site_option('soundboard_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'soundboard_daycounter',1) <= get_site_option('soundboard_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }
                                
                                
                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => "soundcloud_".$track['id']),'show_hidden' => true));
                                if (!$activity_info ['activities'][0]->id && $max == 0) {

                                    $content = '<span class="buddystream_soundcloud_title">'.$track['title'].'</span><br/>
                                               '.$track['uri'];    
               
                                    buddystreamCreateActivity(array(
                                         'user_id'       => $user_meta->user_id,
                                         'extention'     => 'soundcloud',
                                         'type'          => 'song',
                                         'content'       => $content,
                                         'item_id'       => "soundcloud_".$track['id'],
                                         'raw_date'      => gmdate('Y-m-d H:i:s',strtotime($track['created_at'])),
                                         'actionlink'    => $track['permalink_url']
                                        )
                                     );
                                     $itemCounter++;
                                     $content = "";
                                        
                                }
                                
                                }
                            }
                    }
                }
            }
        }
        }
        
        //add record to the log
    buddystreamLog("Soundcloud imported ".$itemCounter." tracks.");
        
    }
}