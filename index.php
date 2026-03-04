<?php
//Fitur Pencarian
include 'koneksi.php'; // Panggil koneksi database

// MENANGKAP DATA PENCARIAN (GET) DENGAN AMAN
$keyword = '';
if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']); // Simpan keyword pencarian dan bersihkan spasi
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Manajemen Karyawan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #FF0095;
            padding-bottom: 10px;
        }
        
        /* FORM PENCARIAN*/
        .search-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 250px;
        }
        .search-input:focus {
            outline: none;
            border-color: #FF0095;
            box-shadow: 0 0 5px rgba(255,0,149,0.3);
        }
        .search-btn {
            background-color: #FF0095;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .search-btn:hover {
            background-color: #d4007a;
        }
        .reset-btn {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .reset-btn:hover {
            background-color: #5a6268;
        }
        
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-top: 20px;
        }
        th { 
            background-color: #FF0095; 
            color: white; 
            padding: 12px;
            text-align: left;
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border: 1px solid #ddd;
        }
        tr:nth-child(even) { 
            background-color: #f2f2f2; 
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn-tambah {
            display: inline-block;
            background-color: #FF0095;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-tambah:hover { 
            background-color: #d4007a;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .status-bonus {
            color: green;
            font-weight: bold;
        }
        .status-normal {
            color: blue;
        }
        .status-gagal {
            color: red;
            font-weight: bold;
        }
        
        
        /* NOTIFIKASI DATA TIDAK DITEMUKAN */
        .not-found {
            text-align: center;
            padding: 50px 20px;
            background-color: #fff8f8;
            border-radius: 10px;
            border: 2px dashed #FF0095;
            margin: 20px 0;
        }
        .not-found .icon {
            font-size: 64px;
            margin-bottom: 20px;
            display: block;
        }
        .not-found h3 {
            color: #FF0095;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .not-found p {
            color: #666;
            font-size: 16px;
            margin: 10px 0;
        }
        .not-found .keyword-highlight {
            background-color: #FF0095;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .not-found .action-links {
            margin-top: 25px;
        }
        .not-found .action-links a {
            display: inline-block;
            padding: 10px 25px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .not-found .reset-link {
            background-color: #6c757d;
            color: white;
        }
        .not-found .reset-link:hover {
            background-color: #5a6268;
        }
        .not-found .tambah-link {
            background-color: #FF0095;
            color: white;
        }
        .not-found .tambah-link:hover {
            background-color: #d4007a;
        }
        
        /* Info pencarian */
        .search-info {
            background-color: #e7f3ff;
            border-left: 4px solid #FF0095;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 14px;
            border-radius: 0 5px 5px 0;
        }
        .search-info strong {
            color: #FF0095;
        }
        
        /* Alert sukses */
        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sistem Manajemen Karyawan</h2>

        <!-- Tampilkan notifikasi jika ada -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert-success">
                <strong>✅ Berhasil!</strong> Data karyawan baru telah ditambahkan.
            </div>
        <?php endif; ?>

        <!-- Tombol Tambah Data -->
        <a href="tambah_data.php" class="btn-tambah">+ Tambah Data Baru</a>

        <!-- FORM PENCARIAN dengan method="GET" -->
        <form method="GET" action="index.php" class="search-form">
            <input type="text" 
                name="keyword" 
                class="search-input"
                placeholder="Ketik kata kunci (nama/divisi)..." 
                value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit" class="search-btn">Cari</button>
            <!-- Tombol Reset Pencarian -->
            <a href="index.php" class="reset-btn">Reset</a>
        </form>

        <!-- INFO PENCARIAN (tampil jika ada keyword) -->
        <?php if (!empty($keyword)): ?>
            <div class="search-info">
                <strong>🔍 Hasil pencarian untuk:</strong> "<?php echo htmlspecialchars($keyword); ?>"
            </div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Jam Kerja</th>
                <th>Gaji Pokok</th>
                <th>Status</th>
            </tr>

            <?php
            // LOGIKA PENCARIAN DAN TAMPIL DATA DARI DATABASE
            if (!empty($keyword)) {
                // Gunakan prepared statement untuk keamanan dari SQL Injection
                $query = "SELECT * FROM karyawan WHERE nama LIKE ? OR divisi LIKE ? ORDER BY id ASC";
                $stmt = mysqli_prepare($koneksi, $query);
                $search_term = "%" . $keyword . "%";
                mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            } else {
                // Tampilkan semua data jika tidak ada pencarian, diurutkan dari ID terkecil
                $query = "SELECT * FROM karyawan ORDER BY id ASC";
                $result = mysqli_query($koneksi, $query);
            }
            
            // Flag untuk mengecek apakah ada data yang cocok
            $data_ditemukan = false;
            $jumlah_tampil = 0;
            
            // Looping data karyawan dari database menggunakan mysqli_fetch_assoc
            while ($data = mysqli_fetch_assoc($result)) {
                $data_ditemukan = true;
                $jumlah_tampil++;
                
                // Tentukan class untuk status
                $status_class = '';
                $status_text = '';
                
                if ($data["jam_kerja_sepekan"] > 40) {
                    $status_class = 'status-bonus';
                    $status_text = 'Bonus Overtime';
                } elseif ($data["jam_kerja_sepekan"] <= 0) {
                    $status_class = 'status-gagal';
                    $status_text = '⚠️ Tidak dapat gaji';
                } else {
                    $status_class = 'status-normal';
                    $status_text = 'Jam Kerja Normal';
                }
                ?>
                <tr>
                    <td><?php echo $data["id"]; ?></td>
                    <td><?php echo htmlspecialchars($data["nama"]); ?></td>
                    <td><?php echo htmlspecialchars($data["divisi"]); ?></td>
                    <td><?php echo $data["jam_kerja_sepekan"]; ?> jam</td>
                    <td>Rp <?php echo number_format($data["gaji_pokok"], 0, ',', '.'); ?></td>
                    <td class="<?php echo $status_class; ?>"><?php echo $status_text; ?></td>
                </tr>
                <?php
            }
            
            // NOTIFIKASI JIKA TIDAK ADA DATA DITEMUKAN
            if (!$data_ditemukan) {
                // Tampilkan pesan dalam satu baris tabel
                echo '<tr><td colspan="6">';
                echo '<div class="not-found">';
                echo '<span class="icon">🔍</span>';
                echo '<h3>Data Tidak Ditemukan</h3>';
                echo '<p>Maaf, karyawan dengan kata kunci</p>';
                echo '<span class="keyword-highlight">"' . htmlspecialchars($keyword) . '"</span>';
                echo '<p>tidak ada dalam database kami.</p>';
                echo '<div class="action-links">';
                echo '<a href="index.php" class="reset-link">⟲ Reset Pencarian</a>';
                echo '<a href="tambah_data.php" class="tambah-link">➕ Tambah Data Baru</a>';
                echo '</div>';
                echo '</div>';
                echo '</td></tr>';
            }
            
            // Hitung total data di database
            $query_total = "SELECT COUNT(*) as total FROM karyawan";
            $result_total = mysqli_query($koneksi, $query_total);
            $total_karyawan = mysqli_fetch_assoc($result_total)['total'];
            ?>
        </table>
        
        <div style="margin-top: 20px; text-align: center; color: #666; font-size: 14px;">
            <?php if (!empty($keyword) && $data_ditemukan): ?>
                <p>Menampilkan <strong><?php echo $jumlah_tampil; ?></strong> dari <strong><?php echo $total_karyawan; ?></strong> karyawan</p>
            <?php elseif (empty($keyword)): ?>
                <p>Total Karyawan: <strong><?php echo $total_karyawan; ?></strong> orang</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>