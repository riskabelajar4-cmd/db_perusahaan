<?php
session_start();

// Proteksi halaman: Cek apakah user sudah login
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; // Panggil koneksi database

// CEK APAKAH DATA DIKIRIM VIA POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form (termasuk ID dari hidden input)
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $divisi = isset($_POST['divisi']) ? trim($_POST['divisi']) : '';
    $jam_kerja = isset($_POST['jam_kerja_sepekan']) ? $_POST['jam_kerja_sepekan'] : '';
    $gaji = isset($_POST['gaji_pokok']) ? $_POST['gaji_pokok'] : '';

    // Validasi: Cek apakah ada data kosong
    if (empty($id) || empty($nama) || empty($divisi) || empty($jam_kerja) || empty($gaji)) {
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
                <a href="edit.php?id=<?php echo $id; ?>" class="btn-kembali">← Kembali ke Form Edit</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    // UPDATE DATA DI DATABASE MENGGUNAKAN PREPARED STATEMENT
    
    $query = "UPDATE karyawan SET nama = ?, divisi = ?, jam_kerja_sepekan = ?, gaji_pokok = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    
    mysqli_stmt_bind_param($stmt, "ssidi", $nama, $divisi, $jam_kerja, $gaji, $id);
    
    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, redirect ke index.php dengan status updated
        header("Location: index.php?status=updated");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error mengupdate data: " . mysqli_error($koneksi);
        exit;
    }
    
    // Tutup statement
    mysqli_stmt_close($stmt);

} else {
    // Jika akses langsung tanpa POST
    header("Location: index.php");
    exit;
}
?>