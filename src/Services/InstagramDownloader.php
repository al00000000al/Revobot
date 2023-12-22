<?php

namespace Revobot\Services;

use Revobot\Config;
use Revobot\Util\Curl;

class InstagramDownloader
{
    public static function get(string $url, int $chat_id)
    {
        Curl::get(Config::get('dl_video_api_url') . '?url=' . urlencode($url) . '&chat_id=' . $chat_id . '&access_code=' . Config::get('dl_video_api_key'));
        return true;
    }

    // Check if the URL matches the pattern for Instagram reels URLs
    public static function is_instagram_reels_url(string $url)
    {
        return preg_match('#^https?://(www\.)?instagram\.com/reel/([^/?]+)#i', $url);
    }
}
