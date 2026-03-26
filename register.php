<!-- http://localhost/register.php -->

<?php
include "koneksi.php";

$success = false;
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $passw = password_hash($_POST["passw"], PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, passw) VALUES (:name, :email, :passw)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':passw', $passw);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar Akun</title>
    </head>
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
        
        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            padding: 20px;
        }
        
        .register-header {
            background: white;
            color: black;
            padding: 30px 30px 15px 30px;
            text-align: center;
        }
        
        .register-header h1 {
            font-size: 26px;
            margin-bottom: 5px;
        }
        
        .register-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #667eea;
            outline: none;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            display: <?php echo $success ? 'block' : 'none'; ?>;
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
        
        .btn-register {
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
            margin-top: 10px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Buat Akun Baru</h1>
            <p>Daftar untuk mengakses sistem</p>
        </div>
        
        <form class="register-form" method="POST" action="">
            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" id="name" name="name" required 
                       placeholder="Buat username unik">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       placeholder="Masukkan email Anda">
            </div>
            
            <div class="form-group">
                <label for="passw">Password</label>
                <input type="password" id="passw" name="passw" required 
                       placeholder="Buat password (min. 6 karakter)" autocomplete="off">
            </div>
            
            <button type="submit" class="btn-register">Daftar Sekarang</button>
            
            <div class="login-link">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </form>
    </div>
</body>
</body>
</html>