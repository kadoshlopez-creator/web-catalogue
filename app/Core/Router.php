<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected Request $request;
    protected Response $response;
    protected array $globalMiddlewares = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function registerGlobalMiddleware(Middleware $middleware): void
    {
        $this->globalMiddlewares[] = $middleware;
    }

    public function get(string $path, $callback): void
    {
        $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, $callback): void
    {
        $this->addRoute('POST', $path, $callback);
    }
    
    public function put(string $path, $callback): void
    {
        $this->addRoute('PUT', $path, $callback);
    }
    
    public function delete(string $path, $callback): void
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    protected function addRoute(string $method, string $path, $callback): void
    {
        // Convert route params like {id} into regex
        $pathRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $path);
        $pathRegex = "#^" . $pathRegex . "$#";
        
        $this->routes[$method][$pathRegex] = $callback;
    }

    public function resolve()
    {
        // Execute global middlewares
        foreach ($this->globalMiddlewares as $middleware) {
            $middleware->execute();
        }

        $path = $this->request->getUri();
        $method = $this->request->getMethod();

        $routes = $this->routes[$method] ?? [];
        
        foreach ($routes as $routeRegex => $callback) {
            if (preg_match($routeRegex, $path, $matches)) {
                
                // Extract named parameters from route
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                if (is_callable($callback)) {
                    return call_user_func_array($callback, $params);
                }

                if (is_array($callback)) {
                    /** @var Controller $controller */
                    $controller = new $callback[0]();
                    $controller->request = $this->request;
                    $controller->response = $this->response;
                    
                    // Execute middlewares
                    foreach ($controller->getMiddlewares() as $middleware) {
                        $middleware->execute();
                    }
                    
                    return call_user_func_array([$controller, $callback[1]], $params);
                }
            }
        }

        $this->response->setStatusCode(404);
        return View::render('errors.404');
    }
}
