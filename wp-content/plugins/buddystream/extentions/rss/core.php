<?php
/**
 * 
 * Page loader functions 
 *
 */

function buddystream_rss()
{
   buddystreamPageLoader('rss');
}

function buddystream_rss_user_settings()
{
    buddystreamUserPageLoader('rss');
}

function buddystream_rss_settings_screen_title()
{
    __('Rss', 'tweetstream_lang');
}

function buddystream_rss_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}

?>
