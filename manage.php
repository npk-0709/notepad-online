<?php
require_once 'config.php';
requireLogin();

$pdo = getDB();
$message = '';
$messageType = '';

// Handle Delete
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM note WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Đã xóa note thành công!';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Có lỗi xảy ra khi xóa!';
        $messageType = 'error';
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Get all notes
$notes = [];
try {
    $stmt = $pdo->query("SELECT * FROM note ORDER BY upload_date DESC");
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    $message = 'Có lỗi xảy ra khi tải dữ liệu!';
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notes</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 20px 30px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        h1 {
            color: #333;
            font-size: 22px;
            font-weight: 600;
        }
        
        .header-actions {
            display: flex;
            gap: 12px;
        }
        
        .header-actions a {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #333;
            color: #fff;
        }
        
        .btn-primary:hover {
            background: #555;
        }
        
        .btn-secondary {
            background: #eee;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #ddd;
        }
        
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .stats {
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        
        .stats strong {
            color: #333;
        }
        
        .message {
            padding: 16px 30px;
            font-size: 14px;
        }
        
        .message.success {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .message.error {
            background: #ffebee;
            color: #c62828;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #fafafa;
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #eee;
        }
        
        td {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #444;
        }
        
        tr:hover {
            background: #fafafa;
        }
        
        .hash {
            font-family: monospace;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
        }
        
        .actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-view {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .btn-view:hover {
            background: #bbdefb;
        }
        
        .btn-link {
            background: #e8f5e9;
            color: #388e3c;
        }
        
        .btn-link:hover {
            background: #c8e6c9;
        }
        
        .btn-delete {
            background: #ffebee;
            color: #c62828;
        }
        
        .btn-delete:hover {
            background: #ffcdd2;
        }
        
        .empty {
            padding: 60px 20px;
            text-align: center;
            color: #888;
        }
        
        .empty h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #999;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-header h3 {
            font-size: 18px;
            color: #333;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #888;
        }
        
        .modal-close:hover {
            color: #333;
        }
        
        .modal-body {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Quản lý Notes</h1>
            <div class="header-actions">
                <a href="index.php" class="btn-primary">+ Tạo Note</a>
                <a href="?logout=1" class="btn-secondary">Đăng xuất</a>
            </div>
        </div>
        
        <div class="card">
            <div class="stats">
                Tổng số: <strong><?php echo count($notes); ?></strong> notes
            </div>
            
            <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>
            
            <?php if (count($notes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hash</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?php echo $note['id']; ?></td>
                        <td><span class="hash"><?php echo htmlspecialchars($note['hash']); ?></span></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($note['upload_date'])); ?></td>
                        <td>
                            <div class="actions">
                                <button class="action-btn btn-view" onclick="showContent(<?php echo $note['id']; ?>, '<?php echo addslashes(htmlspecialchars($note['content'])); ?>')">
                                    👁 View
                                </button>
                                <button class="action-btn btn-link" onclick="copyLink('<?php echo SITE_URL; ?>view.php?hash=<?php echo $note['hash']; ?>')">
                                    🔗 Link
                                </button>
                                <a href="?delete=<?php echo $note['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa note này?')">
                                    🗑 Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty">
                <h3>Chưa có note nào</h3>
                <p>Hãy tạo note đầu tiên của bạn!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal" id="contentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📝 Nội dung Note</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalContent"></div>
        </div>
    </div>
    
    <script>
        // Store note contents
        const noteContents = {};
        <?php foreach ($notes as $note): ?>
        noteContents[<?php echo $note['id']; ?>] = <?php echo json_encode($note['content']); ?>;
        <?php endforeach; ?>
        
        function showContent(id) {
            document.getElementById('modalContent').textContent = noteContents[id] || '';
            document.getElementById('contentModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('contentModal').classList.remove('active');
        }
        
        function copyLink(link) {
            navigator.clipboard.writeText(link).then(() => {
                alert('Đã copy link!');
            });
        }
        
        // Close modal on outside click
        document.getElementById('contentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
