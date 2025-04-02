<?php

namespace services;
use models\Announce;
use services\AnnounceType;
require_once 'models/Announce.php';
require_once 'services/AnnounceType.php';

class AnnounceService{

    private $annouce;

    public function __construct($pdo){
        $this->annouce = new Announce($pdo);
    }

    /**
     * @throws \Exception
     */
    public function create($type, $title = null, $description = null, $image_url=null){

        if($type == AnnounceType::TEXT){
            if($title === null){
                throw new \Exception("Invalid coherence title is required");
            }
            if($description === null){
                throw new \Exception("Invalid coherence description is required");
            }
            if(strlen($description) < 3 || strlen($description) > 700){
                throw new \Exception("Invalid description length, must be between 200 and 700 characters");
            }
            if(strlen($title) < 3 || strlen($title) > 40){
                throw new \Exception("Invalid title length, must be between 3 and 40 characters");
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

    /**
     * @throws \Exception
     */
    public function updateById(int $id, string $title = null, string $description = null, string $image_url=null){
        $type = $this->annouce->getTypeById($id);

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
                throw new \Exception("Invalid title length, must be between 3 and 40 characters");
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

        return $this->annouce->updateById($id, $title, $description, $image_url);
    }

    /**
     * @throws \Exception
     */
    public function getAnnouceById($id){
        $announces = $this->annouce->findById($id);
        if($announces){
            return $announces;
        }
        throw new \Exception("Annouce not found");
    }

    public function getAllAnnouncesOrderedByPosition(){
        return $this->annouce->findAll();
    }

    /**
     * @throws \Exception
     */
    public function changeAnnounceOrder($id, $newPosition) {
        return $this->annouce->updatePosition($id, $newPosition);
    }

    public function reorderAnnounces() {
        return $this->annouce->reorderAll();
    }

    /**
     * @throws \Exception
     */
    public function deleteAnnounce($id): void
    {
        $deleted = $this->annouce->deleteById($id);
        if(!$deleted){
            throw new \Exception("Announce not found");
        }
    }

}