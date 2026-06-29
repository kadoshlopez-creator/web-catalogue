<?php

namespace App\Core;

abstract class Controller
{
    public Request $request;
    public Response $response;
    
    /**
     * @var Middleware[]
     */
    protected array $middlewares = [];
    protected ?string $layout = null;

    protected function render(string $view, array $data = []): string
    {
        return View::render($view, $data, $this->layout);
    }
    
    protected function redirect(string $url): void
    {
        $this->response->redirect($url);
    }
    
    protected function registerMiddleware(Middleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }
    
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
