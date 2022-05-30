<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\ORM\ORMSetup;
use PmnGarage\Domain\Garage;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use PmnGarage\Application\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        EntityManagerInterface::class => function (ContainerInterface $c): EntityManagerInterface {
            /** @var array $settings */
            $settings = $c->get(SettingsInterface::class);
            $dbConfig = $settings->get('doctrine');

            $config = ORMSetup::createAttributeMetadataConfiguration(
                $dbConfig['metadata_dirs'],
                $dbConfig['dev_mode'],
            );

            $em = EntityManager::create($dbConfig['connection'], $config);

            // register custom types
            foreach ($dbConfig['dbal']['types'] as $name => $class) {
                Type::addType($name, $class);
            }

            foreach ($dbConfig['dbal']['mapping_types'] as $name => $type) {
                $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($name, $type);
            }

            return $em;
        },
        ClassMetadata::class => \DI\autowire(ClassMetadata::class)
            ->constructorParameter('entityName', Garage::class)
    ]);
};
