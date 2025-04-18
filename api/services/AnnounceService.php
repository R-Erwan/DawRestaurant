<?php

namespace services;

use Exception;
use models\Announce;
use PDO;

class AnnounceService
{

    private Announce $announce;

    public function __construct(PDO $pdo)
    {
        $this->announce = new Announce($pdo);
    }

    /**
     * @throws Exception
     */
    public function create(string $type, string $title = null, string $description = null, string $image_url = null): string
    {

        if ($type == AnnounceType::TEXT) {
            if ($title === null) {
                throw new Exception("Invalid coherence title is required");
            }
            if ($description === null) {
                throw new Exception("Invalid coherence description is required");
            }
            if (strlen($description) < 3 || strlen($description) > 700) {
                throw new Exception("Invalid description length, must be between 200 and 700 characters");
            }
            if (strlen($title) < 3 || strlen($title) > 40) {
                throw new Exception("Invalid title length, must be between 3 and 40 characters");
            }
            $image_url = null;
        }

        if ($type == AnnounceType::IMAGE) {
            if ($image_url === null) {
                throw new Exception("Invalid coherence, image_url is required");
            }
            $title = null;
            $description = null;
        }
        $result = $this->announce->create($type, $title, $description, $image_url);
        if(!$result) {
            throw new Exception("Announce creation failed");
        }
        return  $result;
    }

    /**
     * @throws Exception
     */
    public function updateById(int $id, string $title = null, string $description = null, string $image_url = null): bool
    {
        $type = $this->announce->getTypeById($id);

        if ($type == AnnounceType::TEXT) {
            if ($title === null) {
                throw new Exception("Invalid coherence title is required");
            }
            if ($description === null) {
                throw new Exception("Invalid coherence description is required");
            }
            if (strlen($description) < 200 || strlen($description) > 630) {
                throw new Exception("Invalid description length, must be between 200 and 700 characters");
            }
            if (strlen($title) < 3 || strlen($title) > 40) {
                throw new Exception("Invalid title length, must be between 3 and 40 characters");
            }
            $image_url = null;
        }

        if ($type == AnnounceType::IMAGE) {
            if ($image_url === null) {
                throw new Exception("Invalid coherence image_url is required");
            }
            $title = null;
            $description = null;
        }

        return $this->announce->updateById($id, $title, $description, $image_url);
    }

    /**
     * @internal This function is not used yet but may be used in a future version.
     * @throws Exception
     */
    public function getAnnounceById(int $id)
    {
        $announces = $this->announce->findById($id);
        if ($announces) {
            return $announces;
        }
        throw new Exception("Announce not found");
    }

    public function getAllAnnouncesOrderedByPosition(): array
    {
        return $this->announce->findAll();
    }

    /**
     * @throws Exception
     */
    public function changeAnnounceOrder(int $id, int $newPosition): ?bool
    {
        return $this->announce->updatePosition($id, $newPosition);
    }

    public function reorderAnnounces(): bool
    {
        return $this->announce->reorderAll();
    }

    /**
     * @throws Exception
     */
    public function deleteAnnounce(int $id): void
    {
        $deleted = $this->announce->deleteById($id);
        if (!$deleted) {
            throw new Exception("Announce not found");
        }
    }

}