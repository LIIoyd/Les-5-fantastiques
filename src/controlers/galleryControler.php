<?php
namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\galleryService;
use App\controlers\userControler;
use App\services\tagService;

class galleryControler
{
    private $view;

    public function __construct(Twig $view, galleryService $galleryService, userControler $userControler,tagService $tagService)
    {
        $this->view = $view;
        $this->galleryService = $galleryService;
        $this->userControler = $userControler;
        $this->tagService = $tagService;
    }

    public function addTagGallery($picture,$tagTab){
        if(count($tagTab) > 5){
            return "erreur trop de tags" ;
        }

        foreach($tagTab as $tag){
            $this->tagService->newTagGallery(trim($tag),$picture);
        }

        return "tag ajouté";
    }

    public function newGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $title = $_POST['titleGalerie'];
        $description = $_POST['descriptionGalerie'];
        $access = $_POST['drone'];
        $user = $_SESSION["username"];
        $userId = $this->userControler->getIdByName($user);
        $tag = $_POST['tags'];
        $tagTab = explode(",",$tag);

        $newGallery = $this->galleryService->newGallery($title, $access, $description, $userId);
        $mesTag = $this->addTagGallery($newGallery,$tagTab);
        return $this->view->render($response, 'uploadGallery.twig', [
            'account' => $_SESSION["username"],
            'resultMessage' => "La galerie vient d'être créée.",
        ]);
    }

    public function logoutAndShowPublicGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args) {
      unset($_SESSION["username"]);
      return $this->view->render($response, 'index.twig', [
        'account' => "",
        'private' => false,
        'galleriesToShow' => $this->galleryService->getGalleriesByPublicAccess(),
      ]);
    }

    public function connecteAndShowPublicGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args) {
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
        return $this->view->render($response, 'index.twig', [
            'account' => " : " . $_SESSION["username"],
            'private' => false,
            'galleriesToShow' => $this->galleryService->getGalleriesByPublicAccess(),
        ]);
      } else {
        return $this->view->render($response, 'signIn.twig', [
            'response' => $message,
            'account' => "",
        ]);
      }
    }

    public function getAllPublicGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args) {
      if (isset($_SESSION["username"])) {
              $account = " : " . $_SESSION["username"];
      } else {
              $account = "";
      }
      return $this->view->render($response, 'index.twig', [
        'account' => $account,
        'private' => false,
        'galleriesToShow' => $this->galleryService->getGalleriesByPublicAccess(),
      ]);
    }

    public function getMyGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args) {
      if (isset($_SESSION["username"])) {
              $account = " : " . $_SESSION["username"];
      } else {
              $account = "";
      }
      return $this->view->render($response, 'index.twig', [
        'account' => $account,
        'private' => true,
        'galleriesToShow' => $this->galleryService->getPrivatesGalleries($this->userControler->getGalleries()),
      ]);
    }

    public function getGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $gal = $this->galleryService->getGallery('photo mignon');
        echo $gal;
        var_dump($gal->getUsers());
        return $this->view->render($response, 'app.twig');
    }

    public function getUsers(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $gal = $this->galleryService->getGallery('test');
        $galRes = $gal->getUsers();
        $text = "Nom de galerie: " . $gal->getNameGallery() . " <br> Users : ";
        foreach($galRes as $galleries){
            $text .= " " . $galleries;
        }
        echo $text;
        return $this->view->render($response, 'app.twig');
    }
}
