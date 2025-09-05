<?php
require_once '../functions/db_connection.php';
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($action === 'delete' && $id !== '') {
    $stmt = $conn->prepare("DELETE FROM authors WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: ../views/authors.php');
exit;
