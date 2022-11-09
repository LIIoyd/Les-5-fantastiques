<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Slim\Views\Twig;

require __DIR__ . '/../vendor/autoload.php';

$container = require_once __DIR__ . '/../bootstrap.php';
 
AppFactory::setContainer($container);

$app = AppFactory::create();

$twig = Twig::create('../templates', ['cache' => false]);

$app->add(TwigMiddleware::create($app,$twig));

//$app->get('/user', \App\controlers\userControler::class . ':createUser');

$app->get('/userGallery', \App\controlers\userControler::class . ':getGallery');

$app->get('/NewGallery', \App\controlers\galleryControler::class . ':newGallery');

$app->get('/GetGallery', \App\controlers\galleryControler::class . ':getGallery');

$app->get('/GetUsers', \App\controlers\galleryControler::class . ':getUsers');

$app->get('/picture', \App\controlers\pictureControler::class . ':start');

$app->run();