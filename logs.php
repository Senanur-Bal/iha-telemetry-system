<?php
$host = 'localhost';
$db = 'iha_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Logları sıfırlama isteği geldiyse
if (isset($_POST['reset_logs'])) {
    $pdo->exec("DELETE FROM telemetry_logs");
    header("Location: logs.php");
    exit;
}

// Filtreleme parametresini al
$filter_iha = isset($_GET['iha_id']) ? trim($_GET['iha_id']) : '';

if ($filter_iha !== '') {
    $stmt = $pdo->prepare("SELECT * FROM telemetry_logs WHERE iha_id = ? ORDER BY id DESC");
    $stmt->execute([$filter_iha]);
} else {
    $stmt = $pdo->query("SELECT * FROM telemetry_logs ORDER BY id DESC");
}
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kara Kutusu - Anomali ve Hata Raporları</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #121212; color: #e0e0e0; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #1e1e1e; padding: 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.7); }
        h1 { color: #ff5252; text-align: center; margin-bottom: 20px; letter-spacing: 1px; }
        
        .nav-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .back-btn { background: #333; color: #00e676; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; border: 1px solid #00e676; transition: 0.3s; }
        .back-btn:hover { background: #00e676; color: #121212; }

        .filter-form { display: flex; gap: 10px; align-items: center; }
        .filter-select { background: #2d2d2d; color: #e0e0e0; border: 1px solid #555; padding: 9px 12px; border-radius: 6px; font-size: 14px; outline: none; }
        .filter-btn { background: #00b0ff; color: #ffffff; padding: 9px 15px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .filter-btn:hover { background: #0091ea; }
        .reset-filter { background: #333; color: #b0bec5; padding: 9px 15px; text-decoration: none; border-radius: 6px; font-weight: bold; border: 1px solid #555; font-size: 14px; }
        .reset-filter:hover { background: #444; color: #fff; }

        .reset-btn { background: #b71c1c; color: #ffcdd2; padding: 10px 20px; border: 1px solid #ff5252; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .reset-btn:hover { background: #d32f2f; color: #ffffff; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #2d2d2d; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #444; font-size: 14px; }
        th { background: #333; color: #ff5252; text-transform: uppercase; font-size: 13px; }
        tr:hover { background: #383838; }
        
        .badge { padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .danger { background: #b71c1c; color: #ffcdd2; }
        .warning { background: #e65100; color: #ffe0b2; }
        .empty-msg { text-align: center; padding: 30px; color: #888; font-size: 16px; }
    </style>
</head>
<body>

<div class="container">
    <h1>KARA KUTU - ANOMALİ VE KRİTİK LOGLAR</h1>
    
    <div class="nav-container">
        <a href="index.php" class="back-btn">⬅️ Komuta Kontrol Paneline Dön</a>
        
        <!-- İHA Filtreleme Formu -->
        <form method="GET" class="filter-form">
            <select name="iha_id" class="filter-select">
                <option value="">Tüm İHA'lar (Tümü)</option>
                <option value="101" <?= $filter_iha == '101' ? 'selected' : '' ?>>İHA-101 (Bayraktar TB2 A)</option>
                <option value="102" <?= $filter_iha == '102' ? 'selected' : '' ?>>İHA-102 (Akıncı Keşif)</option>
                <option value="103" <?= $filter_iha == '103' ? 'selected' : '' ?>>İHA-103 (Kamikaze İHA X)</option>
            </select>
            <button type="submit" class="filter-btn">Filtrele</button>
            <?php if ($filter_iha !== ''): ?>
                <a href="logs.php" class="reset-filter">Sıfırla</a>
            <?php endif; ?>
        </form>

        <form method="POST" onsubmit="return confirm('Tüm kara kutu loglarını kalıcı olarak silmek istediğinize emin misiniz?');" style="margin: 0;">
            <button type="submit" name="reset_logs" class="reset-btn">🗑️ Kara Kutuyu Sıfırla</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>İHA ID</th>
                <th>İrtifa</th>
                <th>Hız</th>
                <th>Batarya</th>
                <th>Sıcaklık</th>
                <th>Durum Mesajı</th>
                <th>Kayıt Zamanı</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($logs) > 0): ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($log['id']) ?></td>
                        <td><strong>İHA-<?= htmlspecialchars($log['iha_id']) ?></strong></td>
                        <td><?= htmlspecialchars($log['altitude']) ?> m</td>
                        <td><?= htmlspecialchars($log['speed']) ?> km/s</td>
                        <td>%<?= htmlspecialchars($log['battery']) ?></td>
                        <td><?= htmlspecialchars($log['temp']) ?> °C</td>
                        <td><span class="badge <?= strpos($log['status_message'], 'KRİTİK') !== false ? 'danger' : 'warning' ?>"><?= htmlspecialchars($log['status_message']) ?></span></td>
                        <td><?= htmlspecialchars($log['created_at'] ?? 'Bilinmiyor') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="empty-msg">Bu filtreye uygun kayıtlı herhangi bir anomali logu bulunmuyor.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>