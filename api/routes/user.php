    <?php

    global $pdo;

    use controllers\UserController;
    use middleware\AuthMiddleware;

    require_once 'controllers/UserController.php';
    require_once 'middleware/AuthMiddleware.php';

    $userController = new UserController($pdo);

    /* MiddleWare Auth*/
    $authUser = AuthMiddleware::verifyToken();
    if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid user ID"]);
        exit;
    }

    $requestedUserId = intval($_GET['id']);

    if ($authUser['user_id'] !== $requestedUserId) {
        http_response_code(403);
        echo json_encode(["message" => "Unauthorized"]);
        exit;
    }

    /* Routes */
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userController->getUserInfoById($requestedUserId);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        $userController->updateUserById($requestedUserId, $input);
    }

