<?php

namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\pictureService;

class pictureControler
{
    private $view;

    public function __construct(Twig $view, pictureService $pictureService)
    {
        $this->view = $view;
        $this->pictureService = $pictureService;
    }


    public function start(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        echo " image";
        return $this->view->render($response, 'app.twig');
    }

    public function addImage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $title = $_POST['title'];
        $des = $_POST['description'];
        if(isset($_SESSION['id_gallery']))  { 
            $idGallery = $_SESSION['id_gallery'];
        }else { 
            $idGallery = 1;
        } 
        if (isset($_SESSION["username"])) {
           $acc = " : " . $_SESSION["username"];
         } else {
        $acc ='account';
     }
  
        //$this->pictureService->newPicture();
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $mes = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $mes = "File is not an image.";
                $uploadOk = 0;
                return $this->view->render($response, 'upload.twig',[
                'resultMessage' => $mes,
                'account' => $acc,]);
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $imgIfno = getimagesize($target_file);
            $height = $imgIfno[1];
            $width = $imgIfno[0];
            $this->pictureService->newPicture($target_file,$title,$height,$width,$des,$idGallery);
            $mes = "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
            return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes , 'account' => $acc]);
       
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000000) {
            $mes = "Sorry, your file is too large.";
            $uploadOk = 0;
            return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes , 'account' => $acc]);
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
            $mes = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes ,'account' => $acc]);
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk !== 0) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $mes = "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
                $imgIfno = getimagesize($target_file);
                $height = $imgIfno[1];
                $width = $imgIfno[0];
                $this->pictureService->newPicture($target_file,$title,$height,$width,$des,$idGallery);
            } else {
                $mes = "Sorry, there was an error uploading your file.";
            }
        }
        return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes, 
            'account' => $acc]);
    }
}
