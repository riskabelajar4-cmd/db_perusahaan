<?php
// index.php
// TIDAK PERLU KONEKSI DATABASE KARENA PAKAI DATA DUMMY
?>

<!DOCTYPE html>
<html>
<head>
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
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #FF0095;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sistem Manajemen Karyawan</h2>

        <!-- Tombol Tambah Data -->
        <a href="tambah_data.php" class="btn-tambah">+ Tambah Data Baru</a>

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
            // DATA DUMMY (TIDAK DARI DATABASE)
            $karyawan_dummy = [
                ["id" => 1, "nama" => "ROIF", "divisi" => "IT", "jam_kerja" => 45, "gaji" => 5000000],
                ["id" => 2, "nama" => "PUTRI", "divisi" => "HRD", "jam_kerja" => 38, "gaji" => 4500000],
                ["id" => 3, "nama" => "MAYA", "divisi" => "Marketing", "jam_kerja" => 42, "gaji" => 4800000],
                ["id" => 4, "nama" => "WIDYA", "divisi" => "Finance", "jam_kerja" => 40, "gaji" => 4700000],
                ["id" => 5, "nama" => "RISMA", "divisi" => "IT", "jam_kerja" => 50, "gaji" => 5200000]
            ];
            
            foreach ($karyawan_dummy as $data) {
                // Tentukan class untuk status
                $status_class = '';
                $status_text = '';
                
                if ($data["jam_kerja"] > 40) {
                    $status_class = 'status-bonus';
                    $status_text = 'Bonus Overtime';
                } elseif ($data["jam_kerja"] <= 0) {
                    $status_class = 'status-gagal';
                    $status_text = '⚠️ Tidak dapat gaji';
                } else {
                    $status_class = 'status-normal';
                    $status_text = 'Jam Kerja Normal';
                }
            ?>
            <tr>
                <td><?php echo $data["id"]; ?></td>
                <td><?php echo $data["nama"]; ?></td>
                <td><?php echo $data["divisi"]; ?></td>
                <td><?php echo $data["jam_kerja"]; ?> jam</td>
                <td>Rp <?php echo number_format($data["gaji"], 0, ',', '.'); ?></td>
                <td class="<?php echo $status_class; ?>"><?php echo $status_text; ?></td>
            </tr>
            <?php } ?>
        </table>
        
        <div style="margin-top: 20px; text-align: center; color: #666; font-size: 14px;">
            <p>Total Karyawan: 5 orang (data dummy)</p>
            <p style="font-size: 12px;">Data yang diinput melalui form TIDAK akan tersimpan di sini</p>
        </div>
    </div>
</body>
</html>