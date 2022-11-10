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
        return $this->view->render($response, 'signUp.twig', [
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
        return $this->view->render($response, 'signIn.twig', [
            'response' => $message,
            'account' => " : " . $_SESSION["username"],
        ]);
    }

    public function getGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $us = $this->userService->getUser('Léa');
        $userRes = $us->getGalleries();
        $text = "User: " . $us->getNameUser() . " <br> All galleries : ";
        foreach ($userRes as $user) {
            $text .= " " . $user;
        }
        echo  $text;
        return $this->view->render($response, 'app.twig');
    }
}
