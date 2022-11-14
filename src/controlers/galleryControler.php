<?php

namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\galleryService;
use App\controlers\userControler;
use App\controlers\pictureControler;
use App\services\tagService;

class galleryControler
{
  private $view;

  public function __construct(Twig $view, galleryService $galleryService, pictureControler $pictureControler, userControler $userControler, tagService $tagService)
  {
    $this->view = $view;
    $this->galleryService = $galleryService;
    $this->userControler = $userControler;
    $this->pictureControler = $pictureControler;
    $this->tagService = $tagService;
  }

  public function addTagGallery($picture, $tagTab)
  {
    if (count($tagTab) > 5) {
      return "erreur trop de tags";
    }

    foreach ($tagTab as $tag) {
      $this->tagService->newTagGallery(trim($tag), $picture);
    }

    return "tag ajouté";
  }

  public function searchGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args)
  {
    $gal = $this->tagService->getTagGalleries($_POST['tag']);
    $taggedGalleries = [];

    foreach ($gal as &$gallery) {
      $backgroundImage = $this->pictureControler->getRandomPictureForBackground($gallery->getIdGallery());
      array_push($taggedGalleries, ["name" => $gallery, "id" => "/gallery/" . $gallery->getIdGallery(), "img" => "/".$backgroundImage]);
    }

    if (isset($_SESSION["username"])) {
      $account = $_SESSION["username"];
    } else {
      $account = "";
    }

    return $this->view->render($response, 'search.twig', [
      'tag' => $_POST['tag'],
        'account' => $account,
        'galleriesToShow' => $taggedGalleries,
    ]);
  }

  public function connecteAndShowPublicGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args)
  {
    $message = "Veuillez saisir une authentification correct.";
    if (isset($_POST['password']) && isset($_POST['username'])) {
      $reserch = $this->userControler->userService->getUser(($_POST['username']));
      if ($reserch == null) {
        $message = "Nom d'utilisateur invalide.";
      } else {
        $passwordUser = $this->userControler->userService->getPassword($_POST['username']);
        if (password_verify($_POST['password'], $passwordUser)) {
          $message = "Tu es connecté.";
          $_SESSION["username"] = ($_POST['username']);
        } else {
          $message = "Mot de passe invalide.";
        }
      }
    }
    if ($message == "Tu es connecté.") {
      $listGalleries = $this->galleryService->getGalleriesByPublicAccess();
      $galleriesAndId = [];
      foreach ($listGalleries as &$gallery) {
        $backgroundImage = $this->pictureControler->getRandomPictureForBackground($gallery->getIdGallery());
        array_push($galleriesAndId, ["name" => $gallery, "id" => "/gallery/" . $gallery->getIdGallery(), "img" => "/".$backgroundImage]);
      }
      return $this->view->render($response, 'index.twig', [
        'account' => $_SESSION["username"],
        'private' => false,
        'galleriesToShow' => $galleriesAndId,
      ]);
    } else {
      return $this->view->render($response, 'signIn.twig', [
        'response' => $message,
        'account' => "",
      ]);
    }
  }

  public function newGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $title = $_POST['titleGalerie'];
    $description = $_POST['descriptionGalerie'];
    $access = $_POST['drone'];
    $user = $_SESSION["username"];
    $userId = $this->userControler->getIdByName($user);
    $tag = $_POST['tags'];
    $tagTab = explode(",", $tag);

    $newGallery = $this->galleryService->newGallery($title, $access, $description, $userId);
    $mesTag = $this->addTagGallery($newGallery, $tagTab);
    $mesUsers = $this->userControler->addGalleries($user, $newGallery);
    return $this->view->render($response, 'uploadGallery.twig', [
      'account' => $_SESSION["username"],
      'resultMessage' => "La galerie vient d'être créée.",
    ]);
  }

  public function modifyGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $idGallery = $args['id_gallery'];
    $access = $_POST['drone'];

    $title = $_POST['titleGalerie'];
    $description = $_POST['descriptionGalerie'];

    if ($title == "") {
      $title = $this->galleryService->getGalleryId($idGallery)->getNameGallery();
    }

    if ($description == "") {
      $title = $this->galleryService->getGalleryId($idGallery)->getDescriptionGallery();
    }

    $this->galleryService->modifyGallery($idGallery, $title, $access, $description);

    return $this->view->render($response, 'modifyGallery.twig', [
      'id' => $idGallery,
      'account' => $_SESSION["username"],
      'resultMessage' => "La galerie vient d'être modifié.",
    ]);
  }

  public function addOwnerGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $idGallery = $args['id_gallery'];
    $name = $_POST['username'];

    $reserch = $this->userControler->userService->getUser($name);

    if ($reserch == null) {
      return $this->view->render($response, 'addOwner.twig', [
        'id' => $idGallery,
        'account' => $_SESSION["username"],
        'resultMessage' => "L'utilisateur n'existe pas.",
      ]);
    } else {
      $gallery = $this->galleryService->getGalleryId($idGallery);

      $this->userControler->addGalleries($name, $gallery);
    }

    return $this->view->render($response, 'addOwner.twig', [
      'id' => $idGallery,
      'account' => $_SESSION["username"],
      'resultMessage' => "Le participant vient d'être ajouté.",
    ]);
  }

  public function logoutAndShowPublicGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args)
  {
    unset($_SESSION["username"]);
    $listGalleries = $this->galleryService->getGalleriesByPublicAccess();
    $galleriesAndId = [];
    foreach ($listGalleries as &$gallery) {
      $backgroundImage = $this->pictureControler->getRandomPictureForBackground($gallery->getIdGallery());
      array_push($galleriesAndId, ["name" => $gallery, "id" => "/gallery/" . $gallery->getIdGallery(), "img" => "/".$backgroundImage]);
    }
    return $this->view->render($response, 'index.twig', [
      'account' => "",
      'private' => false,
      'galleriesToShow' => $galleriesAndId,
    ]);
  }

  public function getMyGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args)
  {
    if (isset($_SESSION["username"])) {
      $account = $_SESSION["username"];
    } else {
      $account = "";
    }
    $listGalleries = $this->galleryService->getPrivatesGalleries($this->userControler->getGalleries());
    $galleriesAndId = [];
    foreach ($listGalleries as &$gallery) {
      $backgroundImage = $this->pictureControler->getRandomPictureForBackground($gallery->getIdGallery());
      array_push($galleriesAndId, ["name" => $gallery, "id" => "/gallery/" . $gallery->getIdGallery(), "img" => "/".$backgroundImage]);
    }
    return $this->view->render($response, 'index.twig', [
      'account' => $account,
      'private' => true,
      'galleriesToShow' => $galleriesAndId,
    ]);
  }

  public function getAllPublicGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args)
  {
    if (isset($_SESSION["username"])) {
      $account = $_SESSION["username"];
    } else {
      $account = "";
    }
    $listGalleries = $this->galleryService->getGalleriesByPublicAccess();
    $galleriesAndId = [];
    foreach ($listGalleries as &$gallery) {
      $backgroundImage = $this->pictureControler->getRandomPictureForBackground($gallery->getIdGallery());
      array_push($galleriesAndId, ["name" => $gallery, "id" => "/gallery/" . $gallery->getIdGallery(), "img" => "/".$backgroundImage]);
    }
    return $this->view->render($response, 'index.twig', [
      'account' => $account,
      'private' => false,
      'galleriesToShow' => $galleriesAndId,
    ]);
  }

  public function deleteGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $idGallery = $_POST['galleryId'];
    $this->galleryService->deleteGallery($idGallery);
    return $this->getAllPublicGalleries($request, $response, $args);
  }
}
