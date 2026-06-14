<?php
/**
 * App — minimal front-controller / router.
 *
 *   /                    -> HomeController::index
 *   /about               -> AboutController::index   (if exists)
 *   /home/contact        -> HomeController::contact
 *   /home/show/42        -> HomeController::show(42)
 */

declare(strict_types=1);

namespace App\Core;

final class App
{
    private array $config;
    private string $controller = 'home';
    private string $action     = 'index';
    private array  $params     = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function run(): void
    {
        $url = $this->parseUrl();

        if (!empty($url[0])) {
            $this->controller = strtolower($url[0]);
            unset($url[0]);
        }

        if (!empty($url[1])) {
            $this->action = strtolower($url[1]);
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        $class = 'App\\Controllers\\' . ucfirst($this->controller) . 'Controller';

        if (!class_exists($class)) {
            $this->notFound("Controller '{$this->controller}' not found");
            return;
        }

        $instance = new $class($this->config);

        if (!method_exists($instance, $this->action)) {
            $this->notFound("Action '{$this->action}' not found");
            return;
        }

        call_user_func_array([$instance, $this->action], $this->params);
    }

    private function parseUrl(): array
    {
        $url = $_GET['url'] ?? '';
        $url = trim((string) $url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);

        if ($url === '') {
            $url = $this->config['default_route'] ?? 'home/index';
        }

        return explode('/', $url);
    }

    private function notFound(string $reason): void
    {
        http_response_code(404);
        $title   = 'Page not found';
        $message = $reason;
        require APP_PATH . '/views/errors/404.php';
    }
}
