<?php
require_once 'config.php';

$message = '';
$link = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $hash = substr(hash('sha256', $content . time() . rand()), 0, 10);

        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("INSERT INTO note (hash, content) VALUES (?, ?)");
            $stmt->execute([$hash, $content]);
            $link = SITE_URL . "view.php?hash=" . $hash;
            $message = 'success';
        } catch (PDOException $e) {
            $message = 'error';
            $errorMessage = $e->getMessage();
        }
    } else {
        $message = 'empty';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Online</title>
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 24px;
            text-align: center;
        }

        textarea {
            width: 100%;
            height: 250px;
            padding: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.2s;
        }

        textarea:focus {
            outline: none;
            border-color: #333;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px 24px;
            margin-top: 16px;
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

        .result {
            margin-top: 20px;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
        }

        .result.success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .result.error {
            background: #ffebee;
            color: #c62828;
        }

        .result a {
            color: inherit;
            word-break: break-all;
            font-weight: 500;
        }

        .link-box {
            margin-top: 12px;
            padding: 12px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            display: flex;
            gap: 10px;
        }

        .link-box input {
            flex: 1;
            padding: 8px;
            border: none;
            font-size: 14px;
            background: transparent;
        }

        .link-box input:focus {
            outline: none;
        }

        .copy-btn {
            padding: 8px 16px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .copy-btn:hover {
            background: #555;
        }

        .footer {
            margin-top: 20px;
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
        <h1>📝 Note Storage</h1>

        <form method="POST" action="">
            <textarea name="content" placeholder="Nhập nội dung note của bạn..."><?php echo isset($_POST['content']) && $message !== 'success' ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
            <button type="submit" class="btn">Save & Get Link</button>
        </form>

        <?php if ($message === 'success'): ?>
            <div class="result success">
                <div>✓ Lưu thành công!</div>
                <div class="link-box">
                    <input type="text" id="noteLink" value="<?php echo htmlspecialchars($link); ?>" readonly>
                    <button class="copy-btn" onclick="copyLink()">Copy</button>
                </div>
            </div>
        <?php elseif ($message === 'error'): ?>
            <div class="result error">
                ✕ Có lỗi xảy ra. Vui lòng thử lại! <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php elseif ($message === 'empty'): ?>
            <div class="result error">
                ✕ Vui lòng nhập nội dung!
            </div>
        <?php endif; ?>

        <div class="footer">
            <a href="login.php">Admin Login</a>
        </div>
    </div>

    <script>
        function copyLink() {
            const input = document.getElementById('noteLink');
            input.select();
            document.execCommand('copy');

            const btn = document.querySelector('.copy-btn');
            btn.textContent = 'Copied!';
            setTimeout(() => btn.textContent = 'Copy', 2000);
        }
    </script>
</body>

</html>