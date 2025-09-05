<?php

require_once __DIR__ . '/../functions/students_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
    handleCreateStudent();
        break;
    case 'edit':
    handleEditStudent();
        break;
    case 'delete':
    handleDeleteStudent();
        break;
}


/**
 * Lấy tất cả danh sách thành viên thư viện (members)
 */
function handleGetAllMembers() {
    $conn = getDbConnection();
    $sql = "SELECT m.*, d.dept_name FROM members m LEFT JOIN departments d ON m.dept_id = d.id ORDER BY m.id";
    $result = mysqli_query($conn, $sql);
    $members = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
    }
    mysqli_close($conn);
    return $members;
}

function handleGetStudentById($id) {
    return getStudentById($id);
}

/**
 * Xử lý tạo sinh viên mới
 */
function handleCreateStudent() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/students.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['ho_ten']) ||
        !isset($_POST['email']) ||
        !isset($_POST['sdt'])
    ) {
        header("Location: ../views/students/create_student.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);

    if (empty($ho_ten) || empty($email) || empty($sdt)) {
        header("Location: ../views/students/create_student.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = addStudent($ho_ten, $email, $sdt);

    if ($result) {
        header("Location: ../views/students.php?success=Thêm sinh viên thành công");
    } else {
        header("Location: ../views/students/create_student.php?error=Có lỗi xảy ra khi thêm sinh viên");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa sinh viên
 */
function handleEditStudent() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/students.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['id']) ||
        !isset($_POST['ho_ten']) ||
        !isset($_POST['email']) ||
        !isset($_POST['sdt'])
    ) {
        header("Location: ../views/students.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $id = $_POST['id'];
    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);

    if (empty($ho_ten) || empty($email) || empty($sdt)) {
        header("Location: ../views/students/edit_student.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = updateStudent($id, $ho_ten, $email, $sdt);

    if ($result) {
        header("Location: ../views/students.php?success=Cập nhật sinh viên thành công");
    } else {
        header("Location: ../views/students/edit_student.php?id=" . $id . "&error=Cập nhật sinh viên thất bại");
    }
    exit();
}

/**
 * Xử lý xóa sinh viên
 */
function handleDeleteStudent() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/students.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/students.php?error=Không tìm thấy ID sinh viên");
        exit();
    }
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/students.php?error=ID sinh viên không hợp lệ");
        exit();
    }
    $result = deleteStudent($id);

    if ($result) {
        header("Location: ../views/students.php?success=Xóa sinh viên thành công");
    } else {
        header("Location: ../views/students.php?error=Xóa sinh viên thất bại");
    }
    exit();
}
?>