<?php
require_once '../../functions/db_connection.php';

$id = $_GET['id'] ?? '';
$author = null;
if ($id !== '') {
    $stmt = $conn->prepare("SELECT * FROM authors WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $author = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_tac_gia = $_POST['ten_tac_gia'] ?? '';
    if ($id !== '' && $ten_tac_gia !== '') {
        $stmt = $conn->prepare("UPDATE authors SET ten_tac_gia = ? WHERE id = ?");
        $stmt->bind_param('si', $ten_tac_gia, $id);
        $stmt->execute();
        header('Location: ../authors.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa tác giả</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>
<div class="container mt-4">
    <h2>Sửa tác giả</h2>
    <form method="post">
        <div class="mb-3">
            <label for="ten_tac_gia" class="form-label">Tên tác giả</label>
            <input type="text" class="form-control" id="ten_tac_gia" name="ten_tac_gia" value="<?= htmlspecialchars($author['ten_tac_gia'] ?? '') ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="../authors.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
