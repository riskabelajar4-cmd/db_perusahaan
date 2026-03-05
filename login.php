<?php
// Halaman Login User
session_start();

// Jika sudah login, redirect ke index.php
if (isset($_SESSION['is_logged_in'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

$error = '';
$success = '';

// Cek apakah ada parameter register=success
if (isset($_GET['register']) && $_GET['register'] == 'success') {
    $success = "Registrasi berhasil! Silakan login dengan akun Anda.";
}

// Cek apakah ada parameter logout=success (TAMBAHAN)
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $success = "Anda berhasil logout!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data pakai $_POST
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        // Cari username tersebut di database (pakai Prepared Statement)
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Jika username ketemu, cek password-nya menggunakan password_verify()
            if (password_verify($password, $user['password'])) {
                // Jika password cocok, jalankan session_start(); di baris paling atas sudah
                
                // Simpan sesi user
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login_time'] = time();
                
                // Redirect user ke index.php (halaman CRUD)
                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Karyawan</title>
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
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
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
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #999;
        }
        .demo-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
            border: 1px dashed #ccc;
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
    <div class="login-container">
        <h2>🔐 Login</h2>
        <div class="subtitle">
            Belum punya akun? <a href="register.php">Daftar disini</a>
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
        
        <div class="demo-info">
            <strong>ℹ️ Info:</strong> Silakan register akun baru terlebih dahulu jika belum memiliki akun
        </div>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" 
                        placeholder="Masukkan username" 
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                        required>
                </div>
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
                <div class="checkbox-container">
                    <input type="checkbox" id="showPassword" onclick="togglePasswordCheckbox()">
                    <label for="showPassword">Tampilkan password</label>
                </div>
            </div>
            
            <button type="submit">🔑 Masuk</button>
        </form>
        
        <div class="footer">
            &copy; <?php echo date('Y'); ?> Sistem Manajemen Karyawan
        </div>
    </div>

    <script>
        // Fitur Toggle Password untuk icon
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Ganti icon
            button.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
            
            // Update checkbox jika perlu
            const checkbox = document.getElementById('showPassword');
            if (checkbox && inputId === 'password') {
                checkbox.checked = (type === 'text');
            }
        }
        
        // Fitur Toggle Password untuk checkbox
        function togglePasswordCheckbox() {
            const passwordInput = document.getElementById('password');
            const checkbox = document.getElementById('showPassword');
            
            const type = checkbox.checked ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Update icon
            const toggleButton = document.querySelector('.toggle-password');
            if (toggleButton) {
                toggleButton.textContent = checkbox.checked ? '👁️‍🗨️' : '👁️';
            }
        }
    </script>
</body>
</html>