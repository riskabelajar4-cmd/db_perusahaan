<?php
// koneksi.php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_perusahaan";

$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8");
?>