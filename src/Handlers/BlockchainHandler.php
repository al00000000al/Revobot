<?php

namespace Revobot\Handlers;

use Revobot\Money\Revocoin;
use Revobot\RequestHandlerInterface;
use Revobot\Response;
use Revobot\Util\Hash;

class BlockchainHandler implements RequestHandlerInterface
{

    const PAGE_MAIN     = 0;
    const PAGE_DETAILS  = 1;

    /** @kphp-required */
    public function handle($uri)
    {
        date_default_timezone_set('Europe/Moscow');
        list($page, $id, $hash) = $this->getParams($uri);
        if ($page === self::PAGE_MAIN) {
            // if (isset($_GET['offset'])) {
            //     $block_id = (int)$_GET['offset'];
            // } else {
            //     list($block_id, $_) = Revocoin::getLastBlock();
            // }
            // return Response::json($this->get($block_id));
            return Response::notFound();
        }

        if ($page === self::PAGE_DETAILS) {
            $json_str = Revocoin::getBlock('tg', $id)[0];
            if (hash_equals(Hash::generate($json_str), $hash)) {
                // return Response::json((array)json_decode($json_str));
                $items = json_decode($json_str);
                $items['time'] = $this->formatTime($items['time']);
                $items['hash'] = $hash;
                $items['text'] = htmlentities(iconv('utf-8', 'cp1251', $items['text']));
                $items['link'] = $this->renderLink('/blocks/' . ($id - 1) . '_' . $items["prev_hash"], '#' . ($id - 1) . ' ' . ($items["prev_hash"]));
                return Response::html($this->render($id, $items));
            }
            return Response::notFound();
        }
    }

    public function get($block_id, $count = 50)
    {
        $data = [];
        for ($i = $block_id; $i >= $count; $i--) {
            $json_str = Revocoin::getBlock('tg', $i)[0];
            $hash = Hash::generate($json_str);
            $data[] = json_decode($json_str) + ['hash' => $hash];
        }
        return $data;
    }

    /**
     * @return tuple(int, int, string)
     */
    private function getParams($uri)
    {
        $re = '/^blocks\/(?<block_id>\d+)_(?<block_hash>[0-9a-f]{64})$/s';
        preg_match($re, $uri, $matches, PREG_OFFSET_CAPTURE, 0);

        if (empty($matches)) {
            return tuple(self::PAGE_MAIN, 0, '');
        } else {
            return tuple(self::PAGE_DETAILS, (int)$matches['block_id'][0], (string)$matches['block_hash'][0]);
        }
    }

    /**
     * @kphp-inline
     */
    private function formatTime($timestamp)
    {
        return date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * @kphp-inline
     */
    private function renderLink($link, $value)
    {
        return "<a href=\"$link\" class=\"inline-block px-6 py-2 border-2 border-blue-500 text-blue-500 font-medium text-xs leading-tight uppercase rounded-full hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out truncate w-full\">{$value}</a>";
    }

    /**
     * @kphp-inline
     */
    private function render($id, $data)
    {

        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blockchain Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto px-4 py-10">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Транзакция #{$id}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Время:</dt>
                        <dd class="mt-1 text-sm text-gray-900 truncate">{$data['time']}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Сумма:</dt>
                        <dd class="mt-1 text-sm text-gray-900 truncate">{$data['amount']} R</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Версия:</dt>
                        <dd class="mt-1 text-sm text-gray-900 truncate">{$data['version']}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Случайное число:</dt>
                        <dd class="mt-1 text-sm text-gray-900 truncate">{$data['nonce']}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Текст:</dt>
                        <dd class="mt-1 text-sm text-gray-900 truncate">{$data['text']}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Хэш:</dt>
                        <dd class="mt-1 text-sm text-gray-900 truncate">{$data['hash']}</dd>
                    </div>
                </div>
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500">Предыдущий блок:</dt>
                    <dd class="mt-1 text-sm">{$data['link']}</dd>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
