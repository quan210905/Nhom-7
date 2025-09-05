<?php
require_once __DIR__ . '/../functions/db_connection.php';

$action = $_POST['action'] ?? '';

if($action === 'create'){
    $booking_id = $_POST['booking_id'] ?? '';
    $so_tien = $_POST['so_tien'] ?? '';
    $ngay_thanh_toan = $_POST['ngay_thanh_toan'] ?? '';
    $phuong_thuc = $_POST['phuong_thuc'] ?? 'Tiền mặt';

    $conn = getDbConnection();
    $stmt = $conn->prepare("INSERT INTO payments (booking_id, so_tien, ngay_thanh_toan, phuong_thuc, trang_thai) VALUES (?, ?, ?, ?, 'Đã thanh toán')");
    $stmt->bind_param("idss", $booking_id, $so_tien, $ngay_thanh_toan, $phuong_thuc);

    if($stmt->execute()){
        header("Location: ../views/payments/payments.php?success=Thanh toán thành công");
    } else {
        header("Location: ../views/payments/create_payment.php?error=Lỗi khi thanh toán");
    }

    $stmt->close();
    $conn->close();
}
