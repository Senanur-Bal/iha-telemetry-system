<?php
header('Content-Type: application/json');

// MySQL Veritabanı Bağlantısı (Kara Kutu Loglama)
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'iha_db';

$conn = new mysqli($host, $username, $password, $dbname);

// Sürü İHA Listesi ve Başlangıç Koordinatları (Konya civarı merkezli)
$ihaList = [
    ["id" => 101, "name" => "Bayraktar TB2-A", "lat" => 37.8746, "lng" => 32.4932],
    ["id" => 102, "name" => "Akıncı Keşif", "lat" => 37.8850, "lng" => 32.5050],
    ["id" => 103, "name" => "Kamikaze İHA-X", "lat" => 37.8650, "lng" => 32.4800]
];

$responseArray = [];

foreach ($ihaList as $iha) {
    // Her saniye koordinatları çok küçük miktarda değiştirerek hareket simüle ediyoruz
    $lat = $iha['lat'] + (rand(-10, 10) * 0.0005);
    $lng = $iha['lng'] + (rand(-10, 10) * 0.0005);
    
    $altitude = rand(30, 600);
    $speed = rand(80, 280);
    $battery = rand(10, 100);
    $temp = rand(80, 125);

    $statusMessage = "Sistem Normal - Uçuş Güvenli";
    $alertLevel = "safe";

    if ($temp > 110) {
        $statusMessage = "[KRİTİK] Motor Sıcaklığı Sınırı Aştı: {$temp}°C!";
        $alertLevel = "danger";
    } else if ($battery < 20) {
        $statusMessage = "[DÜŞÜK BATARYA] Seviye Kritik: %{$battery}";
        $alertLevel = "warning";
    }

    // Anomali durumunda veritabanına logla
    if ($conn->connect_error == false && $alertLevel != "safe") {
        $stmt = $conn->prepare("INSERT INTO telemetry_logs (iha_id, altitude, speed, battery, temp, status_message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idddds", $iha['id'], $altitude, $speed, $battery, $temp, $statusMessage);
        $stmt->execute();
        $stmt->close();
    }

    $responseArray[] = [
        "id" => $iha['id'],
        "name" => $iha['name'],
        "lat" => $lat,
        "lng" => $lng,
        "altitude" => $altitude,
        "speed" => $speed,
        "battery" => $battery,
        "temp" => $temp,
        "statusMessage" => $statusMessage,
        "alertLevel" => $alertLevel
    ];
}

if ($conn->connect_error == false) {
    $conn->close();
}

echo json_encode($responseArray);
?>