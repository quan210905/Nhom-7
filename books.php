<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sách thư viện</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Montserrat', Arial, sans-serif;
            position: relative;
        }
        .overlay-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.78);
            z-index: 0;
        }
        .container {
            position: relative;
            z-index: 1;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            border: none;
            background: #fff;
        }
        .table thead th {
            background: #e9f1fb;
            font-weight: 700;
            font-size: 1.08rem;
            color: #2563eb;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background: #f1f5fd;
        }
        .btn-primary {
            background: linear-gradient(90deg, #2563eb 60%, #60a5fa 100%);
            border: none;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(37,99,235,0.08);
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #1d4ed8 60%, #38bdf8 100%);
        }
        .btn-warning {
            color: #fff;
            background: linear-gradient(90deg, #f59e42 60%, #fbbf24 100%);
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-warning:hover {
            background: linear-gradient(90deg, #f97316 60%, #fde68a 100%);
        }
        .btn-danger {
            background: linear-gradient(90deg, #ef4444 60%, #f87171 100%);
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-danger:hover {
            background: linear-gradient(90deg, #dc2626 60%, #fca5a5 100%);
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .table {
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 16px rgba(0,0,0,0.04);
        }
        h3 {
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(37,99,235,0.10);
        }
        .table-responsive {
            border-radius: 16px;
        }
    </style>
</head>
<body>
    <div class="overlay-bg"></div>
    <?php include './menu.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">
                            <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png" alt="icon" class="header-icon">
                            DANH SÁCH SÁCH THƯ VIỆN
                        </h3>
                        <a href="books/create_book.php" class="btn btn-primary px-4 py-2">+ Thêm sách</a>
                    </div>
                    <?php
                    if (isset($_GET['success'])) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['success']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                            . htmlspecialchars($_GET['error']) .
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
                    }
                    ?>
                    <script>
                    setTimeout(() => {
                        let alertNode = document.querySelector('.alert');
                        if (alertNode) {
                            let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                            bsAlert.close();
                        }
                    }, 3000);
                    </script>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tên sách</th>
                                    <th scope="col">Tác giả</th>
                                    <th scope="col">Năm xuất bản</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Mô tả</th>
                                    <th scope="col" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require '../handle/books_process.php';
                                $books = handleGetAllBooks();
                                foreach($books as $book){
                                ?>
                                <tr>
                                    <td><?= $book["id"] ?></td>
                                    <td><?= htmlspecialchars($book["ten_sach"]) ?></td>
                                    <td><?= htmlspecialchars($book["tac_gia"]) ?></td>
                                    <td><?= htmlspecialchars($book["nam_xuat_ban"]) ?></td>
                                    <td><?= htmlspecialchars($book["so_luong"]) ?></td>
                                    <td><?= htmlspecialchars($book["mo_ta"]) ?></td>
                                    <td class="text-center">
                                        <a href="books/edit_book.php?id=<?= $book["id"] ?>" class="btn btn-warning btn-sm me-1">Sửa</a>
                                        <a href="../handle/books_process.php?action=delete&id=<?= $book["id"] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?')">Xóa</a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if (empty($books)) { ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Chưa có sách nào.</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>