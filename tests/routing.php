<?php

class UserController
{
    public  function showProfile($userId)
    {
        echo "User Profile: $userId";
    }

    public  function editProfile($userId)
    {
        echo "Edit Profile: $userId";
    }
}

class PostController
{
    public  function showPost($postSlug)
    {
        echo "Post: $postSlug";
    }

    public  function createPost()
    {
        echo "Create Post";
    }
}

class Router
{
    private $routes = [];

    public function addRoute($pattern, $handler)
    {
        $this->routes[] = ['pattern' => $pattern, 'handler' => $handler];
    }

    public function routeRequest($url)
    {
        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $url, $matches)) {
                list($className, $methodName) = explode('@', $route['handler']);
                $class = new $className();
                $class->$methodName($matches[1]); // Создаем объект класса и вызываем метод
                return;
            }
        }
        // Обработка случая, когда маршрут не найден
    }
}

// Пример использования:

$router = new Router();

$router->addRoute('/^\/profile\/(\d+)$/', 'UserController@showProfile');
$router->addRoute('/^\/profile\/(\d+)\/edit$/', 'UserController@editProfile');

$router->addRoute('/^\/post\/(\w+)$/', 'PostController@showPost');
$router->addRoute('/^\/post\/create$/', 'PostController@createPost');

$currentUrl = '/profile/1234';
$router->routeRequest($currentUrl);
