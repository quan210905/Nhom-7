<?php
require_once '../../functions/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_tac_gia = $_POST['ten_tac_gia'] ?? '';
    if ($ten_tac_gia !== '') {
        $stmt = $conn->prepare("INSERT INTO authors (ten_tac_gia) VALUES (?)");
        $stmt->bind_param('s', $ten_tac_gia);
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
    <title>Thêm tác giả</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>
<div class="container mt-4">
    <h2>Thêm tác giả</h2>
    <form method="post">
        <div class="mb-3">
            <label for="ten_tac_gia" class="form-label">Tên tác giả</label>
            <input type="text" class="form-control" id="ten_tac_gia" name="ten_tac_gia" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
        <a href="../authors.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
