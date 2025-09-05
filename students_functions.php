<?php
require_once 'db_connection.php';

function getAllStudents() {
    $conn = getDbConnection();
    $sql = "SELECT id, ho_ten, email, sdt FROM students ORDER BY id";
    $result = mysqli_query($conn, $sql);
    $students = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    }
    mysqli_close($conn);
    return $students;
}

function addStudent($ho_ten, $email, $sdt) {
    $conn = getDbConnection();
    $sql = "INSERT INTO students (ho_ten, email, sdt) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $ho_ten, $email, $sdt);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getStudentById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, ho_ten, email, sdt FROM students WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $student = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $student;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

function updateStudent($id, $ho_ten, $email, $sdt) {
    $conn = getDbConnection();
    $sql = "UPDATE students SET ho_ten = ?, email = ?, sdt = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $ho_ten, $email, $sdt, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteStudent($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM students WHERE id = ?";
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