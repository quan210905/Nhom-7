<?php
require_once '../../functions/auth_check.php';
checkLogin();

if($_SESSION['role'] !== 'customer') {
    die("Chỉ khách hàng mới được đánh giá.");
}

require '../../functions/db_connection.php';
$conn = getDbConnection();

// Lấy danh sách bookings của user hiện tại
$user_id = $_SESSION['id'];
$sql = "SELECT b.id AS booking_id, t.ten_tour
        FROM bookings b
        JOIN tours t ON b.tour_id = t.id
        WHERE b.user_id = $user_id";
$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<form action="../../handle/reviews_process.php" method="POST">
    <input type="hidden" name="action" value="create">

    <div class="mb-3">
        <label for="booking_id" class="form-label">Chọn tour đã đặt</label>
        <select name="booking_id" id="booking_id" class="form-control" required>
            <?php
            foreach($bookings as $b){
                echo "<option value='{$b['booking_id']}'>{$b['ten_tour']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="sao" class="form-label">Số sao</label>
        <select name="sao" id="sao" class="form-control" required>
            <option value="5">5 ⭐</option>
            <option value="4">4 ⭐</option>
            <option value="3">3 ⭐</option>
            <option value="2">2 ⭐</option>
            <option value="1">1 ⭐</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="nhan_xet" class="form-label">Nhận xét</label>
        <textarea name="nhan_xet" id="nhan_xet" class="form-control" rows="4" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
</form>
