<?php

namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\userService;


class userControler
{
    private $view;

    public function __construct(Twig $view, userService $userService)
    {
        $this->view = $view;
        $this->userService = $userService;
    }

    public function createUser(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $message = "Pas d'authentification.";
        if (isset($_POST['passwordConfirmation']) && isset($_POST['password']) && isset($_POST['username'])) {
            if ($_POST['passwordConfirmation'] == $_POST['password']) {

                $reserch = $this->userService->getUser(($_POST['username']));
                if ($reserch == null) {
                    $message = "Compte crée.";
                    $user = $this->userService->newUser($_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT));
                    return $this->view->render($response, 'signIn.twig');
                } else {
                    $message = "L'utilisateur existe déjà.";
                }
            } else {
                $message = "Mot de passe non valide.";
            }
        }
        return $this->view->render($response, 'index.twig', [
            'responseMessage' => $message,
        ]);
    }

    public function connecteUser(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $message = "Veuillez saisir une authentification correct.";
        if (isset($_POST['password']) && isset($_POST['username'])) {
            $reserch = $this->userService->getUser(($_POST['username']));
            if ($reserch == null) {
                $message = "Nom d'utilisateur invalide.";
            } else {
                $passwordUser = $this->userService->getPassword($_POST['username']);
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
          ]);
        } else {
          return $this->view->render($response, 'signIn.twig', [
              'response' => $message,
              'account' => " : " . $_SESSION["username"],
          ]);
        }
    }

    public function getGalleries()
    {
      $usr = $this->userService;
      // $adm = $usr->getUser($_SESSION('username'));
      $adm = $usr->getUser($_SESSION['username']);
      $gal = $adm->getGalleries();
      return $gal;
    }

    public function getIdByName($name) {
        return $this->userService->getUser($name)->getIdUser();
    }

    public function addGalleries($user,$Galleryname){
        return $this->userService->addGalleries($user,$Galleryname);
    }
}
