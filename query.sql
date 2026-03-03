--membuat database
CREATE DATABASE db_perusahaan;

--mengguanakan Database
USE db_perusahaan;

--membuat table karyawan
CREATE TABLE karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    divisi VARCHAR(100) NOT NULL,
    jam_kerja_sepekan INT NOT NULL,
    gaji_pokok DECIMAL(10,2) NOT NULL
);

--insert 5 data dummy
INSERT INTO karyawan (nama, divisi, jam_kerja_sepekan, gaji_pokok) VALUES
('ROIF', 'IT', 45, 5000000),
('PUTRI', 'HRD', 38, 4500000),
('MAYA', 'Marketing', 42, 4800000),
('WIDYA', 'Finance', 40, 4700000),
('RISMA', 'IT', 50, 5200000);

--menampilkan data karyawan
SELECT * FROM karyawan;