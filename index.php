<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İHA Telemetri ve Anomali İzleme Paneli</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1e1e1e; color: #fff; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: #2d2d2d; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        h1 { color: #4CAF50; text-align: center; }
        .card { display: flex; justify-content: space-around; background: #3c3c3c; padding: 15px; margin: 15px 0; border-radius: 5px; font-size: 18px; }
        .alert-box { padding: 15px; margin-top: 20px; border-radius: 5px; font-weight: bold; text-align: center; display: none; }
        .danger { background: #d9534f; color: white; }
        .warning { background: #f0ad4e; color: black; }
        .safe { background: #5cb85c; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h1>SAVUNMA SANAYİİ - İHA C2 TELEMETRİ SİSTEMİ</h1>
    
    <div class="card">
        <div>İHA ID: <span id="ihaId">101</span></div>
        <div>İrtifa: <span id="altitude">0</span> m</div>
        <div>Hız: <span id="speed">0</span> km/s</div>
    </div>
    <div class="card">
        <div>Batarya: %<span id="battery">0</span></div>
        <div>Motor Sıcaklığı: <span id="temp">0</span> °C</div>
    </div>

    <div id="alertBox" class="alert-box">SİSTEM NORMAL</div>
</div>

<script>
function fetchTelemetry() {
    fetch('api.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('altitude').innerText = data.altitude;
            document.getElementById('speed').innerText = data.speed;
            document.getElementById('battery').innerText = data.battery;
            document.getElementById('temp').innerText = data.temp;

            let alertBox = document.getElementById('alertBox');
            alertBox.style.display = 'block';

            if (data.temp > 110) {
                alertBox.className = 'alert-box danger';
                alertBox.innerText = `[KRİTİK UYARI] Motor Sıcaklığı Sınırı Aştı: ${data.temp}°C!`;
            } else if (data.battery < 20) {
                alertBox.className = 'alert-box warning';
                alertBox.innerText = `[DÜŞÜK BATARYA] Seviye Kritik: %${data.battery}`;
            } else {
                alertBox.className = 'alert-box safe';
                alertBox.innerText = `[DURUM: NORMAL] Uçuş Güvenli - Tüm Sistemler Aktif`;
            }
        });
}

setInterval(fetchTelemetry, 1000);
</script>

</body>
</html>