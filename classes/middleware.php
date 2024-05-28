<?php
function checkAuth() {
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $jwt = new JWT();
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    list($token) = sscanf($authHeader, 'Bearer %s');

    if (!$token) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $payload = $jwt->decode($token);

    if (!$payload) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    return $payload;
}
