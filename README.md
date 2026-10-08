# İHA / SİHA Gerçek Zamanlı Telemetri ve Anomali Tespit Sistemi (C2 Simülasyonu)

Bu proje, savunma sanayiinde kullanılan **Komuta Kontrol (C2)** ve gerçek zamanlı veri akışı/anomali izleme sistemlerinin mimari mantığını simüle etmek amacıyla geliştirilmiştir. Sistem, İHA'dan gelen anlık telemetri verilerini işler ve eşik değer aşumlarında otomatik anomali uyarıları üretir.

## 🚀 Özellikler
* **Gerçek Zamanlı Veri Akışı:** Asenkron mimariyle periyodik telemetri verisi simülasyonu.
* **Anomali Tespiti ve Alarm Mekanizmaları:** Motor sıcaklığı ve batarya kritik seviyelerini anlık denetleme.
* **Dinamik UI:** Anlık güncellenen modern web tabanlı komuta kontrol arayüzü.

## 🛠️ Kullanılan Teknolojiler
* **Backend:** PHP (JSON tabanlı REST API simülasyonu)
* **Frontend:** HTML5, CSS3, JavaScript (Fetch API & DOM Manipülasyonu)
* **Server:** Apache (WAMP Environment)

## 💻 Kurulum ve Çalıştırma
1. Projeyi WAMP sunucunuzun kök dizinine (`C:\wamp64\www\iha-telemetri`) kopyalayın.
2. WAMP servislerinin (Apache) aktif olduğundan emin olun.
3. Tarayıcınızda şu adrese gidin: `http://localhost/iha-telemetri/`