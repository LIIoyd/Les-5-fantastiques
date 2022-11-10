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

session_start();

// Create Twig:
$twig = Twig::create('../templates', ['cache' => false]);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);

    if (isset($_SESSION["username"])) {
        return $view->render($response, 'index.twig', [
            'account' => " : " . $_SESSION["username"],
        ]);
    } else {
        return $view->render($response, 'index.twig', [
            'account' => ""
        ]);
    }
});

$app->get('/ViewGallery', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'gallery.twig', []);
});


$app->get('/signUp', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    if (isset($_SESSION["username"])) {
        return $view->render($response, 'signUp.twig', [
            'account' => " : " . $_SESSION["username"],
        ]);
    } else {
        return $view->render($response, 'signUp.twig', [
            'account' => "",
        ]);
    }
});

$app->get('/signIn', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    if (isset($_SESSION["username"])) {
        return $view->render($response, 'signIn.twig', [
            'account' => " : " . $_SESSION["username"],
        ]);
    } else {
        return $view->render($response, 'signIn.twig', [
            'account' => "",
        ]);
    }
});

$app->get('/addImage', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    if (isset($_SESSION["username"])) {
        return $view->render($response, 'upload.twig', [
            'account' => " : " . $_SESSION["username"],
        ]);
    } else {
        return $view->render($response, 'index.twig', [
            'account' => "",
        ]);
    }
});

$app->get('/addGallery', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    if (isset($_SESSION["username"])) {
        return $view->render($response, 'uploadGallery.twig', [
            'account' => " : " . $_SESSION["username"],
        ]);
    } else {
        return $view->render($response, 'index.twig', [
            'account' => "",
        ]);
    }
});

$app->get('/home', \App\controlers\galleryControler::class . ':getAllPublicGalleries');

$app->get('/myGalleries', \App\controlers\galleryControler::class . ':getMyGalleries');

$app->post('/addImage', \App\controlers\pictureControler::class . ':addImage');

$app->post('/createUser', \App\controlers\userControler::class . ':createUser');

$app->post('/connecteUser', \App\controlers\galleryControler::class . ':connecteAndShowPublicGalleries');

$app->get('/logout', \App\controlers\galleryControler::class . ':logoutAndShowPublicGalleries');

$app->get('/userGallery', \App\controlers\userControler::class . ':getGalleries');

$app->post('/newGallery', \App\controlers\galleryControler::class . ':newGallery');

$app->get('/getGallery', \App\controlers\galleryControler::class . ':getGallery');

$app->get('/getUsers', \App\controlers\galleryControler::class . ':getUsers');

$app->get('/picture', \App\controlers\pictureControler::class . ':start');

$app->get('/gallery/{id_gallery}', \App\controlers\pictureControler::class . ':displayGalleryPic');


// Run app
$app->run();
