<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{

    protected $routes = []; //caching the routes when Router methods are called from routes.php

    protected function add($uri, $controller, $method)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add($uri, $controller, 'GET');

    }

    public function post($uri, $controller)
    {
        return $this->add($uri, $controller, 'POST');
    }

    public function delete($uri, $controller)
    {
        return $this->add($uri, $controller, 'DELETE');
    }

    public function patch($uri, $controller)
    {
        return $this->add($uri, $controller, 'PATCH');
    }

    public function put($uri, $controller)
    {
        return $this->add($uri, $controller, 'PUT');
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;
        return $this;
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] == strtoupper($method)) {

                if ($route['middleware']) {

                    Middleware::resolve($route['middleware']);
                }

                return require base_path('Http/controllers/' . $route['controller']);
            }
        }
        $this->abort();
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function abort($responseCode = Response::HTTP_NOT_FOUND)
    {
        http_response_code($responseCode);
        require base_path("views/{$responseCode}.php");
        exit;
    }

}

?>
