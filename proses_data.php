<?php
session_start();

// Proteksi halaman: Cek apakah user sudah login
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; // Panggil koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $divisi = isset($_POST['divisi']) ? trim($_POST['divisi']) : '';
    $jam_kerja = isset($_POST['jam_kerja_sepekan']) ? $_POST['jam_kerja_sepekan'] : '';
    $gaji = isset($_POST['gaji_pokok']) ? $_POST['gaji_pokok'] : '';

    // Validasi: Cek apakah ada data kosong
    if (empty($nama) || empty($divisi) || empty($jam_kerja) || empty($gaji)) {
        // Jika ada data kosong, tampilkan pesan error
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Error Validasi</title>
            <style>
                body { 
                    font-family: 'Courier New', monospace; 
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 20px;
                }
                .container { 
                    max-width: 400px; 
                    margin: 0 auto; 
                    background: white; 
                    padding: 20px; 
                    border-radius: 5px; 
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .error { 
                    color: red; 
                    font-size: 18px;
                    text-align: center;
                }
                .btn-kembali { 
                    display: block;
                    text-align: center;
                    background-color: #FF0095; 
                    color: white; 
                    padding: 10px; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2 class="error">❌ Error: Semua kolom wajib diisi!</h2>
                <a href="tambah_data.php" class="btn-kembali">← Kembali ke Form</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    // CARI ID KOSONG TERKECIL UNTUK DIISI
    
    // Buat tabel sementara berisi urutan ID yang seharusnya (1 sampai max+1)
    $buat_urutan = "
        CREATE TEMPORARY TABLE urutan_id AS
        SELECT @row := @row + 1 AS urutan
        FROM karyawan, (SELECT @row := 0) AS r
        UNION
        SELECT (SELECT COUNT(*) FROM karyawan) + 1
        LIMIT (SELECT COUNT(*) + 1 FROM karyawan)
    ";
    mysqli_query($koneksi, $buat_urutan);
    
    // Cari ID terkecil yang tidak ada di tabel karyawan
    $cari_id_kosong = "
        SELECT MIN(urutan) AS id_kosong
        FROM urutan_id
        WHERE urutan NOT IN (SELECT id FROM karyawan)
    ";
    $result = mysqli_query($koneksi, $cari_id_kosong);
    $row = mysqli_fetch_assoc($result);
    $id_baru = $row['id_kosong'];
    
    // Hapus tabel sementara
    mysqli_query($koneksi, "DROP TEMPORARY TABLE urutan_id");
    
    // SIMPAN DATA KE DATABASE
    
    // Matikan sementara foreign key checks (jika ada)
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");
    
    if ($id_baru) {
        // Jika ada ID kosong, insert dengan ID tertentu
        $query = "INSERT INTO karyawan (id, nama, divisi, jam_kerja_sepekan, gaji_pokok) 
                    VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "issid", $id_baru, $nama, $divisi, $jam_kerja, $gaji);
    } else {
        // Jika tidak ada ID kosong, insert biasa (auto increment)
        $query = "INSERT INTO karyawan (nama, divisi, jam_kerja_sepekan, gaji_pokok) 
                    VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssid", $nama, $divisi, $jam_kerja, $gaji);
    }
    
    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        
        // Reset auto increment ke nilai terakhir + 1
        $max_id = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(id) as max FROM karyawan"))['max'];
        $next_id = $max_id + 1;
        mysqli_query($koneksi, "ALTER TABLE karyawan AUTO_INCREMENT = $next_id");
        
        // Hidupkan kembali foreign key checks
        mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
        
        // Jika berhasil, redirect ke index.php dengan status sukses
        header("Location: index.php?status=success");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($koneksi);
        exit;
    }

} else {
    // Jika akses langsung tanpa POST
    header("Location: tambah_data.php");
    exit;
}
?>