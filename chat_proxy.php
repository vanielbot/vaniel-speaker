<?php

header('Content-Type: application/json');

$DIFY_API_KEY = 'app-uqc7LBzwu60fx0OKQWygA8wa';

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';
$conversation_id = $input['conversation_id'] ?? '';

if (!$message) {
    echo json_encode(['error' => 'No message']);
    exit;
}

// Thử Chat Messages endpoint trước
$url = 'https://api.dify.ai/v1/chat-messages';

$body = [
    'inputs' => (object)[],
    'query' => $message,
    'response_mode' => 'blocking',
    'user' => 'visitor-' . substr(md5($_SERVER['REMOTE_ADDR'] ?? 'guest'), 0, 8)
];

if ($conversation_id) {
    $body['conversation_id'] = $conversation_id;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $DIFY_API_KEY,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

// Nếu chat-messages lỗi, thử workflow endpoint
if ($http_code !== 200 || !isset($data['answer'])) {
    $url2 = 'https://api.dify.ai/v1/workflows/run';
    $body2 = [
        'inputs' => ['query' => $message],
        'response_mode' => 'blocking',
        'user' => 'visitor'
    ];
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $url2);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($body2));
    curl_setopt($ch2, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $DIFY_API_KEY,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
    $response2 = curl_exec($ch2);
    curl_close($ch2);
    $data2 = json_decode($response2, true);

    // Workflow trả về data.outputs.text hoặc data.outputs.answer
    $answer = $data2['data']['outputs']['text']
           ?? $data2['data']['outputs']['answer']
           ?? $data2['data']['outputs']['output']
           ?? null;

    if ($answer) {
        echo json_encode(['answer' => $answer, 'conversation_id' => '']);
        exit;
    }

    // Debug: trả về raw response nếu vẫn lỗi
    echo json_encode(['error' => 'Dify error', 'raw' => $response2]);
    exit;
}

echo $response;
