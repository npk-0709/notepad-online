<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: manage.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: manage.php');
        exit;
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        
        h1 {
            color: #333;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: center;
        }
        
        .subtitle {
            color: #888;
            font-size: 14px;
            text-align: center;
            margin-bottom: 32px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #555;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: #333;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 14px 24px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn:hover {
            background: #555;
        }
        
        .error {
            background: #ffebee;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        .footer {
            margin-top: 24px;
            text-align: center;
        }
        
        .footer a {
            color: #888;
            text-decoration: none;
            font-size: 13px;
        }
        
        .footer a:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin Login</h1>
        <p class="subtitle">Đăng nhập để quản lý notes</p>
        
        <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Đăng nhập</button>
        </form>
        
        <div class="footer">
            <a href="index.php">← Về trang chủ</a>
        </div>
    </div>
</body>
</html>
