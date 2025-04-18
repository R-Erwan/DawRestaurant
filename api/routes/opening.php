<?php

global $pdo;
global $path;

use middleware\AuthMiddleware;
use controllers\OpeningBasicController;
use controllers\OpeningExceptionController;

$openingBasicController = new OpeningBasicController($pdo);
$openingExceptionController = new OpeningExceptionController($pdo);

$subPath = substr($path, strlen('opening'));
$subPath = trim($subPath, '/');

switch (true) {
    case $subPath === '':
        // /api/opening
        echo json_encode(["message" => "Opening rules endpoint"]);
        break;

    case str_starts_with($subPath, 'basic'):
        // GET /api/opening/basic?date=JJ-MM-AAAA
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['date'])) {
            $openingBasicController->getOpeningBasicByDate($_GET['date']);
        }
        // GET /api/opening/basic
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $openingBasicController->getAllOpeningsBasic();
        }

        /* MiddleWare Auth*/
        $authUser = AuthMiddleware::verifyAdminAcces();

        // POST /api/opening/basic
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $openingBasicController->createOpeningBasicRule($input);
        }
        // PUT /api/opening/basic
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $input = json_decode(file_get_contents('php://input'), true);
            $openingBasicController->updateOpeningBasicRule($input);
        }
        // DELETE /api/opening/basic?id_time=X
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $openingBasicController->deleteOpeningBasicRule();
        }

        respond(false, "Invalid opening basic endpoint", 404);

    case str_starts_with($subPath, 'exception'):

        // GET /api/opening/exception?date=JJ-MM-AAA
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['date'])) {
            $openingExceptionController->getOpeningExceptionByDate($_GET['date']);
        }

        // GET /api/opening/exception
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $openingExceptionController->getAllFuturOpeningsExceptions();
        }

        /* MiddleWare Auth*/
        $authUser = AuthMiddleware::verifyAdminAcces();

        // POST /api/opening/exception
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $openingExceptionController->createOpeningException($input);
        }

        // DELETE /api/opening/exception
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $openingExceptionController->deleteOpeningException();
        }

        respond(false,"Invalid opening exception endpoint",404);

    default:
        respond(false, "Invalid opening endpoint", 404);
}
