<?php
//Menghapus data karyawan dengan reset ID otomatis
include 'koneksi.php'; // Panggil koneksi database

// Tangkap ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Cek apakah ID ada
if (empty($id)) {
    // Jika tidak ada ID, redirect ke index.php
    header("Location: index.php");
    exit;
}


// HAPUS DATA DARI DATABASE

// Query DELETE dengan prepared statement
$query = "DELETE FROM karyawan WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);

// "i" = integer (untuk ID)
mysqli_stmt_bind_param($stmt, "i", $id);

// Eksekusi query hapus
if (mysqli_stmt_execute($stmt)) {
    // Tutup statement hapus
    mysqli_stmt_close($stmt);
    
    // Matikan sementara foreign key checks (jika ada)
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");
    
    // Ambil semua data dengan urutan ID lama
    $ambil_data = mysqli_query($koneksi, "SELECT * FROM karyawan ORDER BY id ASC");
    
    // Hapus semua data dari tabel
    mysqli_query($koneksi, "DELETE FROM karyawan");
    
    // Reset auto increment ke 1
    mysqli_query($koneksi, "ALTER TABLE karyawan AUTO_INCREMENT = 1");
    
    // Insert kembali data dengan ID baru yang berurutan
    $no = 1;
    while ($data = mysqli_fetch_assoc($ambil_data)) {
        $nama = mysqli_real_escape_string($koneksi, $data['nama']);
        $divisi = mysqli_real_escape_string($koneksi, $data['divisi']);
        $jam_kerja = $data['jam_kerja_sepekan'];
        $gaji = $data['gaji_pokok'];
        
        // Insert dengan ID baru yang berurutan
        $insert = "INSERT INTO karyawan (id, nama, divisi, jam_kerja_sepekan, gaji_pokok) 
                    VALUES ($no, '$nama', '$divisi', $jam_kerja, $gaji)";
        mysqli_query($koneksi, $insert);
        $no++;
    }
    
    // Reset auto increment ke nilai terakhir + 1
    $max_id = $no - 1;
    $next_id = $max_id + 1;
    mysqli_query($koneksi, "ALTER TABLE karyawan AUTO_INCREMENT = $next_id");
    
    // Hidupkan kembali foreign key checks
    mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
    
    // Redirect ke index.php dengan status deleted
    header("Location: index.php?status=deleted");
    exit;
    
} else {
    // Jika gagal hapus, tampilkan pesan error
    echo "Error menghapus data: " . mysqli_error($koneksi);
    exit;
}
?>