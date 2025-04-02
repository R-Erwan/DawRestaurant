<?php

namespace services;
use models\Announce;
use services\AnnounceType;
require_once 'models/Announce.php';
require_once 'services/AnnounceType.php';

class AnnouceService{

    private $annouce;

    public function __construct(Announce $pdo){
        $this->annouce = new Announce($pdo);
    }

    //TODO Gérer l'ordonnancement des announces
    public function create($type, $ordering, $title = null, $description = null, $image_url=null){

        if($type == AnnounceType::TEXT){
            if($title === null){
                throw new \Exception("Invalid coherence title is required");
            }
            if($description === null){
                throw new \Exception("Invalid coherence description is required");
            }
            if(strlen($description) < 200 || strlen($description) > 700){
                throw new \Exception("Invalid description length, must be between 200 and 700 characters");
            }
            if(strlen($title) < 3 || strlen($title) > 40){
                throw new \Exception("Invalid description length, must be between 3 and 40 characters");
            }
            $image_url=null;
        }

        if($type == AnnounceType::IMAGE){
            if($image_url === null){
                throw new \Exception("Invalid coherence image_url is required");
            }
            $title = null;
            $description = null;
        }

        return $this->annouce->create($type, $title, $description, $image_url);
    }

    public function getAnnouceById($id){

    }

    public function getAllAnnounces(){
        return $this->annouce->findAll();
    }

}