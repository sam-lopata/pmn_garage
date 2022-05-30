<?php

use DI\Container;
use Doctrine\ORM\EntityManager;

/** @var Container $container */
$container = require_once __DIR__ . '/bootstrap.php';

$em = $container->get(EntityManager::class);
try {
    $em->getConnection()->connect();
    echo "Success" . PHP_EOL;
} catch (\Exception $e) {
    echo "Failed: " . $e->getMessage() . PHP_EOL;
}

