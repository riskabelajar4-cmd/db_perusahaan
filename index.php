<?php

// array multidimensi (5 data, sama seperti di database)
$karyawan = [
    [
        "id" => 1,
        "nama" => "ROIF",
        "divisi" => "IT",
        "jam_kerja_sepekan" => 45,
        "gaji_pokok" => 5000000
    ],
    [
        "id" => 2,
        "nama" => "PUTRI",
        "divisi" => "HRD",
        "jam_kerja_sepekan" => 38,
        "gaji_pokok" => 4500000
    ],
    [
        "id" => 3,
        "nama" => "MAYA",
        "divisi" => "Marketing",
        "jam_kerja_sepekan" => 42,
        "gaji_pokok" => 4800000
    ],
    [
        "id" => 4,
        "nama" => "WIDYA",
        "divisi" => "Finance",
        "jam_kerja_sepekan" => 40,
        "gaji_pokok" => 4700000
    ],
    [
        "id" => 5,
        "nama" => "RISMA",
        "divisi" => "IT",
        "jam_kerja_sepekan" => 50,
        "gaji_pokok" => 5200000
    ]
];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Manajemen Karyawan</title>
</head>
<body>

<h2>Data Karyawan</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Divisi</th>
        <th>Jam Kerja</th>
        <th>Gaji Pokok</th>
        <th>Status</th>
    </tr>

<?php

// perulangan foreach
foreach ($karyawan as $data) {
?>
    <tr>
        <td><?php echo $data["id"]; ?></td>
        <td><?php echo $data["nama"]; ?></td>
        <td><?php echo $data["divisi"]; ?></td>
        <td><?php echo $data["jam_kerja_sepekan"]; ?> jam</td>
        <td>Rp <?php echo number_format($data["gaji_pokok"], 0, ',', '.'); ?></td>
        <td>

        
            <?php

            // pengkondisian if/else
            if ($data["jam_kerja_sepekan"] > 40) {
                echo "Bonus Overtime";
            } else {
                echo "Jam Kerja Normal";
            }
            ?>
        </td>
    </tr>
<?php
}
?>

</table>

</body>
</html>