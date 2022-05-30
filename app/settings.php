<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
use PmnGarage\Application\Settings\Settings;
use PmnGarage\Application\Settings\SettingsInterface;

const APP_ROOT = __DIR__;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => true,
                'logErrorDetails'     => true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'doctrine' => [
                    'dev_mode' => true,
                    'cache_dir' => APP_ROOT . '/../var/doctrine',
                    'metadata_dirs' => [APP_ROOT . '/../src/Domain'],
                    'connection' => [
                        'driver' => 'pdo_mysql',
                        'host' => 'mysql',
                        'port' => 3306,
                        'dbname' => 'pmn_garage',
                        'user' => 'pmn_garage',
                        'password' => 'pmn_garage',
                        'charset' => 'utf8mb4'
                    ],
                    'dbal' => [
                        'types' => [
                            'point' => 'CrEOF\Spatial\DBAL\Types\Geography\PointType'
                        ],
                        'mapping_types' => [
                            'point' => 'point'
                        ]
                    ]
                ]
            ]);
        }
    ]);
};
