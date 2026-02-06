<?php
require_once 'config.php';

$hash = isset($_GET['hash']) ? trim($_GET['hash']) : '';
$note = null;

if (!empty($hash)) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM note WHERE hash = ?");
        $stmt->execute([$hash]);
        $note = $stmt->fetch();
    } catch (PDOException $e) {
        $note = null;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $note ? 'View Note' : 'Not Found'; ?></title>
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
            max-width: 700px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eee;
        }
        
        h1 {
            color: #333;
            font-size: 20px;
            font-weight: 600;
        }
        
        .date {
            color: #888;
            font-size: 13px;
        }
        
        .content {
            background: #fafafa;
            border-radius: 8px;
            padding: 24px;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.7;
            color: #444;
            min-height: 200px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .not-found {
            text-align: center;
            padding: 60px 20px;
        }
        
        .not-found h2 {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 16px;
        }
        
        .not-found p {
            color: #888;
            margin-bottom: 24px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: background 0.2s;
        }
        
        .btn:hover {
            background: #555;
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
        
        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        
        .copy-btn {
            padding: 10px 20px;
            background: #eee;
            color: #333;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.2s;
        }
        
        .copy-btn:hover {
            background: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($note): ?>
            <div class="header">
                <h1>📝 Note</h1>
                <span class="date"><?php echo date('d/m/Y H:i', strtotime($note['upload_date'])); ?></span>
            </div>
            
            <div class="content" id="noteContent"><?php echo htmlspecialchars($note['content']); ?></div>
            
            <div class="actions">
                <button class="copy-btn" onclick="copyContent()">📋 Copy Content</button>
            </div>
            
            <div class="footer">
                <a href="index.php">← Tạo note mới</a>
            </div>
        <?php else: ?>
            <div class="not-found">
                <h2>404</h2>
                <p>Note không tồn tại hoặc đã bị xóa.</p>
                <a href="index.php" class="btn">← Về trang chủ</a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function copyContent() {
            const content = document.getElementById('noteContent').innerText;
            navigator.clipboard.writeText(content).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.textContent = '✓ Copied!';
                setTimeout(() => btn.textContent = '📋 Copy Content', 2000);
            });
        }
    </script>
</body>
</html>
