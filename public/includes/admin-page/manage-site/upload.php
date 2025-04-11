<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json");

// Vérifier si un token est présent
$headers = apache_request_headers();
if (!isset($headers["Authorization"])) {
    http_response_code(401);
    echo json_encode(["error" => "Token manquant"]);
    exit;
}

$token = str_replace("Bearer ", "", $headers["Authorization"]);

// Vérification via API
$apiUrl = "http://host.docker.internal:8000/auth?action=adminAccess";
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(403);
    echo json_encode(["error" => "Accès refusé"]);
    exit;
}

// Vérifier que $_FILES["file"] existe bien
if (!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE) {
    http_response_code(400);
    echo json_encode(["error" => "Aucun fichier envoyé"]);
    exit;
}

// Vérifier l'existence du dossier d'upload
$uploadDir = dirname(__DIR__, 3) . "/resources/uploads/";
if (!file_exists($uploadDir) && !mkdir($uploadDir, 0777, true)) {
    http_response_code(500);
    echo json_encode(["error" => "Impossible de créer le dossier d'upload"]);
    exit;
}

if ($_FILES["file"]["error"] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES["file"]["tmp_name"];
    $fileOriginalName = basename($_FILES["file"]["name"]);
    $fileExtension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);

    // Sécurité : Vérifier que l'extension est bien une image autorisée
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        http_response_code(400);
        echo json_encode(["error" => "Format de fichier non autorisé"]);
        exit;
    }

    // Générer un hash unique basé sur le temps + un random
    $uniqueName = hash("sha256", uniqid() . microtime()) . ".$fileExtension";

    $targetFilePath = $uploadDir . $uniqueName;

    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
        echo json_encode(["url" => "/resources/uploads/" . $uniqueName]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Erreur lors de l'upload"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Fichier invalide", "message" => $_FILES["file"]["error"]]);
}
