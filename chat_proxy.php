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

$user_id = 'visitor-' . substr(md5($_SERVER['REMOTE_ADDR'] ?? 'guest'), 0, 8);

// Thử chat-messages trước (Chatbot app)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.dify.ai/v1/chat-messages');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $DIFY_API_KEY,
    'Content-Type: application/json'
]);

$body = ['inputs' => (object)[], 'query' => $message, 'response_mode' => 'blocking', 'user' => $user_id];
if ($conversation_id) $body['conversation_id'] = $conversation_id;
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$data = json_decode($response, true);

if ($code === 200 && isset($data['answer'])) {
    echo json_encode(['answer' => $data['answer'], 'conversation_id' => $data['conversation_id'] ?? '']);
    exit;
}

// Thử workflow
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, 'https://api.dify.ai/v1/workflows/run');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $DIFY_API_KEY,
    'Content-Type: application/json'
]);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode([
    'inputs' => ['query' => $message, 'sys.query' => $message],
    'response_mode' => 'blocking',
    'user' => $user_id
]));
$response2 = curl_exec($ch2);
$code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);
$data2 = json_decode($response2, true);

// Tìm answer trong tất cả các output keys có thể có
$outputs = $data2['data']['outputs'] ?? [];
$answer = null;
foreach ($outputs as $val) {
    if (is_string($val) && strlen($val) > 5) {
        $answer = $val;
        break;
    }
}

if ($answer) {
    echo json_encode(['answer' => $answer, 'conversation_id' => '']);
    exit;
}

// Trả về toàn bộ để debug
echo json_encode([
    'error' => 'Could not get answer',
    'chat_code' => $code,
    'chat_raw' => $data,
    'workflow_code' => $code2,
    'workflow_raw' => $data2
]);
