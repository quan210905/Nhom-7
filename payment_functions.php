<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy tất cả thanh toán
 * @return array
 */
function getAllPayments() {
    $conn = getDbConnection();
    $sql = "SELECT p.id, p.booking_id, b.user_id, b.tour_id, p.so_tien, p.ngay_thanh_toan, p.phuong_thuc, p.trang_thai
            FROM payments p
            LEFT JOIN bookings b ON p.booking_id = b.id
            ORDER BY p.id DESC";
    $result = $conn->query($sql);
    if (!$result) {
        die("Lỗi khi truy vấn payments: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Lấy thanh toán theo ID
 * @param int $id
 * @return array|null
 */
function getPaymentById($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM payments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Tạo thanh toán mới
 * @param int $booking_id
 * @param float $so_tien
 * @param string $ngay_thanh_toan
 * @param string $phuong_thuc ('Tien mat' hoặc 'QR')
 * @param string $trang_thai ('Chưa thanh toán' hoặc 'Đã thanh toán')
 * @return bool|string true nếu thành công, lỗi nếu thất bại
 */
function createPayment($booking_id, $so_tien, $ngay_thanh_toan, $phuong_thuc = 'Tien mat', $trang_thai = 'Chưa thanh toán') {
    $conn = getDbConnection();
    $stmt = $conn->prepare("INSERT INTO payments (booking_id, so_tien, ngay_thanh_toan, phuong_thuc, trang_thai) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $booking_id, $so_tien, $ngay_thanh_toan, $phuong_thuc, $trang_thai);
    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }
}

/**
 * Cập nhật trạng thái thanh toán
 * @param int $id
 * @param string $trang_thai
 * @return bool
 */
function updatePaymentStatus($id, $trang_thai) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE payments SET trang_thai = ? WHERE id = ?");
    $stmt->bind_param("si", $trang_thai, $id);
    return $stmt->execute();
}

/**
 * Xóa thanh toán
 * @param int $id
 * @return bool
 */
function deletePayment($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM payments WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>
