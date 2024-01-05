<?php

namespace Revobot;

// use Revobot\Handlers\AdminHandler;

use Revobot\Handlers\BlockchainHandler;
use Revobot\Handlers\StableDiffusionHandler;
use Revobot\Handlers\TelegramBotHandler;
use Revobot\Handlers\VKBotHandler;

class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->defineRoutes();
    }

    private function defineRoutes()
    {
        $this->routes = [
            '/^tg_bot$/' => (new TelegramBotHandler),
            '/^vk_bot$/' => (new VKBotHandler),
            '/^sd_task/' => (new StableDiffusionHandler),
            '/^blocks/' => (new BlockchainHandler),
            // '/^admin(\/.*)?$/' => AdminHandler::class, // Обрабатывает 'admin' и любые подпути
        ];
    }

    public function handleRequest($uri)
    {
        $uri = $this->normalizeUri($uri);
        foreach ($this->routes as $pattern => $handlerClass) {
            if (preg_match($pattern, $uri)) {
                /** @var RequestHandlerInterface $handlerClass */
                $handlerClass->handle($uri);
                return;
            }
        }
        Response::notFound();
    }

    private function normalizeUri(string $uri)
    {
        return trim((string)parse_url($uri, PHP_URL_PATH), '/');
    }
}
