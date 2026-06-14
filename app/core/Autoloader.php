<?php
/**
 * Tiny PSR-4 autoloader for the App\ namespace.
 *
 *   App\Core\App                    -> app/core/App.php
 *   App\Controllers\HomeController  -> app/controllers/HomeController.php
 *   App\Models\ProfileModel         -> app/models/ProfileModel.php
 *
 * Convention: every segment of the namespace below "App" becomes a
 * lower-cased folder; the final segment is the file name (case kept).
 */

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);
    }

    public static function load(string $class): void
    {
        if (!str_starts_with($class, 'App\\')) {
            return;
        }

        $parts  = explode('\\', substr($class, 4));
        $name   = array_pop($parts);
        $folder = strtolower(implode('/', $parts));

        $file = APP_PATH . ($folder === '' ? '' : '/' . $folder) . '/' . $name . '.php';

        if (is_file($file)) {
            require_once $file;
        }
    }
}
