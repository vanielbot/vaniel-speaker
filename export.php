<?php

require 'config.php';

// ← Sửa thành domain thật khi lên hosting
$BASE_URL = 'http://localhost/VanielSpeaker';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $SUPABASE_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_KEY",
    "Authorization: Bearer $SUPABASE_KEY"
]);
$response = curl_exec($ch);
curl_close($ch);

$products = json_decode($response, true);

$txt = '';
foreach ($products as $sp) {
    $txt .= "Tên: {$sp['ten_sp']}\n";
    $txt .= "Hãng: {$sp['hang']}\n";
    $txt .= "Giá: " . number_format($sp['gia']) . "đ\n";
    $txt .= "Mô tả: {$sp['mo_ta']}\n";
    $txt .= "Tags: {$sp['tag_ai']}\n";
    $txt .= "Link xem chi tiết: $BASE_URL/product.php?id={$sp['ma_sp']}\n";
    $txt .= "---\n";
}

file_put_contents('products.txt', $txt);
echo "Xuất xong " . count($products) . " sản phẩm!";
?>
