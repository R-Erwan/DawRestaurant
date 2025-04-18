<?php

namespace controllers;

use Exception;
use PDO;
use services\AnnounceService;

class AnnounceController
{

    private AnnounceService $announceService;

    public function __construct(PDO $pdo)
    {
        $this->announceService = new AnnounceService($pdo);
    }

    public function createAnnounce(mixed $data): never
    {
        if (!isset($data['type'])) {
            respond(false, "Invalid type", 400);
        }
        try {
            $result = $this->announceService->create(
                $data['type'],
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['image_url'] ?? null
            );
            respond(true, "Announce created successfully", 200, ["id" => $result]);
        } catch (Exception $e) {
            respond(false, "Can not create announce : " . $e->getMessage(), 400);
        }
    }

    public function getAllAnnounces(): never
    {
        try {
            $result = $this->announceService->getAllAnnouncesOrderedByPosition();
            respond(true, "Annonces retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, 400, "Can't retrieve announces : " . $e->getMessage());
        }
    }

    public function updateAnnounce(mixed $data): never
    {
        if (!isset($data['id'])) {
            respond(false, "Missing required field ID", 400);
        }
        try {
            $this->announceService->updateById(
                $data['id'],
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['image_url'] ?? null
            );
            respond(true, "Announce updated successfully");
        } catch (Exception $e) {
            respond(false, "Can not update announce : " . $e->getMessage(), 400);
        }
    }

    public function updatePositions(mixed $data): never
    {
        if (!isset($data['positions']) || !is_array($data['positions'])) {
            respond(false, "Missing required field, or invalid format", 400);
        }
        $positions = $data['positions'];

        // Vérifier que toutes les annonces existent
        $allAnnounces = $this->announceService->getAllAnnouncesOrderedByPosition();
        $announceIds = array_column($allAnnounces, 'id');
        if (count($positions) !== count($announceIds) || array_diff($announceIds, $positions)) {
            respond(false, "Invalid positions array", 400);
        }

        // Mettre à jour les positions
        try {
            foreach ($positions as $newPosition => $announceId) {
                $this->announceService->changeAnnounceOrder($announceId, $newPosition + 1);
            }
        } catch (Exception $e) {
            respond(false, "Can not change positions : " . $e->getMessage(), 400);
        }

        $this->announceService->reorderAnnounces(); //Réordonne les positions

        respond(true, "Announces updated successfully");
    }

    public function deleteAnnounce(): never
    {
        if (!isset($_GET['announce_id'])) {
            respond(false, "Missing required field", 400);
        }
        try {
            $this->announceService->deleteAnnounce($_GET['announce_id']);
            $this->announceService->reorderAnnounces(); //Réordonne les positions
            respond(true, "Announces deleted successfully");
        } catch (Exception $e) {
            respond(false, "Can not delete announce : " . $e->getMessage(), 400);
        }
    }
}