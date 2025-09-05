<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách tour từ database
 * @return array Danh sách tour
 */
function getAllBooks() {
    $conn = getDbConnection();
    $sql = "SELECT b.*, GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
            FROM books b
            LEFT JOIN book_authors ba ON b.id = ba.book_id
            LEFT JOIN authors a ON ba.author_id = a.id
            GROUP BY b.id
            ORDER BY b.id";
    $result = mysqli_query($conn, $sql);
    $books = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
    }
    mysqli_close($conn);
    return $books;
}

/**
 * Thêm tour mới
 * @param string $ten_tour
 * @param float $gia
 * @param string $ngay_khoi_hanh
 * @param int $so_cho
 * @param string $mo_ta
 * @return bool True nếu thành công, False nếu thất bại
 */
function addBook($title, $publisher_id, $publication_year, $category_id, $description, $author_ids = []) {
    $conn = getDbConnection();
    $sql = "INSERT INTO books (title, publisher_id, publication_year, category_id, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "siiis", $title, $publisher_id, $publication_year, $category_id, $description);
        $success = mysqli_stmt_execute($stmt);
        $book_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        // Thêm tác giả vào book_authors
        if ($success && !empty($author_ids)) {
            $stmt2 = mysqli_prepare($conn, "INSERT INTO book_authors (book_id, author_id) VALUES (?, ?)");
            foreach ($author_ids as $author_id) {
                mysqli_stmt_bind_param($stmt2, "ii", $book_id, $author_id);
                mysqli_stmt_execute($stmt2);
            }
            mysqli_stmt_close($stmt2);
        }
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Lấy thông tin một tour theo ID
 * @param int $id
 * @return array|null
 */
function getBookById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM books WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    $book = null;
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $book = mysqli_fetch_assoc($result);
            // Lấy danh sách tác giả
            $sql2 = "SELECT author_id FROM book_authors WHERE book_id = ?";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $id);
            mysqli_stmt_execute($stmt2);
            $result2 = mysqli_stmt_get_result($stmt2);
            $author_ids = [];
            while ($row = mysqli_fetch_assoc($result2)) {
                $author_ids[] = $row['author_id'];
            }
            $book['author_ids'] = $author_ids;
            mysqli_stmt_close($stmt2);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return $book;
}

/**
 * Cập nhật thông tin tour
 * @param int $id
 * @param string $ten_tour
 * @param float $gia
 * @param string $ngay_khoi_hanh
 * @param int $so_cho
 * @param string $mo_ta
 * @return bool
 */
function updateBook($id, $title, $publisher_id, $publication_year, $category_id, $description, $author_ids = []) {
    $conn = getDbConnection();
    $sql = "UPDATE books SET title = ?, publisher_id = ?, publication_year = ?, category_id = ?, description = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "siiisi", $title, $publisher_id, $publication_year, $category_id, $description, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        // Cập nhật lại tác giả
        $sql_del = "DELETE FROM book_authors WHERE book_id = ?";
        $stmt_del = mysqli_prepare($conn, $sql_del);
        mysqli_stmt_bind_param($stmt_del, "i", $id);
        mysqli_stmt_execute($stmt_del);
        mysqli_stmt_close($stmt_del);
        if (!empty($author_ids)) {
            $stmt2 = mysqli_prepare($conn, "INSERT INTO book_authors (book_id, author_id) VALUES (?, ?)");
            foreach ($author_ids as $author_id) {
                mysqli_stmt_bind_param($stmt2, "ii", $id, $author_id);
                mysqli_stmt_execute($stmt2);
            }
            mysqli_stmt_close($stmt2);
        }
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Xóa tour theo ID
 * @param int $id
 * @return bool
 */
function deleteBook($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM books WHERE id = ?";
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