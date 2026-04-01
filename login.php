<!-- http://localhost/login.php -->

<?php
session_start();
include 'koneksi.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// ── Jika session kosong, cek COOKIE
if (empty($_SESSION['user_id']) && !empty($_COOKIE['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $_COOKIE['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Cookie valid → restore session → redirect ke index
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        header('Location: index.php');
        exit;
    } else {
        // Cookie tidak valid → hapus cookie
        setcookie('user_id',   '', time() - 3600, '/');
        setcookie('user_name', '', time() - 3600, '/');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $passw = $_POST['passw'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE name = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($passw, $user['passw'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];

            // COOKIES — untuk "Remember Me" selama 7 hari
            if (isset($_POST['remember_me'])) {
                setcookie('user_id',   $user['id'],   time() + (7 * 24 * 60 * 60), '/');
                setcookie('user_name', $user['name'], time() + (7 * 24 * 60 * 60), '/');
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0F172A 0%, #1E40AF 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            padding: 20px;
        }
        
        .login-header {
            background: white;
            color: black;
            padding: 30px 30px 15px 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            opacity: 0.9;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #667eea;
            outline: none;
        }
        
        .error-message {
            background-color: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
            display: <?php echo $error ? 'block' : 'none'; ?>;
        }
        
        .btn-login {
            background: #1E40AF;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Selamat Datang</h1>
            <p>Silakan login untuk melanjutkan</p>
        </div>
        
        <form class="login-form" method="POST" action="">
            <?php if($error): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" id="name" name="name" required 
                       placeholder="Masukkan username Anda">
            </div>
            
            <div class="form-group">
                <label for="passw">Password</label>
                <input type="password" id="passw" name="passw" required 
                       placeholder="Masukkan password Anda" autocomplete="off">
            </div>
            
            <button type="submit" class="btn-login">Login</button>
            
            <div class="form-group">
                <label for="remember_me">Ingat saya selama 7 hari</label> <input type="checkbox" id="remember_me" name="remember_me">
            </div>

            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </form>
    </div>
</body>
</html>