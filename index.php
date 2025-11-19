<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['part'])) {
    
    file_put_contents("part2.txt", $_POST['part']);
    echo "OK";
    exit;
}

echo "<pre>";

$myUrl = "http://localhost:8000/index.php";

$payload = [
    "msg" => "Salom interview.php",
    "url" => $myUrl
];

$ch = curl_init("https://test.icorp.uz/interview.php");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$part1 = curl_exec($ch);
curl_close($ch);

echo "Часть-1 пришла с сервера: $part1\n";


echo "Ожидаем поступления второй части...\n";

$part2 = null;
for ($i = 0; $i < 10; $i++) {
    if (file_exists("part2.txt")) {
        $part2 = file_get_contents("part2.txt");
        unlink("part2.txt");
        break;
    }
    sleep(1);
}

if (!$part2) {
    die("Вторая часть не пришла!!\n");
}

echo "Вторая часть получена: $part2\n";


$fullCode = trim($part1 . $part2);
echo "Объединённый код: $fullCode\n";


$ch = curl_init("https://test.icorp.uz/interview.php?code=$fullCode");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);


echo "\n--- Итоговое сообщение ---\n";
echo $response;
echo "\n\nГотово!\n";
?>
