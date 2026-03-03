<?php
// proses_data.php

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

    // =====================================================
    // TAMPILKAN STRUK (TANPA SIMPAN KE DATABASE)
    // =====================================================
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Struk Data Karyawan</title>
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
                padding: 30px 20px;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                border: 2px dashed #FF0095;
            }
            .header {
                text-align: center;
                border-bottom: 2px dashed #333;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }
            .header h1 {
                margin: 0;
                color: #FF0095;
                font-size: 24px;
                letter-spacing: 2px;
            }
            .header p {
                margin: 5px 0;
                color: #666;
                font-size: 12px;
            }
            .struk-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
                padding: 5px 0;
                border-bottom: 1px dotted #ccc;
            }
            .label {
                font-weight: bold;
                color: #333;
            }
            .value {
                color: #FF0095;
                font-weight: bold;
            }
            .status-box {
                text-align: center;
                margin: 20px 0;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 5px;
                font-size: 18px;
                font-weight: bold;
            }
            .status-bonus {
                color: green;
            }
            .status-normal {
                color: blue;
            }
            .status-gagal {
                color: red;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                padding-top: 15px;
                border-top: 2px dashed #333;
            }
            .btn {
                display: inline-block;
                background-color: #FF0095;
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 5px;
                margin: 5px;
                font-weight: bold;
                font-family: Arial, sans-serif;
            }
            .btn:hover {
                background-color: #d4007a;
            }
            .btn-secondary {
                background-color: #6c757d;
            }
            .btn-secondary:hover {
                background-color: #5a6268;
            }
            .tanggal {
                text-align: center;
                color: #666;
                font-size: 12px;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- HEADER STRUK -->
            <div class="header">
                <h1>⚡ STRUK DATA KARYAWAN ⚡</h1>
                <p>No. Transaksi: #TRX-<?php echo date('YmdHis'); ?></p>
                <p>Tanggal: <?php echo date('d/m/Y H:i:s'); ?></p>
                <p style="font-size: 10px;">* Ini hanya struk sementara, data TIDAK disimpan</p>
            </div>

            <!-- DATA KARYAWAN -->
            <div class="struk-item">
                <span class="label">Nama:</span>
                <span class="value"><?php echo htmlspecialchars($nama); ?></span>
            </div>

            <div class="struk-item">
                <span class="label">Divisi:</span>
                <span class="value"><?php echo htmlspecialchars($divisi); ?></span>
            </div>

            <div class="struk-item">
                <span class="label">Jam Kerja:</span>
                <span class="value"><?php echo htmlspecialchars($jam_kerja); ?> jam/minggu</span>
            </div>

            <div class="struk-item">
                <span class="label">Gaji Pokok:</span>
                <span class="value">Rp <?php echo number_format($gaji, 0, ',', '.'); ?></span>
            </div>

            <!-- STATUS KARYAWAN -->
            <div class="status-box">
                <?php
                $jam_kerja_int = intval($jam_kerja);
                if ($jam_kerja_int > 40) {
                    echo "<span class='status-bonus'>⭐ BONUS OVERTIME ⭐</span>";
                } elseif ($jam_kerja_int <= 0) {
                    echo "<span class='status-gagal'>❌ TIDAK MENDAPAT GAJI ❌</span>";
                } else {
                    echo "<span class='status-normal'>✓ JAM KERJA NORMAL ✓</span>";
                }
                ?>
            </div>

            <!-- INFORMASI TAMBAHAN -->
            <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; margin: 15px 0; font-size: 12px;">
                <p style="margin: 5px 0;"><strong>📌 Catatan:</strong></p>
                <p style="margin: 5px 0;">✓ Data ini bersifat sementara dan TIDAK disimpan di database</p>
                <p style="margin: 5px 0;">✓ Struk ini hanya sebagai bukti input data</p>
                <p style="margin: 5px 0;">✓ Silakan screenshot untuk dokumentasi</p>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="footer">
                <a href="tambah_data.php" class="btn">📝 Input Lagi</a>
                <a href="index.php" class="btn btn-secondary">🏠 Ke Halaman Utama</a>
            </div>
            
            <div class="tanggal">
                <p>Terima kasih telah menggunakan sistem kami</p>
                <p>© Riska Damayanti - <?php echo date('Y'); ?></p>
            </div>
        </div>
    </body>
    </html>
    <?php

} else {
    // Jika akses langsung tanpa POST
    header("Location: tambah_data.php");
    exit;
}
?>