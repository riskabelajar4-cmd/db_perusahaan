<?php
// Konfigurasi database
$host = 'localhost';  // atau 127.0.0.1
$username = 'root';    // username MySQL default XAMPP
$password = '';        // password MySQL default XAMPP (kosong)
$database = 'db_perusahaan';

// Buat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset ke UTF-8
mysqli_set_charset($koneksi, "utf8");

// Jika ingin mengecek koneksi berhasil (hapus komentar untuk debugging)
// echo "Koneksi berhasil";
?>