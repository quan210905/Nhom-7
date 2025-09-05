<?php
require_once '../functions/db_connection.php';
require_once '../functions/auth_check.php';
checkLogin();

$conn = getDbConnection();
$action = $_POST['action'] ?? '';

if($action === 'create') {
    $booking_id = intval($_POST['booking_id']);
    $sao = intval($_POST['sao']);
    $nhan_xet = $conn->real_escape_string($_POST['nhan_xet']);

    $sql = "INSERT INTO reviews (booking_id, sao, nhan_xet) 
            VALUES ($booking_id, $sao, '$nhan_xet')";

    if($conn->query($sql)) {
        header("Location: ../../views/bookings.php?success=Đánh giá đã gửi!");
        exit;
    } else {
        header("Location: ../../views/reviews/create_review.php?error=Lỗi: " . $conn->error);
        exit;
    }
}
