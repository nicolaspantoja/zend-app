<?php

namespace Album;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlbumTable::class => function ($container) {
                    $tableGatway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGatway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resulteSetPrototype = new ResultSet();
                    $resulteSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('album', $dbAdapter, null, $resulteSetPrototype);
                }
            ]
        ];
    }
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function ($container){
                    return new Controller\AlbumController($container->get(Model\AlbumTable::class),
                );
                }
            ]
        ];
    }
}
