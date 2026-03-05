<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Halaman Registrasi User Baru
session_start();

// Jika sudah login, redirect ke index.php
if (isset($_SESSION['is_logged_in'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

// Cek koneksi database
if (!isset($koneksi) || !$koneksi) {
    die("Error: Koneksi database tidak tersedia. Periksa file koneksi.php");
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap inputan form menggunakan $_POST
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif (!preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) {
        $error = "Username minimal 3 karakter dan hanya huruf dan angka!";
    } else {
        // Cek apakah username sudah ada
        $check_query = "SELECT id FROM users WHERE username = ?";
        $check_stmt = mysqli_prepare($koneksi, $check_query);
        
        // Cek apakah prepared statement berhasil dibuat
        if (!$check_stmt) {
            die("Error pada prepared statement (cek username): " . mysqli_error($koneksi));
        }
        
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Keamanan: Acak password menggunakan password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Simpan username dan password yang sudah diacak ke tabel users
            $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($koneksi, $insert_query);
            
            // Cek apakah prepared statement berhasil dibuat
            if (!$insert_stmt) {
                die("Error pada prepared statement (insert): " . mysqli_error($koneksi));
            }
            
            mysqli_stmt_bind_param($insert_stmt, "ss", $username, $hashed_password);
            
            if (mysqli_stmt_execute($insert_stmt)) {
                // Jika berhasil, redirect ke halaman login.php
                header("Location: login.php?register=success");
                exit;
            } else {
                $error = "Gagal registrasi: " . mysqli_error($koneksi);
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - Sistem Manajemen Karyawan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #FF0095 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .subtitle a {
            color: #FF0095;
            text-decoration: none;
            font-weight: bold;
        }
        .subtitle a:hover {
            text-decoration: underline;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        input {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        input:focus {
            outline: none;
            border-color: #FF0095;
            background: white;
            box-shadow: 0 0 0 3px rgba(255,0,149,0.1);
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #666;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s;
            z-index: 10;
        }
        .toggle-password:hover {
            color: #FF0095;
        }
        .toggle-password:focus {
            outline: none;
        }
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: #FF0095;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background: #d4007a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,0,149,0.3);
        }
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c62828;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #2e7d32;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        .password-requirements {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }
        .password-requirements ul {
            margin-left: 20px;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #999;
        }
        .password-strength {
            margin-top: 5px;
            height: 5px;
            border-radius: 3px;
            background: #e0e0e0;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        .strength-text {
            font-size: 12px;
            margin-top: 3px;
            text-align: right;
        }
        .match-indicator {
            font-size: 12px;
            margin-top: 5px;
            padding-left: 5px;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }
        .checkbox-container input[type="checkbox"] {
            width: auto;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>📝 Buat Akun Baru</h2>
        <div class="subtitle">
            Sudah punya akun? <a href="login.php">Login disini</a>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                <span>⚠️</span> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <span>✅</span> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="registerForm">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" 
                        placeholder="Masukkan username" 
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                        required>
                </div>
                <div class="info-text">Username minimal 3 karakter, hanya huruf dan angka</div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" 
                        placeholder="Masukkan password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', this)" tabindex="-1">
                        👁️
                    </button>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrength"></div>
                </div>
                <div class="strength-text" id="strengthText"></div>
                <div class="checkbox-container">
                    <input type="checkbox" id="showPassword" onclick="toggleBothPasswords()">
                    <label for="showPassword">Tampilkan semua password</label>
                </div>
                <div class="password-requirements">
                    <strong>Persyaratan Password:</strong>
                    <ul>
                        <li>Minimal 6 karakter</li>
                        <li>Gunakan kombinasi huruf dan angka untuk keamanan lebih</li>
                    </ul>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" 
                        placeholder="Ulangi password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)" tabindex="-1">
                        👁️
                    </button>
                </div>
                <div class="match-indicator" id="passwordMatch"></div>
            </div>
            
            <button type="submit">🔐 Daftar Sekarang</button>
        </form>
        
        <div class="footer">
            &copy; <?php echo date('Y'); ?> Sistem Manajemen Karyawan
        </div>
    </div>

    <script>
        // Fitur Toggle Password untuk masing-masing field
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Ganti icon
            button.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        }
        
        // Fitur Toggle Password untuk kedua field sekaligus (via checkbox)
        function toggleBothPasswords() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            const checkbox = document.getElementById('showPassword');
            
            const type = checkbox.checked ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            confirmInput.setAttribute('type', type);
            
            // Update icon pada kedua tombol
            const toggleButtons = document.querySelectorAll('.toggle-password');
            const icon = checkbox.checked ? '👁️‍🗨️' : '👁️';
            toggleButtons.forEach(button => {
                button.textContent = icon;
            });
        }
        
        // Fitur Cek Kekuatan Password
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let message = '';
            let color = '';
            
            // Kriteria penilaian
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 10; // Bonus panjang
            if (password.match(/[a-z]+/)) strength += 15;
            if (password.match(/[A-Z]+/)) strength += 15;
            if (password.match(/[0-9]+/)) strength += 20;
            if (password.match(/[$@#&!%*?]+/)) strength += 15; // Karakter khusus
            
            // Batasi maksimal 100
            strength = Math.min(strength, 100);
            
            // Tentukan kategori
            if (strength < 30) {
                message = 'Terlalu lemah';
                color = '#ff4444';
            } else if (strength < 50) {
                message = 'Lemah';
                color = '#ff7744';
            } else if (strength < 70) {
                message = 'Sedang';
                color = '#ffaa44';
            } else if (strength < 90) {
                message = 'Kuat';
                color = '#44ff44';
            } else {
                message = 'Sangat Kuat';
                color = '#00cc00';
            }
            
            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = 'Kekuatan password: ' + message;
            strengthText.style.color = color;
        });
        
        // Fitur Cek Kecocokan Password
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
        document.getElementById('password').addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirm.length === 0) {
                matchDiv.innerHTML = '';
            } else if (password === confirm) {
                matchDiv.innerHTML = '✅ Password cocok';
                matchDiv.style.color = 'green';
            } else {
                matchDiv.innerHTML = '❌ Password tidak cocok';
                matchDiv.style.color = 'red';
            }
        }
        
        // Validasi form sebelum submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const username = document.getElementById('username').value;
            
            // Validasi username
            const usernameRegex = /^[a-zA-Z0-9]{3,}$/;
            if (!usernameRegex.test(username)) {
                e.preventDefault();
                alert('Username minimal 3 karakter dan hanya boleh huruf dan angka!');
                return false;
            }
            
            // Validasi password
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
            
            // Validasi kecocokan password
            if (password !== confirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
        });
    </script>
</body>
</html>