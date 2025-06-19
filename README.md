# 📋 Sistem Absensi Berbasis RFID

Proyek ini merupakan sistem absensi berbasis **RFID** menggunakan **PHP Native** untuk frontend dan backend, serta **API Python** untuk komunikasi dengan perangkat **Arduino**. Sistem ini ditujukan untuk pencatatan kehadiran secara otomatis dan real-time.

## 🧩 Arsitektur Sistem

```text
[ RFID + Arduino ] ⇄ [ Python API Server ] ⇄ [ PHP Web (MySQL) ]
````

* **Arduino + RFID**: Membaca UID kartu RFID
* **Python API**: Menjembatani komunikasi serial dengan Arduino dan meneruskan UID ke server PHP
* **PHP Native**: Menyimpan data absensi ke database dan menyediakan antarmuka pengguna

---

## 🛠️ Teknologi yang Digunakan

* **Frontend & Backend**: PHP Native
* **Database**: MySQL
* **Komunikasi Serial**: Python 3 (flask, pyserial)
* **Hardware**: Arduino UNO, RFID RC522, USB

---

## 🚀 Cara Menjalankan

### 1. Siapkan Arduino + RFID

* Upload sketch Arduino untuk membaca UID RFID
* Pastikan data dikirim ke serial dalam format sederhana (misal: `UID:123456AB`)

### 2. Jalankan API Python

Masuk ke folder `api-python/`, lalu install dependency dan jalankan server:

```bash
pip install flask pyserial
python app.py
```


## 📊 Fitur

* Pencatatan otomatis berdasarkan UID RFID
* Komunikasi real-time dengan Arduino melalui API Python
* Riwayat kehadiran dalam antarmuka web sederhana

---

## 💡 Pengembangan Selanjutnya

* Tambahkan autentikasi dan user management
* Integrasi dengan notifikasi Telegram/Email
* Sistem validasi UID terhadap daftar karyawan/siswa

---

## 📜 Lisensi

Proyek ini bersifat open-source di bawah lisensi [MIT](LICENSE).

```
