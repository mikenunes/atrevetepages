<?php
/**
 * BuddyStream Facebook Import Starter
 */

function BuddystreamFacebookImportStart(){
    //check if api server is online
    if(buddystreamCheckNetwork("http://www.facebook.com")){
        $importer = new BuddyStreamFacebookImport();
        $importer->doImport();
        
    }else{
        buddystreamLog(__("Facebook API server offline at the moment.","buddystream_facebook"),"error");
    }
}

/**
 * BudddyStream Facebook Import class
 */

class BuddyStreamFacebookImport {
    
    public function doImport() {

        global $bp,$wpdb;
        $itemCounter = 0;

            $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta where meta_key='facestream_session_key';"));

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {
                        
                    //max import reset
                    if (get_user_meta($user_meta->user_id, 'facestream_counterdate', 1) != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'facestream_daycounter', 1);
                        update_user_meta($user_meta->user_id, 'facestream_counterdate', date('d-m-Y'));
                    }

                   //max items per day
                   if (get_site_option('facestream_user_settings_maximport') != '') {
                       if (get_user_meta($user_meta->user_id, 'facestream_daycounter', 1) <= get_site_option('facestream_user_settings_maximport')) {
                           $import = 1;
                       } else {
                           $import = 0;
                       }
                   } else {
                       $import = 1;
                   }
     
                   //import turned on by user?
                   if(!get_user_meta($user_meta->user_id,'facestream_synctoac',1)){
                       $import = 0;
                   }
                   
                   
                   if ($import == 1) {

                        //FACEBOOK
                        $items    = "";
                        $facebook = new BuddystreamFacebook;
                        $facebook->setApplicationKey(get_site_option("facestream_application_id"));
                        $facebook->setApplicationId(get_site_option("facestream_application_id"));
                        $facebook->setApplicationSecret(get_site_option("facestream_application_secret"));
                        $facebook->setAccessToken(get_user_meta($user_meta->user_id, 'facestream_session_key', 1));
                        $facebook->setSource($bp->root_domain);
                        $facebook->setUsername(get_userdata($user_meta->user_id)->user_login);
                        $facebook->setGoodFilters(get_site_option('facestream_filter').get_user_meta($user_meta->user_id,'facestream_filtergood', 1));
                        $facebook->setBadFilters(get_site_option('facestream_filterexplicit').get_user_meta($user_meta->user_id,'facetream_filterbad', 1));
                        $items = $facebook->requestWall();
                                                
                          if(is_array($items)){
                            foreach($items as $item){

                                //max items
                                $max = 1;
                                if (get_site_option('facestream_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'facestream_daycounter', 1) <= get_site_option('facestream_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                } else {
                                    $max = 0;
                                }
                                
                                //does the item already excist
                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $item['id']), 'show_hidden' => true));
                                if(!$activity_info['activities'][0]->id && $max == 0) {
                                       
                                    //shorten the message/description
                                    $message = "";
                                    $message = $item['message'];
                                    if (strlen($message) > 400) {
                                       $message = substr($message,0,400)."...";
                                    }
                                    
                                    if(empty($message)){
                                        $message = $item['description'];
                                        if (strlen($message) > 400) {
                                            $message = substr($message,0,400)."...";
                                        }
                                    }
         
                                    //reset the content
                                    $content = "";
                                    
                                    //are we allowed to import this type
                                    if ($item['type'] == "status"  && get_site_option("facestream_user_settings_syncupdatesbp") == "on") {
                                          $content =
                                            '<div class="facebook_container_message">
                                                   '.$message.'
                                                </div>';
                                    }

                                    if ($item['type'] == "photo" && get_site_option("facestream_user_settings_syncphotosbp") == "on"){
                                         
                                        $fullSize = $item['picture'];
                                        $fullSize = str_replace("_s", "_n", $fullSize);
                                        $fullSize = str_replace("_z", "_n", $fullSize);
                                        
                                        $content ='<div class="facebook_container_image" id="facebook_photo">
                                              <a href="'.$fullSize.'" class="bs_lightbox"><img src="'.$item['picture'].'"></a>
                                           </div>
                                           <div class="facebook_container_message">
                                               '.$message.'
                                            </div>';
                                        
                                        
                                    }
                                    
                                    
                                    if($item['type'] == "link" && get_site_option("facestream_user_settings_synclinksbp") == "on"){
                                        
                                        if($item['picture']){
                                            $imgArray = explode("=", $item['picture']);
                                            $imgArray = array_reverse($imgArray);
                                        }
                                        
                                        $content =
                                        '<div class="facebook_container_image">
                                              <a href="'.urldecode($imgArray[0]).'" class="bs_lightbox"><img src="'.$item['picture'].'"></a>
                                           </div>
                                           <div class="facebook_container_message">
                                                <strong><a href="'.$item['link'].'" target="_new" rel="external">'.$item['name'].'</a></strong><br/>
                                                '.$message.'
                                            </div>';
                                        
                                    }

                                    if($item['type']=="video" && get_site_option("facestream_user_settings_syncvideosbp") == "on"){

                                        if ($item['attribution'] != "YouTube") {
                                            $content =
                                            '<div class="facebook_container_image">
                                                  <a href="'.$item['link'].'" class="bs_lightbox"><img src="'.$item['picture'].'"></a>
                                               </div>
                                               <div class="facebook_container_message">
                                                   '.$message.'
                                                </div>';
                                        }
                                    }

                                    //check of item does not exist.
                                    if(!bp_activity_check_exists_by_content($content) && $content != ""){
                                        buddystreamCreateActivity(array(
                                             'user_id'       => $user_meta->user_id,
                                             'extention'     => 'facebook',
                                             'type'          => $item['type'],
                                             'content'       => $content,
                                             'item_id'       => $item['id'],
                                             'raw_date'      => gmdate('Y-m-d H:i:s',strtotime($item['created_time'])),
                                             'actionlink'    => 'http://www.facebook.com/profile.php?id=' .get_user_meta($user_meta->user_id, 'facestream_user_id', 1),
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
         
         //add record to the log
         buddystreamLog("Facebook imported ".$itemCounter." items.");
         
     }
}