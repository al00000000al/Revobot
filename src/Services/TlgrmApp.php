<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class TlgrmApp
{
    public const URL = 'https://typesense.tlgrm.app/collections/channels/documents/search?';

    /**
     * @param string $query
     * @return string
     */
    public static function search(string $query): string
    {
        $tries = 0;
        $link = '';

        while ($tries < 3) {
            $res = self::fetch($query);
            if ($res && isset($res['found'])) {
                $count = (int)$res['found'];
                if ($count > 0 && isset($res['hits'][0]['document']['link'])) {
                    $link = (string)$res['hits'][0]['document']['link'];
                    return 'https://t.me/' . $link;
                }
            } elseif (isset($res['message'])) {
                dbg_echo((string)$res['message']);
                break;
            }
            $tries++;
        }
        return $link;

    }

    /**
     * @param string $query
     * @return mixed
     */
    private static function fetch(string $query)
    {
        $params = http_build_query([
            'q' => $query,
            'query_by' => 'name,link',
            'per_page' => 1,
            'page' => '1',
            'query_by_weights' => '120,10',
            'sort_by' => '_text_match:desc',
            'filter_by' => 'lang:[na,en,ru,es,sv]',
            'highlight_fields' => '_',
            'min_len_1typo' => 5,
            'min_len_2typo' => 8,
            'x-typesense-api-key' => TLGRM_TYPESENSE_KEY,
        ]);
        return json_decode(Curl::get(self::URL . $params), true);
    }


}