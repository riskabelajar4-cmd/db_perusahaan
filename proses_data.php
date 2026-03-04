<?php
// proses_data.php
include 'koneksi.php'; // Panggil koneksi database

// =====================================================
// CEK APAKAH DATA DIKIRIM VIA POST
// =====================================================
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
    // SIMPAN DATA KE DATABASE MENGGUNAKAN PREPARED STATEMENT

    // Query INSERT dengan prepared statement
    $query = "INSERT INTO karyawan (nama, divisi, jam_kerja_sepekan, gaji_pokok) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    
    // Hubungkan variabel ke prepared statement
    mysqli_stmt_bind_param($stmt, "ssid", $nama, $divisi, $jam_kerja, $gaji);
    
    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, redirect ke index.php dengan status sukses
        header("Location: index.php?status=success");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($koneksi);
        exit;
    }
    
    // Tutup statement
    mysqli_stmt_close($stmt);

} else {
    // Jika akses langsung tanpa POST
    header("Location: tambah_data.php");
    exit;
}
?>