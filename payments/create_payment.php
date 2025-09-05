<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/payment_functions.php';

checkLogin();
$currentUser = getCurrentUser();
$conn = getDbConnection();
$bookings = $conn->query("SELECT id, user_id, tour_id FROM bookings ORDER BY id DESC");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $so_tien = $_POST['so_tien'];
    $ngay_thanh_toan = $_POST['ngay_thanh_toan'];
    $phuong_thuc = $_POST['phuong_thuc'];

    $result = createPayment($booking_id, $so_tien, $ngay_thanh_toan, $phuong_thuc);
    if($result === true){
        header("Location: payments.php?success=Thêm thanh toán thành công");
        exit();
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Thêm thanh toán</h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Booking</label>
            <select name="booking_id" class="form-select" required>
                <?php while($b = $bookings->fetch_assoc()){ ?>
                    <option value="<?= $b['id'] ?>">ID: <?= $b['id'] ?> - User: <?= $b['user_id'] ?> - Tour: <?= $b['tour_id'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Số tiền</label>
            <input type="number" name="so_tien" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Ngày thanh toán</label>
            <input type="date" name="ngay_thanh_toan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phương thức</label>
            <select name="phuong_thuc" class="form-select" required>
                <option value="Tiền mặt">Tiền mặt</option>
                <option value="QR">QR</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Thêm thanh toán</button>
        <a href="payments.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>
</body>
</html>
