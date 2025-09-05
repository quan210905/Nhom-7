<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách đặt tour từ database
 * @return array Danh sách đặt tour
 */
function getAllLoans() {
    $conn = getDbConnection();
    $sql = "SELECT id, student_id, book_id, ngay_muon, ngay_tra, trang_thai FROM loans ORDER BY id";
    $result = mysqli_query($conn, $sql);
    $loans = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $loans[] = $row;
        }
    }
    mysqli_close($conn);
    return $loans;
}

/**
 * Thêm đặt tour mới
 * @param int $user_id
 * @param int $tour_id
 * @param int $so_nguoi
 * @param string $ngay_dat
 * @param string $trang_thai
 * @return bool True nếu thành công, False nếu thất bại
 */
function addLoan($student_id, $book_id, $ngay_muon, $ngay_tra, $trang_thai) {
    $conn = getDbConnection();
    $sql = "INSERT INTO loans (student_id, book_id, ngay_muon, ngay_tra, trang_thai) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iisss", $student_id, $book_id, $ngay_muon, $ngay_tra, $trang_thai);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Lấy thông tin một đặt tour theo ID
 * @param int $id
 * @return array|null
 */
function getLoanById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, student_id, book_id, ngay_muon, ngay_tra, trang_thai FROM loans WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $loan = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $loan;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật thông tin đặt tour
 * @param int $id
 * @param int $user_id
 * @param int $tour_id
 * @param int $so_nguoi
 * @param string $ngay_dat
 * @param string $trang_thai
 * @return bool
 */
function updateLoan($id, $student_id, $book_id, $ngay_muon, $ngay_tra, $trang_thai) {
    $conn = getDbConnection();
    $sql = "UPDATE loans SET student_id = ?, book_id = ?, ngay_muon = ?, ngay_tra = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iisssi", $student_id, $book_id, $ngay_muon, $ngay_tra, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Xóa đặt tour theo ID
 * @param int $id
 * @return bool
 */
function deleteLoan($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM loans WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}
?>