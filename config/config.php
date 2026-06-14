<?php
/**
 * Application configuration.
 * Returned as a plain array so it can be cached and injected freely.
 */

declare(strict_types=1);

return [

    'app' => [
        'name'     => 'AfriJudith.online',
        'tagline'  => 'Data Analyst • Web Developer',
        'base_url' => '/',
        'env'      => 'development', // production | development
    ],

    /**
     * Default route when no URL is provided.
     * "controller/action" — both must exist.
     */
    'default_route' => 'home/index',

    /**
     * Database settings. Not used yet but reserved so that the
     * future migration to a DB-backed profile is a drop-in change.
     */
    'database' => [
        'enabled'  => false,
        'driver'   => 'mysql',
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'name'     => 'afrijudith',
        'user'     => 'root',
        'pass'     => '',
        'charset'  => 'utf8mb4',
    ],
];
