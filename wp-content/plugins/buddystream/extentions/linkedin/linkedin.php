<?php
/**
 * LinkedIn class
 */

class BuddystreamLinkedin {

    protected $_callBackUrl;
    protected $_consumerKey;
    protected $_consumerSecret;
    protected $_postContent;
    protected $_shortLink;
    protected $_username;
    protected $_accessToken;
    protected $_accessTokenSecret;
    protected $_badFilters;
    protected $_goodFilters;
    
      public function setCallbackUrl($callBackUrl)
      {
        $this->_callBackUrl = $callBackUrl;
      }

      public function getCallbackUrl()
      {
        return $this->_callBackUrl;
      }

      public function setConsumerKey($consumerKey)
      {
        $this->_consumerKey = $consumerKey;
      }

      public function getConsumerKey()
      {
        return $this->_consumerKey;
      }

      public function setConsumerSecret($consumerSecret)
      {
        $this->_consumerSecret = $consumerSecret;
      }

      public function getConsumerSecret()
      {
        return $this->_consumerSecret;
      }

     public function setUsername($username)
     {
         $this->_username = $username;
     }


     public function getUsername()
     {
         return $this->_username;
     }

     public function setAccessToken($accessToken)
     {
        $this->_accessToken = $accessToken;
     }

     public function getAccessToken()
     {
         return $this->_accessToken;
     }

     public function setAccessTokenSecret($accessTokenSecret)
     {
        $this->_accessTokenSecret = $accessTokenSecret;
     }

     public function getAccessTokenSecret()
     {
         return $this->_accessTokenSecret;
     }

     public function setShortLink($shortLink)
     {
        $this->_shortLink = $shortLink;
     }

     public function getShortLink()
     {
         return $this->_shortLink;
     }

      public function getConsumer()
      {
         $consumer = new Zend_Oauth_Consumer(
             array(
                 'version' => '1.0',
                 'callbackUrl' => $this->getCallbackUrl(),
                 'requestTokenUrl' => 'https://api.linkedin.com/uas/oauth/requestToken',
                 'userAuthorizationUrl' => 'https://api.linkedin.com/uas/oauth/authorize',
                 'accessTokenUrl' => 'https://api.linkedin.com/uas/oauth/accessToken',
                 'consumerKey' => $this->getConsumerKey(),
                 'consumerSecret' => $this->getConsumerSecret()
             )
         );

         return $consumer;
      }
      
      
      public function getClient(){
          $options = array('version' => '1.0',
		'localUrl' => $this->getCallbackUrl(),
		'callbackUrl' => $this->getCallbackUrl(),
		'requestTokenUrl' => 'https://api.linkedin.com/uas/oauth/requestToken',
		'userAuthorisationUrl' => 'https://api.linkedin.com/uas/oauth/authorize',
		'accessTokenUrl' => 'https://api.linkedin.com/uas/oauth/accessToken',
		'consumerKey' => $this->getConsumerKey(),
		'consumerSecret' => $this->getConsumerSecret());
         
               $access = new Zend_Oauth_Token_Access();
               $access->setToken($this->getAccessToken());
               $access->setTokenSecret($this->getAccessTokenSecret());
               
               return $access->getHttpClient($options);
      }
      
     public function getRedirectUrl()
     {
         global $bp;

         try {
             $consumer = $this->getConsumer();
             $token = $consumer->getRequestToken();
             
             update_user_meta($bp->loggedin_user->id,"buddystream_linkedin_oauth_token",$token->oauth_token);
             update_user_meta($bp->loggedin_user->id,"buddystream_linkedin_oauth_token_secret",$token->oauth_token_secret);

             return $consumer->getRedirectUrl(null, $token);
          }  catch (Exception $e){
              buddystreamLog('LinkedIn configuration error, try to re-enter the API keys.','error');
              return false;
          }
     }


     public function getLinkedinToken(){

         global $bp;
         $oauthTokenRequest = new Zend_Oauth_Token_Request();
         $oauthTokenRequest->setToken(get_user_meta($bp->loggedin_user->id,"buddystream_linkedin_oauth_token",1));
         $oauthTokenRequest->setTokenSecret(get_user_meta($bp->loggedin_user->id,"buddystream_linkedin_oauth_token_secret",1));

         return $oauthTokenRequest;
     }
     
     public function createXmlData($content){
         
         return '<?xml version="1.0" encoding="UTF-8"?>
            <share>
              <comment>'.$content.'</comment>
              <visibility>
                 <code>anyone</code>
              </visibility>
            </share>';
     }

     public function setPostContent($content)
     {
         $content = stripslashes($content);
         $content = str_replace("#linkedin", "", $content);
         $content = strip_tags($content);

         //shorten message to max 140
         if(strlen($content.' '.$this->getShortLink()) > 140){
             $maxChar = 137-strlen($this->getShortLink());
             $content = substr($content,0,$maxChar).'...'.$this->getShortLink();
         }else{
             $content =  $content.' '.$this->getShortLink();
         }

         $this->_postContent = $this->createXmlData($content);
     }
     
     public function postUpdate()
     {
         $client = $this->getClient();
         $client->setUri('http://api.linkedin.com/v1/people/~/shares');         
         $client->setMethod(Zend_Http_Client::POST);
         
         $client->setRawData($this->_postContent, 'text/xml');
         $client->setHeaders('Content-Type', 'text/xml');
         $response = $client->request();
     }
     
     public function setBadFilters($badfilters){
        $this->_badFilters = $badfilters;
     }

     public function getBadFilters(){
         return $this->_badFilters;
     }

     public function setGoodFilters($goodfilters){
         $this->_goodFilters = $goodfilters;
     }

     public function getGoodFilters(){
         return $this->_goodFilters;
     }

    public function getFriends(){
     
         $client = $this->getClient();
         $client->setUri('https://api.linkedin.com/v1/people/~/connections');  
         $client->setParameterGet('scope','self');
         $client->setMethod(Zend_Http_Client::GET);
         $request = $client->request();
         $response = $request->getBody();
         $friends = simplexml_load_string($response);
         
         return $friends;

    }
     
     public function getShares()
     {
         
         $client = $this->getClient();
         $client->setUri('https://api.linkedin.com/v1/people/~/network/updates');  
         $client->setParameterGet('scope','self');
         $client->setMethod(Zend_Http_Client::GET);
         $request = $client->request();
         $response = $request->getBody();
         $shares = simplexml_load_string($response);
         
         $items = array();
         
         if($shares){
                foreach($shares as $share){

                    //checkvar
                    $content  = "";
                    $filter1  = 1;
                    $filter2  = 1;

                    //get the content
                    if($share->{'update-type'} == 'STAT'){
                        $content = $share->{'update-content'}->{'person'}->{'current-status'};
                    }
                    
                          //only allow if filter is in it
                           if($this->getGoodFilters()){
                               foreach(explode(",",$this->getGoodFilters()) as $filter){
                                    if(preg_match("/".$filter."/i",strtolower($content))){
                                        $filter1 = 1;
                                    }else{
                                        $filter1 = 0;
                                    }
                                }
                           }

                        //deny when having one of the badfilters in it
                           if($this->getBadFilters() && $filter1 == 1){
                                foreach(explode(",",$this->getBadFilters()) as $filter){
                                    if(preg_match("/".$filter."/i",strtolower($content))){
                                        $filter2 = 0;
                                    }
                                }
                           }

                        if($filter1== 1 && $filter2== 1){
                            $items[] = $share;
                        }
                }
         }else{
             return $items;
         }
         
         return $items;
         
        }
}