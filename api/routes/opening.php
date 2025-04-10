<?php

global $pdo;
global $path;
use middleware\AuthMiddleware;
use controllers\OpeningBasicController;
require_once 'middleware/AuthMiddleware.php';
require_once 'controllers/OpeningBasicController.php';

$openingBasicController = new OpeningBasicController($pdo);

$subPath = substr($path, strlen('opening'));
$subPath = trim($subPath, '/');

switch (true) {
    case $subPath === '':
        // /api/opening
        echo json_encode(["message" => "Opening rules endpoint"]);
        break;

    case str_starts_with($subPath, 'basic'):
        // GET /api/opening/basic?date=JJ-MM-AAAA
        if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['date'])) {
            $openingBasicController->getOpeningBasicByDate($_GET['date']);
            exit;
        }
        // GET /api/opening/basic
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $openingBasicController->getAllOpeningsBasic();
            exit;
        }
        // POST /api/opening/basic
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $openingBasicController->createOpeningBasicRule($input);
            exit;
        }
        // PUT /api/opening/basic
        if($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $input = json_decode(file_get_contents('php://input'), true);
            $openingBasicController->updateOpeningBasicRule($input);
            exit;
        }
        // DELETE /api/opening/basic?id_time=X
        if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $openingBasicController->deleteOpeningBasicRule();
            exit;
        }

        http_response_code(404);
        echo json_encode(['error' => 'Invalid opening basic route']);
        break;

    case str_starts_with($subPath, 'exceptions'):
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Invalid opening route']);
}
