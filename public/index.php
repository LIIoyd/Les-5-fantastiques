<?php
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

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

$app->get('/home', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'home.twig', []);
});

$app->get('/signUp', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'signUp.twig', []);
});

$app->get('/signIn', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'signIn.twig', []);
});

// Run app
$app->run();

