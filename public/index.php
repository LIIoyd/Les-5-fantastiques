<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Slim\Views\Twig;

require __DIR__ . '/../vendor/autoload.php';
$container = require_once __DIR__ . '/../bootstrap.php';

AppFactory::setContainer($container);

// Create App
$app = AppFactory::create();

// Create Twig:
$twig = Twig::create('../templates', ['cache' => false]);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'index.twig', []);
});

$app->get('/signUp', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'signUp.twig', []);
});

$app->get('/signIn', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'signIn.twig', []);
});

//$app->get('/user', \App\controlers\userControler::class . ':createUser');

$app->get('/userGallery', \App\controlers\userControler::class . ':getGalleries');

$app->get('/NewGallery', \App\controlers\galleryControler::class . ':newGallery');

$app->get('/GetGallery', \App\controlers\galleryControler::class . ':getGallery');

$app->get('/GetUsers', \App\controlers\galleryControler::class . ':getUsers');

$app->get('/picture', \App\controlers\pictureControler::class . ':start');

// Run app
$app->run();