<?php
require_once __DIR__ . '/../functions/books_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateBook();
        break;
    case 'edit':
        handleEditBook();
        break;
    case 'delete':
        handleDeleteBook();
        break;
}

/**
 * Lấy tất cả danh sách sách
 */
function handleGetAllBooks() {
    return getAllBooks();
}

function handleGetBookById($id) {
    return getBookById($id);
}

/**
 * Xử lý tạo sách mới
 */
function handleCreateBook() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/books.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['ten_sach']) ||
        !isset($_POST['tac_gia']) ||
        !isset($_POST['nam_xuat_ban']) ||
        !isset($_POST['so_luong']) ||
        !isset($_POST['mo_ta'])
    ) {
        header("Location: ../views/books/create_book.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $ten_sach = trim($_POST['ten_sach']);
    $tac_gia = trim($_POST['tac_gia']);
    $nam_xuat_ban = trim($_POST['nam_xuat_ban']);
    $so_luong = trim($_POST['so_luong']);
    $mo_ta = trim($_POST['mo_ta']);
    if (empty($ten_sach) || empty($tac_gia) || empty($nam_xuat_ban) || empty($so_luong) || empty($mo_ta)) {
        header("Location: ../views/books/create_book.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }
    if (!is_numeric($so_luong) || $so_luong < 1) {
        header("Location: ../views/books/create_book.php?error=Số lượng phải là số nguyên dương");
        exit();
    }
    $result = addBook($ten_sach, $tac_gia, $nam_xuat_ban, $so_luong, $mo_ta);
    if ($result) {
        header("Location: ../views/books.php?success=Thêm sách thành công");
    } else {
        header("Location: ../views/books/create_book.php?error=Có lỗi xảy ra khi thêm sách");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa sách
 */
function handleEditBook() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/books.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['id']) ||
        !isset($_POST['ten_sach']) ||
        !isset($_POST['tac_gia']) ||
        !isset($_POST['nam_xuat_ban']) ||
        !isset($_POST['so_luong']) ||
        !isset($_POST['mo_ta'])
    ) {
        header("Location: ../views/books.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $id = $_POST['id'];
    $ten_sach = trim($_POST['ten_sach']);
    $tac_gia = trim($_POST['tac_gia']);
    $nam_xuat_ban = trim($_POST['nam_xuat_ban']);
    $so_luong = trim($_POST['so_luong']);
    $mo_ta = trim($_POST['mo_ta']);
    if (empty($ten_sach) || empty($tac_gia) || empty($nam_xuat_ban) || empty($so_luong) || empty($mo_ta)) {
        header("Location: ../views/books/edit_book.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }
    if (!is_numeric($so_luong) || $so_luong < 1) {
        header("Location: ../views/books/edit_book.php?id=" . $id . "&error=Số lượng phải là số nguyên dương");
        exit();
    }
    $result = updateBook($id, $ten_sach, $tac_gia, $nam_xuat_ban, $so_luong, $mo_ta);
    if ($result) {
        header("Location: ../views/books.php?success=Cập nhật sách thành công");
    } else {
        header("Location: ../views/books/edit_book.php?id=" . $id . "&error=Cập nhật sách thất bại");
    }
    exit();
}

/**
 * Xử lý xóa sách
 */
function handleDeleteBook() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/books.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/books.php?error=Không tìm thấy ID sách");
        exit();
    }
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/books.php?error=ID sách không hợp lệ");
        exit();
    }
    $result = deleteBook($id);
    if ($result) {
        header("Location: ../views/books.php?success=Xóa sách thành công");
    } else {
        header("Location: ../views/books.php?error=Xóa sách thất bại");
    }
    exit();
}
?>