<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/payment_functions.php';

checkLogin();
$payments = getAllPayments();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        td.qr img {
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3>Danh sách thanh toán</h3>
    <a href="create_payment.php" class="btn btn-primary mb-2">+ Thêm thanh toán</a>
    <table class="table table-bordered align-middle text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Booking ID</th>
                <th>Số tiền</th>
                <th>Ngày thanh toán</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>QR Thanh toán</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($payments as $p): ?>
            <?php
            // Link thanh toán khi quét QR
            $qrLink = "http://localhost/hieu/views/payments_pay.php?id={$p['id']}";
            // Tạo URL QR code sử dụng Google Chart API
            $qrCodeUrl = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=" . urlencode($qrLink);
            ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['booking_id'] ?></td>
                <td><?= number_format($p['so_tien'],0,',','.') ?> đ</td>
                <td><?= $p['ngay_thanh_toan'] ?></td>
                <td><?= htmlspecialchars($p['phuong_thuc']) ?></td>
                <td><?= htmlspecialchars($p['trang_thai']) ?></td>
                <td class="qr">
                    <img src="<?= $qrCodeUrl ?>" alt="QR code thanh toán">
                </td>
                <td>
                    <a href="edit_payment.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="../handle/payment_process.php?action=delete&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if(empty($payments)): ?>
            <tr>
                <td colspan="8" class="text-center text-muted">Chưa có thanh toán nào.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
