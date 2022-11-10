<?php

// bootstrap.php

use App\controlers\userControler;
use App\services\userService;
use App\controlers\galleryControler;
use App\services\galleryService;
use App\controlers\pictureControler;
use App\services\pictureService;
use App\services\tagService;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use UMA\DIC\Container;

require_once __DIR__ . '/vendor/autoload.php';


$container = new Container(require __DIR__ . '/settings.php');


$container->set(LoggerInterface::class, function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Logger($settings['name']);
    $logger->pushHandler(new StreamHandler($settings['path'], Level::Debug));
    return $logger;
});

$container->set(EntityManager::class, static function (Container $c): EntityManager {
    /** @var array $settings */
    $settings = $c->get('settings');

    // Use the ArrayAdapter or the FilesystemAdapter depending on the value of the 'dev_mode' setting
    // You can substitute the FilesystemAdapter for any other cache you prefer from the symfony/cache library
    $cache = $settings['doctrine']['dev_mode'] ?
        DoctrineProvider::wrap(new ArrayAdapter()) :
        DoctrineProvider::wrap(new FilesystemAdapter(directory: $settings['doctrine']['cache_dir']));

    $config = Setup::createAttributeMetadataConfiguration(
        $settings['doctrine']['metadata_dirs'],
        $settings['doctrine']['dev_mode'],
        null,
        $cache
    );

    return EntityManager::create($settings['doctrine']['connection'], $config);
});


$container->set('view', function () {
    return Twig::create(
        __DIR__ . '/templates'
        //['cache' => __DIR__ . '/cache']
    );
});

$container->set(userService::class, static function (Container $c) {
    return new userService($c->get(EntityManager::class), $c->get(LoggerInterface::class));
});

$container->set(userControler::class, static function (ContainerInterface $container) {
    $view = $container->get('view');
    return new userControler($view, $container->get(userService::class));
});


$container->set(galleryService::class, static function (Container $c) {
    return new galleryService($c->get(EntityManager::class), $c->get(LoggerInterface::class));
});

$container->set(tagService::class, static function (Container $c) {
    return new tagService($c->get(EntityManager::class), $c->get(LoggerInterface::class));
});


$container->set(galleryControler::class, static function (ContainerInterface $container) {
    $view = $container->get('view');
    return new galleryControler($view, $container->get(galleryService::class), $container->get(userControler::class),$container->get(tagService::class));
});


$container->set(pictureService::class, static function (Container $c) {
    return new pictureService($c->get(EntityManager::class), $c->get(LoggerInterface::class));
});

$container->set(pictureControler::class, static function (ContainerInterface $container) {
    $view = $container->get('view');
    return new pictureControler($view, $container->get(pictureService::class),$container->get(tagService::class));
});

return $container;
