<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İHA Sürü Komuta Kontrol (C2) ve Harita Takibi</title>
    <!-- Leaflet CSS Kütüphanesi -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #121212; color: #e0e0e0; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #1e1e1e; padding: 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.7); }
        h1 { color: #00e676; text-align: center; margin-bottom: 25px; letter-spacing: 1px; }
        
        /* Harita Alanı Tasarımı */
        #map { width: 100%; height: 400px; border-radius: 8px; margin-bottom: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }

        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: #2d2d2d; padding: 20px; border-radius: 8px; border-left: 5px solid #00e676; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        .card.danger { border-left-color: #ff5252; }
        .card.warning { border-left-color: #ffab40; }
        .metric { display: flex; justify-content: space-between; margin: 10px 0; font-size: 16px; }
        .status-badge { margin-top: 15px; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center; font-size: 14px; }
        .safe { background: #1b5e20; color: #a5d6a7; }
        .warning { background: #e65100; color: #ffe0b2; }
        .danger { background: #b71c1c; color: #ffcdd2; }
        .footer-info { text-align: center; margin-top: 25px; color: #888; font-size: 13px; }
    </style>
</head>
<body>

<div class="container">
    <h1>SAVUNMA SANAYİİ - SÜRÜ İHA C2 KOMUTA KONTROL MERKEZİ</h1>
    
    <!-- Haritanın Görüneceği Alan -->
    <div id="map"></div>

    <div id="ihaContainer" class="grid">
        <!-- Dinamik İHA kartları buraya gelecek -->
    </div>

    <div class="footer-info">
        🗺️ Leaflet.js Canlı Harita Entegrasyonu | 🔒 MySQL Kara Kutu Loglama Aktif
    </div>
</div>

<!-- Leaflet JS Kütüphanesi -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
// Haritayı başlat (Konya merkezli)
var map = L.map('map').setView([37.8746, 32.4932], 13);

// OpenStreetMap katmanını ekle
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// İHA Marker'larını tutmak için sözlük
var markers = {};

function fetchSwarmTelemetry() {
    fetch('api.php')
        .then(response => response.json())
        .then(dataList => {
            let container = document.getElementById('ihaContainer');
            container.innerHTML = '';

            dataList.forEach(data => {
                let cardClass = data.alertLevel;
                let badgeClass = data.alertLevel;

                let cardHTML = `
                    <div class="card ${cardClass}">
                        <h3>${data.name} (ID: ${data.id})</h3>
                        <div class="metric"><span>Enlem/Boylam:</span> <strong>${data.lat.toFixed(4)}, ${data.lng.toFixed(4)}</strong></div>
                        <div class="metric"><span>İrtifa:</span> <strong>${data.altitude} m</strong></div>
                        <div class="metric"><span>Hız:</span> <strong>${data.speed} km/s</strong></div>
                        <div class="metric"><span>Batarya:</span> <strong>%${data.battery}</strong></div>
                        <div class="metric"><span>Motor Sıcaklığı:</span> <strong>${data.temp} °C</strong></div>
                        <div class="status-badge ${badgeClass}">${data.statusMessage}</div>
                    </div>
                `;
                container.innerHTML += cardHTML;

                // Harita Üzerindeki Marker İşlemleri
                if (markers[data.id]) {
                    markers[data.id].setLatLng([data.lat, data.lng]);
                    markers[data.id].getPopup().setContent(`<b>${data.name}</b><br>İrtifa: ${data.altitude}m<br>Hız: ${data.speed}km/s`);
                } else {
                    let marker = L.marker([data.lat, data.lng]).addTo(map)
                        .bindPopup(`<b>${data.name}</b><br>İrtifa: ${data.altitude}m<br>Hız: ${data.speed}km/s`);
                    markers[data.id] = marker;
                }
            });
        });
}

// Saniyede bir güncelle
setInterval(fetchSwarmTelemetry, 1000);
fetchSwarmTelemetry();
</script>

</body>
</html>