<?php

require 'config.php';

$question = $_POST['question'] ?? '';

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

$result = [];

foreach($products as $sp){

    if(
        stripos($sp['tag_ai'], $question) !== false ||
        stripos($sp['mo_ta'], $question) !== false
    ){
        $result[] = $sp;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>AI Tư vấn</title>
</head>
<body>

<h1>AI Vaniel Speaker</h1>

<form method="post">
    <input
        type="text"
        name="question"
        placeholder="Ví dụ: bass mạnh"
        style="width:400px;padding:10px;"
    >

    <button type="submit">
        Hỏi AI
    </button>
</form>

<hr>

<?php

if($question){

    echo "<h3>Kết quả cho: $question</h3>";

    foreach($result as $sp){

        echo "<p>";

        echo "<b>".$sp['ten_sp']."</b><br>";

        echo $sp['tag_ai'];

        echo "</p><hr>";
    }
}

?>

</body>
</html>