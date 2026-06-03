# AmanParkir - Sistem Manajemen Tempat Parkir FMIPA

Sistem manajemen parkir yang dirancang untuk memantau dan mencatat masuk/keluar kendaraan (motor dan mobil) dengan batasan kapasitas per area parkir.

## Fitur Utama

- ✅ **Dashboard Real-time**: Monitoring status semua area parkir
- ✅ **Pencatatan Masuk Kendaraan**: Catat kendaraan masuk dengan data lengkap
- ✅ **Pencatatan Keluar Kendaraan**: Catat kendaraan keluar dan durasi parkir
- ✅ **Manajemen Area Parkir**: Kelola area dengan batasan kapasitas motor/mobil
- ✅ **Laporan Statistik**: Laporan penggunaan parkir per area dan rentang waktu
- ✅ **Respons Design**: Kompatibel dengan desktop dan mobile

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (Laragon)

## Struktur Project

```
UAP_PDT/
├── config/
│   ├── database.php      # Konfigurasi koneksi database
│   └── parkir_pdt.sql    # Skema database
├── app/
│   ├── controllers/      # Controller aplikasi
│   ├── models/          # Model database
│   └── views/           # Template HTML
├── public/              # Public folder (web root)
│   ├── css/            # Stylesheet
│   ├── js/             # JavaScript files
│   ├── index.php       # Dashboard
│   ├── entry.php       # Halaman masuk kendaraan
│   ├── exit.php        # Halaman keluar kendaraan
│   ├── reports.php     # Halaman laporan
│   └── setup.php       # Setup & verifikasi
└── README.md
```

## Instalasi

### 1. Download dan Setup

Proyek sudah disimpan di: `C:\laragon\www\UAP_PDT\`

### 2. Buat Database

Buka **Laragon** dan klik **Database > MySQL Console** atau gunakan **phpMyAdmin**:

**Option A - Menggunakan phpMyAdmin:**
1. Buka http://localhost/phpmyadmin
2. Klik "Import"
3. Pilih file: `config/parkir_pdt.sql`
4. Klik "Go"

**Option B - Menggunakan MySQL Command Line:**
```bash
mysql -u root -p < C:\laragon\www\UAP_PDT\config\parkir_pdt.sql
```
(Tekan Enter jika password kosong)

### 3. Verifikasi Konfigurasi Database

Edit file `config/database.php` dan sesuaikan dengan setting Laragon Anda:
```php
define('DB_HOST', 'localhost');     // Host database
define('DB_USER', 'root');          // Username
define('DB_PASS', '');              // Password (kosong untuk default)
define('DB_NAME', 'amanparkir');    // Nama database
define('DB_PORT', 3306);            // Port
```

### 4. Akses Aplikasi

Buka browser dan akses:
- **Setup/Verifikasi**: http://localhost/UAP_PDT/public/setup.php
- **Dashboard**: http://localhost/UAP_PDT/public/index.php

Jika database berhasil terhubung, Anda akan diarahkan ke dashboard.

## Penggunaan

### Dashboard
- Menampilkan statistik real-time penggunaan parkir
- Menunjukkan kapasitas motor dan mobil per area
- Daftar aktivitas terbaru

### Masuk Kendaraan (Entry)
1. Masukkan nomor plat kendaraan
2. Pilih tipe kendaraan (Motor/Mobil)
3. Pilih area parkir
4. (Opsional) Isi data pemilik
5. Klik "Catat Masuk"

### Keluar Kendaraan (Exit)
1. Masukkan nomor plat kendaraan
2. Pilih area parkir
3. Klik "Catat Keluar"
4. Sistem akan menampilkan durasi parkir

### Laporan
- Filter berdasarkan rentang tanggal
- Lihat statistik penggunaan per area
- Ekspor data untuk keperluan analisis

## Database Schema

### Tabel Utama

**parking_areas** - Zona parkir
- id, area_code, area_name, location
- total_capacity, motorcycle_capacity, car_capacity
- current_motorcycle, current_car, status

**vehicles** - Data kendaraan
- id, plate_number, vehicle_type_id
- owner_name, owner_phone, owner_email

**parking_transactions** - Log masuk/keluar
- id, vehicle_id, parking_area_id
- entry_time, exit_time, duration_minutes, is_active

**vehicle_types** - Tipe kendaraan (Motor, Mobil)

**activity_logs** - Log aktivitas sistem

## Fitur Lanjutan

### Batasan Kapasitas
- Sistem akan mencegah kendaraan masuk jika kapasitas sudah penuh
- Batasan terpisah untuk motor dan mobil per area

### Validasi Otomatis
- Nomor plat tidak boleh duplikat untuk masuk bersamaan
- Area dan tipe kendaraan wajib dipilih
- Format input data ter-validasi

### Laporan Real-time
- Total kendaraan per area
- Durasi rata-rata parkir
- Statistik motor vs mobil
- Fleksibel filter by date range

## Tips Penggunaan

1. **Format Nomor Plat**: Gunakan format standar (contoh: B 1234 CD)
2. **Area Parkir**: Tentukan area sesuai lokasi fisik kendaraan
3. **Data Pemilik**: Opsional namun berguna untuk tracking
4. **Laporan Rutin**: Export laporan setiap hari/minggu

## Troubleshooting

### Database Connection Error
- Cek apakah MySQL sedang berjalan di Laragon
- Verifikasi kredensial di `config/database.php`
- Pastikan database `amanparkir` sudah terbuat

### Data tidak muncul
- Refresh halaman (F5 atau Ctrl+R)
- Cek console browser untuk error (F12 > Console)
- Pastikan database sudah diisi dengan data sample

### Kapasitas tidak terhitung
- Pastikan vehicle_type_id benar di tabel vehicles
- Update nilai current_motorcycle/current_car di tabel parking_areas

## Support & Maintenance

Untuk informasi lebih lanjut atau reporting bugs:
- Cek file config/parkir_pdt.sql untuk struktur lengkap
- Review kode controller untuk logika bisnis
- Hubungi admin FMIPA untuk support teknis

---

**Version**: 1.0.0
**Last Updated**: 2024
**Author**: AmanParkir Development Team
