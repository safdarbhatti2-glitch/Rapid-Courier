<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function get(string $path, mixed $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, mixed $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, mixed $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, mixed $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, mixed $handler, array $middleware): void
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'     => $method,
            'path'       => $path,
            'pattern'    => $pattern,
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->getMethod();
        $uri    = $request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Run route middleware pipeline
                foreach ($route['middleware'] as $mwClass) {
                    if (class_exists($mwClass)) {
                        $mw = new $mwClass();
                        $mw->handle($request);
                    }
                }

                $handler = $route['handler'];

                if (is_callable($handler)) {
                    call_user_func_array($handler, array_merge([$request], $params));
                    return;
                }

                if (is_array($handler) && count($handler) === 2) {
                    list($controllerClass, $methodName) = $handler;
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $methodName)) {
                            call_user_func_array([$controller, $methodName], array_merge([$request], $params));
                            return;
                        }
                    }
                }

                Response::error("Handler for route {$uri} not found", [], 500);
                return;
            }
        }

        if ($request->isJson()) {
            Response::error("Route {$method} {$uri} not found", [], 404);
        } else {
            View::render('public.404', ['uri' => $uri], 'main');
        }
    }
}
