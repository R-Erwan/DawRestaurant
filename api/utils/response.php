<?php

/**
 * @param bool $success
 * @param string $message
 * @param int $code
 * @param array|null $data
 * @return never Exists the script
 */
function respond(bool $success, string $message, int $code = 200, ?array $data = null ): never {
    http_response_code($code);
    header('Content-Type: application/json');

    $response = [
        'success' => $success,
        'message' => $message
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode($response);
    exit;
}
