<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/payment_functions.php';

checkLogin(); // đảm bảo người dùng đã đăng nhập

// Kiểm tra tham số ID
if (!isset($_GET['id'])) {
    die("Thiếu tham số ID thanh toán!");
}

$id = (int)$_GET['id'];
$payment = getPaymentById($id);

if (!$payment) {
    die("Thanh toán không tồn tại!");
}

// Cập nhật trạng thái nếu chưa thanh toán
$updated = false;
if ($payment['trang_thai'] !== 'Đã thanh toán') {
    $updated = updatePaymentStatus($id, 'Đã thanh toán');
    if ($updated) {
        $payment['trang_thai'] = 'Đã thanh toán';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán <?= $updated ? 'thành công' : 'đã hoàn tất' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4 text-center">
    <h3>Thanh toán <?= $updated ? 'thành công!' : 'đã được thực hiện trước đó!' ?></h3>
    <p><strong>ID Thanh toán:</strong> <?= $payment['id'] ?></p>
    <p><strong>Số tiền:</strong> <?= number_format($payment['so_tien'],0,',','.') ?> đ</p>
    <p><strong>Booking ID:</strong> <?= $payment['booking_id'] ?></p>
    <p><strong>Trạng thái hiện tại:</strong> <?= htmlspecialchars($payment['trang_thai']) ?></p>
    <a href="payments.php" class="btn btn-primary mt-3">Quay lại danh sách thanh toán</a>
</div>
</body>
</html>
