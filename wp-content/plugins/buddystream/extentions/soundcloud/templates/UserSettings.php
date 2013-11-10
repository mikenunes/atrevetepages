<?php

  if($_GET['reset'] == 'true'){
      delete_user_meta($bp->loggedin_user->id, 'soundcloud_access_token');
      delete_user_meta($bp->loggedin_user->id, 'soundcloud_expires_in');
      delete_user_meta($bp->loggedin_user->id, 'soundcloud_refresh_token');
  }

  if (!get_user_meta($bp->loggedin_user->id, 'soundcloud_access_token',1)) {
           if(buddystreamCheckNetwork("http://soundcloud.com")){
             echo '<h3>' . __('Soundcloud setup</h3>
             You may setup you Soundcloud intergration over here.<br>
             Before you can begin using Soundcloud with this site you must authorize on Soundcloud by clicking the link below.', 'buddystream_soundcloud') . '<br><br>';

             $soundcloud = new BuddystreamSoundcloud(
                               get_site_option("soundcloud_client_id"), 
                               get_site_option("soundcloud_client_secret"),
                               $bp->root_domain."/?buddystream_auth=soundcloud"
                           );

             echo '<a href="' . $soundcloud->getAuthorizeUrl() . '">' . __('Click here to start authorization', 'buddystream_soundcloud') . '</a><br/><br/>';
           }else{
                _e('Soundcloud is currently offline please come back in a while.','buddystream_soundcloud');
           }
     }else{
         echo '<h3>' . __('Soundcloud setup</h3>You are succefully connected to Soundcloud!<br/><br/>
               <br/><strong><a href="?reset=true">'._e('Remove my Soundcloud settings', 'buddystream_soundcloud').'</a></strong>');
     }