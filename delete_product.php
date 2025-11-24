<?php
// delete_product.php
include 'database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    // If order_items has FK with RESTRICT, this may fail — adjust as needed.
    // Optionally delete order_items referencing this product first:
    // $stmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
    // $stmt->bind_param('i', $id); $stmt->execute(); $stmt->close();

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: index.php');
exit;
?>