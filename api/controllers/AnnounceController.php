<?php

namespace controllers;
use services\AnnounceService;

class AnnounceController{

    private $annouceService;
    public function __construct(\PDO $pdo){
        $this->annouceService = new AnnounceService($pdo);
    }

    public function createAnnounce($data): void {
        if(!isset($data['type'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Type is required']);
            exit;
        }

        try {
            $result = $this->annouceService->create(
                $data['type'],
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['image_url'] ?? null
            );
            http_response_code(200);
            echo json_encode(["message" => "Annouce created successfully", "result" => $result]);
        } catch(\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function getAllAnnouces(): void {
        try {
            $result = $this->annouceService->getAllAnnouncesOrderedByPosition();
            http_response_code(200);
            echo json_encode(["message" => "Annonces retrieved successfully", "result" => $result]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function updateAnnounce($data): void {
        if(!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Id is required']);
            exit;
        }
        try {
            $result = $this->annouceService->updateById(
                $data['id'],
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['image_url'] ?? null
            );
            http_response_code(200);
            echo json_encode(["message" => "Annonce updated successfully"]);
        } catch (\Exception $e){
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function updatePositions($data) :void {
        if(!isset($data['positions']) || !is_array($data['positions'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid positions format']);
            exit;
        }
        $positions = $data['positions'];

        // Vérifier que toutes les annonces existent
        $allAnnounces = $this->annouceService->getAllAnnouncesOrderedByPosition();
        $announceIds = array_column($allAnnounces, 'id');
        if (count($positions) !== count($announceIds) || array_diff($announceIds, $positions)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid positions array']);
            exit;
        }

        // Mettre à jour les positions
        try {
            foreach ($positions as $newPosition => $announceId) {
                $this->annouceService->changeAnnounceOrder($announceId, $newPosition + 1);
            }
        } catch(\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }

        $this->annouceService->reorderAnnounces(); //Réordonne les positions

        http_response_code(200);
        echo json_encode(['message' => 'Positions updated successfully']);
    }

    public function deleteAnnounce(): void {
        if(!isset($_GET['announce_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'announce_id is required']);
        }
        try {
            $this->annouceService->deleteAnnounce($_GET['announce_id']);
            $this->annouceService->reorderAnnounces(); //Réordonne les positions
            http_response_code(200);
            echo json_encode(['message' => 'Announce deleted successfully']);
        } catch(\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}