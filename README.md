# 📝 Note Storage System

Hệ thống lưu trữ và chia sẻ ghi chú đơn giản, nhanh chóng với giao diện hiện đại.

## ✨ Tính năng

- **Lưu trữ nhanh**: Tạo và lưu ghi chú chỉ với một cú click
- **Chia sẻ dễ dàng**: Mỗi note có link riêng để chia sẻ
- **Quản lý tập trung**: Admin panel để quản lý tất cả notes
- **Giao diện đơn giản**: Thiết kế tối giản, tập trung vào nội dung
- **Bảo mật**: Yêu cầu đăng nhập để truy cập trang quản lý

## 📦 Yêu cầu

- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- PDO extension
- Apache/Nginx web server

## 🚀 Cài đặt

### 1. Tạo cơ sở dữ liệu

Chạy SQL từ file `db.sql`:

```sql
CREATE DATABASE note_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE note_db;

CREATE TABLE IF NOT EXISTS note (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash VARCHAR(64) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_hash (hash),
    INDEX idx_upload_date (upload_date)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
```

### 2. Cấu hình

Chỉnh sửa file `config.php`:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'note_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Login Credentials
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin123');

// Site Configuration
define('SITE_URL', 'http://note.smm79.com/');
```

### 3. Deploy

Upload tất cả files lên web server và truy cập domain của bạn.

## 📁 Cấu trúc File

```
note.smm79.com/
├── config.php      # Cấu hình hệ thống
├── index.php       # Trang chủ - Tạo note mới
├── view.php        # Hiển thị note
├── login.php       # Trang đăng nhập admin
├── manage.php      # Quản lý notes
├── db.sql          # Schema database
└── README.md       # File này
```

## 🎯 Hướng dẫn sử dụng

### Tạo Note Mới

1. Truy cập trang chủ
2. Nhập nội dung vào textarea
3. Click nút "Save & Get Link"
4. Copy link để chia sẻ

### Xem Note

- Truy cập link dạng: `view.php?hash=xxxxxxxxxx`
- Click "Copy Content" để sao chép nội dung

### Quản lý Notes (Admin)

1. Truy cập `login.php`
2. Đăng nhập với credentials đã cấu hình
3. Vào `manage.php` để:
   - Xem danh sách tất cả notes
   - Xem nội dung note
   - Copy link chia sẻ
   - Xóa note

## 🔐 Bảo mật

### Đổi mật khẩu Admin

Chỉnh sửa trong `config.php`:

```php
define('ADMIN_USER', 'your_username');
define('ADMIN_PASS', 'your_secure_password');
```

### Khuyến nghị

- Sử dụng mật khẩu mạnh
- Không chia sẻ thông tin đăng nhập
- Định kỳ backup database
- Cân nhắc thêm HTTPS cho production

## 🎨 Tính năng giao diện

- **Responsive**: Tự động điều chỉnh trên mọi thiết bị
- **Dark on Light**: Giao diện sáng, dễ đọc
- **Minimal Design**: Tập trung vào nội dung
- **Smooth Animations**: Hiệu ứng chuyển động mượt mà

## 🛠️ Công nghệ

- **Backend**: PHP với PDO
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Storage**: Session-based authentication

## 📝 Database Schema

### Table: `note`

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key, auto increment |
| hash | VARCHAR(64) | Unique identifier cho mỗi note |
| content | LONGTEXT | Nội dung note |
| upload_date | TIMESTAMP | Thời gian tạo |

### Indexes

- `idx_hash`: Tìm kiếm nhanh theo hash
- `idx_upload_date`: Sắp xếp theo ngày tạo

## 🔄 Quy trình hoạt động

1. **Tạo Note**: User nhập nội dung → System tạo hash unique → Lưu vào DB
2. **Xem Note**: Truy cập với hash → Query DB → Hiển thị nội dung
3. **Quản lý**: Login → Session check → CRUD operations

## 📊 Thông số

- **Max Note Size**: ~4GB (LONGTEXT)
- **Hash Length**: 10 ký tự
- **Hash Algorithm**: SHA-256
- **Encoding**: UTF-8 (utf8mb4)

## 🤝 Đóng góp

Mọi đóng góp đều được hoan nghênh! Feel free to:

- Báo lỗi (issues)
- Đề xuất tính năng mới
- Submit pull requests
- Cải thiện documentation

## 📄 License

Free to use for personal and commercial projects.

## 👤 Tác giả

Developed for note.smm79.com

## 📞 Hỗ trợ

Nếu gặp vấn đề, hãy:
1. Kiểm tra cấu hình trong `config.php`
2. Verify database connection
3. Check PHP error logs
4. Ensure proper file permissions

---

**Version**: 1.0.0  
**Last Updated**: February 6, 2026
