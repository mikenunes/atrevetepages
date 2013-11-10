<?php
/**
 * Rss Class
 */

class BuddystreamRss
{
    protected $_feedUrl;

     public function setFeedUrl($feedUrl)
     {
         $this->_feedUrl = $feedUrl;
     }


     public function getFeedUrl()
     {
         return $this->_feedUrl;
     }

     public function getItems()
     {
         $feed = fetch_feed($this->getFeedUrl());
         if(!is_wp_error($feed)) {
             $maxitems = $feed->get_item_quantity(50); 
             $items    = $feed->get_items(0, $maxitems); 
             return $items;
         }else{
             return "";
         }
     }
}