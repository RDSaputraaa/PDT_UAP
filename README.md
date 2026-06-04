AmanParkir (Proyek UAP)
----------------------------------------------------------------------------------------
Proyek ini merupakan sistem manajemen parkir berbasis web yang dibangun menggunakan PHP dan MySQL. Tujuannya untuk membantu pengelolaan kendaraan yang masuk dan keluar area parkir FMIPA secara lebih terstruktur dan efisien. Sistem memanfaatkan konsep MVC (Model-View-Controller), transaksi database, serta monitoring kapasitas parkir secara real-time sehingga petugas dapat mengelola area parkir dengan lebih mudah.

Detail Konsep
------------------------------------------------------
AmanParkir dirancang untuk mempermudah proses pencatatan kendaraan masuk dan keluar serta pemantauan kapasitas area parkir. Sistem terdiri dari beberapa komponen utama yang saling terhubung melalui arsitektur MVC.

## Registrasi Kendaraan Masuk

Fitur ini digunakan untuk mencatat kendaraan yang memasuki area parkir.

Proses yang dilakukan:

Input nomor plat kendaraan.
Input data pemilik kendaraan.
Pemilihan area parkir.
Penyimpanan waktu masuk secara otomatis.
Validasi kapasitas area parkir.

Contoh implementasi:
```php
$vehicle->plate_number = $_POST['plate_number'];
$vehicle->owner_name = $_POST['owner_name'];
$vehicle->area_id = $_POST['area_id'];

$transaction->entry_time = date('Y-m-d H:i:s');
$transaction->save();
```

## Registrasi Kendaraan Keluar

Fitur ini digunakan untuk mencatat kendaraan yang meninggalkan area parkir.

Proses yang dilakukan:

Mencari kendaraan berdasarkan nomor plat.
Menampilkan data parkir aktif.
Menyimpan waktu keluar secara otomatis.
Menghitung durasi parkir.

Contoh implementasi:
```php
$transaction = ParkingTransaction::findActive($plate_number);

$transaction->exit_time = date('Y-m-d H:i:s');
$transaction->update();
```

##Dashboard Monitoring

Dashboard menampilkan informasi parkir secara real-time, meliputi:

Total kendaraan aktif.
Kapasitas area parkir.
Slot yang tersedia.
Aktivitas parkir terbaru.

Contoh query:
```php
SELECT COUNT(*) AS total_active
FROM parking_transactions
WHERE exit_time IS NULL;
```

## Manajemen Area Parkir

Sistem menyediakan pengelolaan beberapa area parkir yang memiliki kapasitas berbeda.

Contoh data:
| Area | Kapasitas |
|------|-----------|
| A | 150 |
| B | 200 |
| PASCA | 20 |


Ketika kapasitas penuh, sistem akan memberikan peringatan dan menolak kendaraan baru untuk area tersebut.

## Struktur MVC
Controller

Mengatur alur logika aplikasi.
```php
app/controllers/
├── HomeController.php
└── ParkingController.php
```

Model

Mengelola data dan interaksi database.
```php
app/models/
├── ParkingArea.php
├── ParkingTransaction.php
└── Vehicle.php
```

View

Mengelola tampilan antarmuka pengguna.
```php
app/views/
├── dashboard.php
├── entry.php
├── exit.php
├── header.php
└── footer.php
```

## Database

Database yang digunakan:
```php
amanparkir
```

Tabel utama:
```php
parking_areas
vehicle_types
vehicles
parking_transactions
activity_logs
```

Relasi database:

- Satu area parkir memiliki banyak kendaraan.
- Satu kendaraan memiliki banyak transaksi parkir.
- Setiap transaksi menyimpan waktu masuk dan keluar kendaraan.
