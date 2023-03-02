<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class InstagramDownloader
{
   public static function get(string $url, int $chat_id){
        Curl::get(DL_VIDEO_API_URL.'?url='.urlencode($url).'&chat_id='.$chat_id.'&access_code='.DL_VIDEO_API_KEY);
        return true;
   }

   // Check if the URL matches the pattern for Instagram reels URLs
   public static function is_instagram_reels_url(string $url){
        return preg_match('#^https?://(www\.)?instagram\.com/reel/([^/?]+)#i', $url);
   }
}
