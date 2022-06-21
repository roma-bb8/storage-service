<?php declare(strict_types=1);

define('SERVICE_ROOT_DIR', dirname(__DIR__));

if (file_exists(__DIR__ . '/bootstrap-local.php')) {
    require_once __DIR__ . '/bootstrap-local.php';
}

if (!defined('SERVICE_ENV')) {
    define('SERVICE_ENV', (getenv('SERVICE_ENV') ?: 'production'));
}

require_once SERVICE_ROOT_DIR . '/vendor/autoload.php';
