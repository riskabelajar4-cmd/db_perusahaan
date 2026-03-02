<?php

//array multidimensi data karyawan
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

//perulangan untuk menampilkan data + pengkomdisian
foreach ($karyawan as $data) {
    echo "ID: " . $data["id"] . "<br>";
    echo "Nama: " . $data["nama"] . "<br>";
    echo "Divisi: " . $data["divisi"] . "<br>";
    echo "Jam Kerja: " . $data["jam_kerja_sepekan"] . "jam<br>";
    echo "Gaji Pokok: Rp " . number_format($data["gaji_pokko"], 0, ',', '.') . "<br>";

    //pengkondisian
    if ($data["jam_kerja_sepekan"] > 40) {
        echo "Status: <b>Bonus Overtime</b>";
    } else {
        echo "Status: Jam Kerja Normal";
    }

    echo "<hr>";

}
?>