<?php
// Quản lý tác giả - authors.php
require_once '../functions/db_connection.php';

function getAllAuthors() {
    global $conn;
    $sql = "SELECT * FROM authors";
    $result = $conn->query($sql);
    $authors = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
    }
    return $authors;
}

$authors = getAllAuthors();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tác giả</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<?php include 'menu.php'; ?>
<div class="container mt-4">
    <h2>Quản lý tác giả</h2>
    <a href="authors/create_author.php" class="btn btn-primary mb-3">Thêm tác giả</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên tác giả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($authors as $author): ?>
                <tr>
                    <td><?= htmlspecialchars($author['id']) ?></td>
                    <td><?= htmlspecialchars($author['ten_tac_gia']) ?></td>
                    <td>
                        <a href="authors/edit_author.php?id=<?= $author['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="../handle/authors_process.php?action=delete&id=<?= $author['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
