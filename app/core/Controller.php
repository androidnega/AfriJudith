<?php
/**
 * Base Controller.
 *
 * - holds shared config
 * - loads models
 * - renders views wrapped in the main layout
 */

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Instantiate a model by short name: $this->model('Profile').
     */
    protected function model(string $name): object
    {
        $class = 'App\\Models\\' . ucfirst($name) . 'Model';
        if (!class_exists($class)) {
            throw new \RuntimeException("Model not found: {$name}");
        }
        return new $class($this->config);
    }

    /**
     * Render a view through the main layout.
     *
     *   $view   "home/index" -> app/views/home/index.php
     *   $data   variables exposed to both layout and view
     *   $layout layout file name without .php (false to skip layout)
     */
    protected function view(string $view, array $data = [], string|false $layout = 'main'): void
    {
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        // Detect base path so the site works equally well at the web root
        // (afrijudith.online/) or inside a subfolder (localhost/afrijudith.online/).
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

        // Expose helpers + config to every view.
        $data['app']    = $this->config['app']  ?? [];
        $data['base']   = $base;
        $data['asset']  = static fn (string $p) => $base . '/public/assets/' . ltrim($p, '/');
        $data['url']    = static fn (string $p = '') => $base . '/' . ltrim($p, '/');
        $data['e']      = static fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        extract($data, EXTR_SKIP);

        if ($layout === false) {
            require $viewFile;
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = APP_PATH . '/views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            echo $content;
            return;
        }
        require $layoutFile;
    }
}
