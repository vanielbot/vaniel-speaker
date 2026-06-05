<?php

require 'config.php';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $SUPABASE_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_KEY",
    "Authorization: Bearer $SUPABASE_KEY"
]);

$response = curl_exec($ch);

if(curl_errno($ch)){
    die(curl_error($ch));
}

curl_close($ch);

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";

?>