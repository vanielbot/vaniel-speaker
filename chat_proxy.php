<?php

header('Content-Type: application/json');

$DIFY_API_KEY = 'app-uqc7LBzwu60fx0OKQWygA8wa';
$DIFY_API_URL = 'https://api.dify.ai/v1/chat-messages';

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';
$conversation_id = $input['conversation_id'] ?? '';

if (!$message) {
    echo json_encode(['error' => 'No message']);
    exit;
}

$body = [
    'inputs' => (object)[],
    'query' => $message,
    'response_mode' => 'blocking',
    'user' => 'visitor'
];

if ($conversation_id) {
    $body['conversation_id'] = $conversation_id;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $DIFY_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $DIFY_API_KEY,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;