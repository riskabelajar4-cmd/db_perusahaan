<?php
session_start();

// Proteksi halaman: Cek apakah user sudah login
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; // Panggil koneksi database

// Tangkap ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Validasi: Cek apakah ID ada
if (empty($id)) {
    // Jika tidak ada ID, redirect ke index.php
    header("Location: index.php");
    exit;
}

// AMBIL DATA KARYAWAN BERDASARKAN ID
$query = "SELECT * FROM karyawan WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// Cek apakah data ditemukan
if (!$data) {
    // Jika data tidak ditemukan, redirect ke index.php
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Karyawan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-top: 0;
            border-bottom: 2px solid #FF0095;
            padding-bottom: 10px;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px;
            font-weight: bold; 
            color: #333;
        }
        input { 
            padding: 10px; 
            width: 100%; 
            border: 1px solid #ddd; 
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #FF0095;
            box-shadow: 0 0 5px rgba(255,0,149,0.3);
        }
        button { 
            background-color: #FF0095; 
            color: white; 
            padding: 12px 24px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover { 
            background-color: #d4007a;
        }
        .btn-kembali {
            display: inline-block;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-kembali:hover {
            color: #FF0095;
            text-decoration: underline;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #FF0095;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form Edit Data Karyawan</h2>
        
        <!-- Form action ke proses_edit.php -->
        <form method="POST" action="proses_edit.php">
            <!-- Input hidden untuk menyimpan ID -->
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            
            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="divisi">Divisi:</label>
                <input type="text" id="divisi" name="divisi" value="<?php echo htmlspecialchars($data['divisi']); ?>" placeholder="Contoh: IT, HRD, Marketing" required>
            </div>

            <div class="form-group">
                <label for="jam_kerja_sepekan">Jam Kerja per Minggu:</label>
                <input type="number" id="jam_kerja_sepekan" name="jam_kerja_sepekan" value="<?php echo $data['jam_kerja_sepekan']; ?>" placeholder="Contoh: 40" min="0" max="168" required>
                <small>Maksimal 168 jam/minggu</small>
            </div>

            <div class="form-group">
                <label for="gaji_pokok">Gaji Pokok (Rp):</label>
                <input type="number" id="gaji_pokok" name="gaji_pokok" value="<?php echo $data['gaji_pokok']; ?>" placeholder="Contoh: 5000000" min="0" required>
            </div>

            <button type="submit">Update Data</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" class="btn-kembali">← Kembali ke Daftar Karyawan</a>
        </div>
    </div>
</body>
</html>