AmanParkir (Proyek UAP)
----------------------------------------------------------------------------------------
Proyek ini merupakan sistem manajemen parkir berbasis web yang dibangun menggunakan PHP dan MySQL. Tujuannya untuk membantu pengelolaan kendaraan yang masuk dan keluar area parkir FMIPA secara lebih terstruktur dan efisien. Sistem memanfaatkan konsep MVC (Model-View-Controller), transaksi database, serta monitoring kapasitas parkir secara real-time sehingga petugas dapat mengelola area parkir dengan lebih mudah.

Detail Konsep
------------------------------------------------------
AmanParkir dirancang untuk mempermudah proses pencatatan kendaraan masuk dan keluar serta pemantauan kapasitas area parkir. Sistem terdiri dari beberapa komponen utama yang saling terhubung melalui arsitektur MVC.

Registrasi Kendaraan Masuk

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
