<?php
/**
 * AfriJudith.online — Front Controller
 *
 * All HTTP traffic enters here. The .htaccess rewrites any non-asset
 * request to this file, which boots the framework and dispatches the
 * URL to a controller/action pair.
 *
 * Structure (MVC):
 *   app/core         framework primitives (Router, base Controller, base Model)
 *   app/controllers  request handlers
 *   app/models       domain models (DB-ready)
 *   app/views        templates rendered by controllers
 *   config           application configuration
 *   public/assets    css, js, images
 */

declare(strict_types=1);

define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');
define('PUBLIC_PATH', APP_ROOT . '/public');

require_once APP_PATH . '/core/Autoloader.php';
\App\Core\Autoloader::register();

$config = require APP_ROOT . '/config/config.php';

$app = new \App\Core\App($config);
$app->run();
