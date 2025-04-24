<?php
require_once('db.php');
if (isset($_POST['order_id'])) {
    $id = intval($_POST['order_id']);
    $stmt = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>
