# 🚁 Sürü İHA C2 Komuta Kontrol ve Telemetri Sistemi

Bu proje, savunma sanayiinde kullanılan **Komuta Kontrol (C2)** ve gerçek zamanlı veri akışı / anomali izleme sistemlerinin mimari mantığını simüle etmek amacıyla geliştirilmiştir. Sistem; çoklu İHA (Sürü) simülasyonu gerçekleştiren, anlık telemetri verilerini işleyen, harita üzerinde rota izleyen ve kritik eşik aşumlarında otomatik kara kutu (blackbox) loglaması yapan web tabanlı bir komuta kontrol merkezidir.

## 🚀 Öne Çıkan Özellikler
- **Harita Tabanlı Çoklu İHA Takibi:** Leaflet.js entegrasyonu ile Konya merkezli simüle edilen sürü İHA'ların (`101`, `102`, `103`) harita üzerindeki anlık konum takibi.
- **Polyline Rota Geçmişi:** İHA'ların harita üzerinde katettikleri güzergâhların renkli çizgilerle (iz bırakarak) görselleştirilmesi.
- **Akıllı Anomali Tespiti ve Alarm Mekanizmaları:** Motor sıcaklığı (>110°C) ve batarya (<%20) kritik seviyelerinin anlık olarak denetlenmesi ve durum rozetleri ile görselleştirilmesi.
- **Kara Kutu (Blackbox) Raporlama ve Filtreleme Paneli (`logs.php`):** Anomali ve hata kayıtlarının veritabanında saklanması, İHA bazlı filtrelenebilmesi ve gerektiğinde güvenli şekilde sıfırlanabilmesi.

## 🛠️ Kullanılan Teknolojiler
- **Backend:** PHP (PDO ile güvenli MySQL veritabanı bağlantısı)
- **Database:** MySQL (`telemetry_logs` tablosu)
- **Frontend:** HTML5, CSS3 (Modern Dark Theme Dashboard tasarımı)
- **JavaScript & Kütüphaneler:** Leaflet.js (Harita ve Rota Görselleştirme), Fetch API (Asenkron Veri Akışı)
- **Server:** Apache (WAMP Environment)

## 📂 Proje Mimarisi
- `index.php`: Harita arayüzü, canlı İHA kartları ve periyodik veri çekme döngüsü.
- `api.php`: Sürü telemetri verilerini simüle eden ve anomali durumunda veritabanına log düşen uç nokta.
- `logs.php`: Kara kutu loglarını listeleyen, filtreleme ve veritabanı temizleme işlemlerini yöneten yönetim paneli.

## 💻 Kurulum ve Çalıştırma
1. Projeyi WAMP sunucunuzun kök dizinine (örneğin `C:\wamp64\www\iha-telemetry`) kopyalayın.
2. WAMP servislerinin (Apache ve MySQL) aktif olduğundan emin olun.
3. PhpMyAdmin üzerinden `iha_db` adında bir veritabanı oluşturun ve `telemetry_logs` tablosunu yapılandırın.
4. Tarayıcınızda şu adrese gidin: `http://localhost/iha-telemetry/`