<?php
// MySQL Veritabanı Bağlantısı
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'iha_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// Logları veritabanından id'ye göre en yeniden eskiye doğru çek
$sql = "SELECT * FROM telemetry_logs ORDER BY id DESC LIMIT 50";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İHA Kara Kutu - Anomali ve Log Raporları</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #121212; color: #e0e0e0; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #1e1e1e; padding: 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.7); }
        h1 { color: #ff5252; text-align: center; margin-bottom: 20px; letter-spacing: 1px; }
        .nav-links { text-align: center; margin-bottom: 25px; }
        .nav-links a { background: #2d2d2d; color: #00e676; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; border: 1px solid #00e676; transition: 0.3s; }
        .nav-links a:hover { background: #00e676; color: #121212; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #2d2d2d; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #444; font-size: 14px; }
        th { background: #333; color: #ff5252; text-transform: uppercase; font-size: 13px; }
        tr:hover { background: #383838; }
        .badge-danger { background: #b71c1c; color: #ffcdd2; padding: 5px 10px; border-radius: 4px; font-weight: bold; }
        .footer-info { text-align: center; margin-top: 25px; color: #888; font-size: 13px; }
    </style>
</head>
<body>

<div class="container">
    <h1>🛡️ KARA KUTU - ANOMALİ VE KRİTİK LOG RAPORLARI</h1>
    
    <div class="nav-links">
        <a href="index.php">⬅️ Canlı C2 Komuta Kontrol Paneline Dön</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>İHA ID</th>
                <th>İrtifa (m)</th>
                <th>Hız (km/s)</th>
                <th>Batarya</th>
                <th>Sıcaklık</th>
                <th>Durum Mesajı / Anomali</th>
                <th>Zaman Damgası</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Veritabanındaki olası zaman alanı isimlerini güvenli şekilde kontrol ediyoruz
                    $logTime = isset($row['log_time']) ? $row['log_time'] : (isset($row['tarih']) ? $row['tarih'] : 'Kayıt Zamanı');

                    echo "<tr>";
                    echo "<td>#" . $row['id'] . "</td>";
                    echo "<td><strong>İHA-" . $row['iha_id'] . "</strong></td>";
                    echo "<td>" . $row['altitude'] . " m</td>";
                    echo "<td>" . $row['speed'] . " km/s</td>";
                    echo "<td>%" . $row['battery'] . "</td>";
                    echo "<td>" . $row['temp'] . " °C</td>";
                    echo "<td><span class='badge-danger'>" . htmlspecialchars($row['status_message']) . "</span></td>";
                    echo "<td>" . $logTime . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align: center; color: #888;'>Henüz veritabanına kaydedilmiş bir anomali/kara kutu kaydı bulunmuyor. Sistem güvenli!</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

    <div class="footer-info">
        🔒 MySQL Blackbox Telemetry Logging System | Savunma Sanayii C2 Protokolü
    </div>
</div>

</body>
</html>