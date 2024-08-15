<?php

define('ROOT_DIR', __DIR__);

function megaAutoload($class): void
{
    static $config = [];
    if (empty($config)) {
        $config = require_once ROOT_DIR . '/config/autoload.php';
    }

    $mapping = $config['mapping'];

    $parts = explode('\\', $class);

    $targetRootDir = $mapping[$parts[0]];
    unset($parts[0]);
    array_unshift($parts, $targetRootDir);

    $finalClassPath = implode('/', $parts);
    require_once ROOT_DIR . $finalClassPath . '.php';
}

spl_autoload_register('megaAutoload', true, true);
