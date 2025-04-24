<?php
require_once('db.php');
if (isset($_POST['order_id'], $_POST['status'])) {
    $id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}
?>
