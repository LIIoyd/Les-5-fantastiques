<?php

namespace App\controlers;

use App\services\galleryService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\pictureService;
use App\services\tagService;
use App\services\userService;

class pictureControler
{
    private $view;

    public function __construct(Twig $view, pictureService $pictureService, galleryService $galleryService, userService $userService, tagService $tagService)
    {
        $this->view = $view;
        $this->pictureService = $pictureService;
        $this->tagService = $tagService;
        $this->galleryService = $galleryService;
        $this->userService = $userService;
    }


    public function start(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        echo " image";
        return $this->view->render($response, 'app.twig');
    }

    public function addTag($picture,$tagTab){
        if(count($tagTab) > 5){
            return "erreur trop de tags" ;
        }

        foreach($tagTab as $tag){
            $this->tagService->newtag(trim($tag),$picture);
        }

        return "tag ajouté";
    }

    public function addImage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $title = $_POST['title'];
        $des = $_POST['description'];
        $tag = $_POST['tags'];
        $tagTab = explode(",",$tag);

        if(isset($args['id_gallery']))  {
            $idGallery = $args['id_gallery'];
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
                'resultMessage' => $mes,'id' => $idGallery,
                'account' => $acc ]);
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $imgIfno = getimagesize($target_file);
            $height = $imgIfno[1];
            $width = $imgIfno[0];
            $picture = $this->pictureService->newPicture($target_file,$title,$height,$width,$des,$idGallery);
            $mesTag = $this->addTag($picture,$tagTab);
            $mes = "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded." . $mesTag;
            return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes , 'id' => $idGallery, 'account' => $acc ]);

        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000000) {
            $mes = "Sorry, your file is too large.";
            $uploadOk = 0;
            return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes , 'id' => $idGallery, 'account' => $acc , ]);
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
            $mes = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes ,  'id' => $idGallery ,'account' => $acc ]);
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk !== 0) {

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $imgIfno = getimagesize($target_file);
                $height = $imgIfno[1];
                $width = $imgIfno[0];
                $picture = $this->pictureService->newPicture($target_file,$title,$height,$width,$des,$idGallery);
                $mesTag = $this->addTag($picture,$tagTab);
                $mes = "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
            } else {
                $mes = "Sorry, there was an error uploading your file.";
            }
        }
        return $this->view->render($response, 'upload.twig',[
            'resultMessage' => $mes ." ". $mesTag,  'id' => $idGallery,
            'account' => $acc ]);
    }

    public function displayGalleryPic(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        if (isset($_SESSION["username"])) {
            $account = $_SESSION["username"];
        } else {
            $account = "";
        }
        $gallery = $this->galleryService->getGalleryId($args['id_gallery']);
        $userPic = $this->userService->getUserId($gallery->getIdCreator());

        $pictures = $this->pictureService->getAllPicturesGallery($args['id_gallery']);


        if(isset($args['page'])){
            $currentPage = ($args['page']);
        }else{
            $currentPage = 1;
        }

        $parPage = 10;
        $pages = ceil(count($pictures) / $parPage);
        $premier = ($currentPage * $parPage) - $parPage;

        $tabResPicture = [];

        for($i = $premier; ($i < count($pictures)) && ($i <  $currentPage + $parPage -1) ; $i++){
            $tabResPicture[$i] = $pictures[$i];
        }

        $previous = $currentPage - 1;
        if($previous < 1){
            $previous = 1;
        }
        $next = $currentPage + 1;
        if($next > $pages){
            $next = $pages;
        }



        $tags = $gallery->getTags()->getValues();
        if($account == ""){
            $admin = false;
        }else{
            $userRead = $this->userService->getUser($account);
            $idRead = $userRead->getIdUser();
            $idUserPic = $userPic->getIdUser();
            if($idRead == $idUserPic){
                $admin = true;
            }else{
                $admin = false;
            }
        }

        $view = Twig::fromRequest($request);
        return $view->render($response, 'gallery.twig', [
            "tags"=>$tags,
            "admin" =>$admin,
            "account" => $account,
            "id" => $gallery->getIdGallery(),
            "title" => $gallery->getNameGallery(),
            "descr" => $gallery->getDescriptionGallery(),
            "createur" => $userPic->getNameUser(),
            "date" => $gallery->getDateCreat()->format('d-m-Y'),
            "images" => $tabResPicture,
            "pages" => $pages,
            "nextPage" => $next,
            "previousPage" => $previous,
            "currentPage" => $currentPage,
            "numbPic" => count($pictures)
        ]);
    }

    public function getRandomPictureForBackground($idGallery){
      $listObjects = $this->pictureService->getAllPicturesGallery($idGallery);
      if (count($listObjects)>0) {
        $random = rand(0,(count($listObjects)-1));
        return $listObjects[$random]->getLink();
      } else {
        return "media/logo.png";
      }
    }

    public function displayPicture(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
      $view = Twig::fromRequest($request);
      $prec = "/gallery/".$args['id_gallery']."/picture/".((intval($args['id_picture']))-1);
      $suiv = "/gallery/".$args['id_gallery']."/picture/".((intval($args['id_picture']))+1);
      $images = $this->pictureService->getAllPicturesGallery($args['id_gallery']);
      $stuff = array_values((array) $images[$args['id_picture']]);


      if ($args['id_picture']==0) {
        $prec = "hidden";
      }
      if ($args['id_picture']==(count($images)-1)) {
        $suiv = "hidden";
      }
      return $view->render($response, 'picture.twig', [
        "id" => $args['id_gallery'],
        "title" => $stuff[2],
        "descr" => $stuff[5],
        "height" => $stuff[3],
        "width" => $stuff[4],
        "photo" => "/".$stuff[1],
        "tags" => $this->pictureService->getTagsImageById($stuff[0]),
        "prec" => $prec,
        "suiv" => $suiv
      ]);
    }

    public function deletePicture(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $this->pictureService->deletePicture($_POST['pictureId']);
        return $this->displayGalleryPic($request, $response, $args);
    }

}
