<?php
// view_order.php
include 'database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    header('Location: view_orders.php');
    exit;
}

// fetch order and customer
$stmt = $conn->prepare("SELECT o.id, o.order_date, c.firstname, c.lastname, c.email
                        FROM orders o
                        LEFT JOIN customers c ON o.customer_id = c.id
                        WHERE o.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$orderRes = $stmt->get_result();
$order = $orderRes->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "Order not found.";
    exit;
}

// fetch items (with price taken from products table)
$itstmt = $conn->prepare("
    SELECT 
        oi.quantity, 
        p.product_name, 
        p.price 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$itstmt->bind_param('i', $id);
$itstmt->execute();
$items = $itstmt->get_result();
$itstmt->close();

// compute total
$total = 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Order #<?= htmlspecialchars($order['id']) ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Order #<?= htmlspecialchars($order['id']) ?></h1>
  <p><strong>Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
  <p><strong>Customer:</strong> <?= htmlspecialchars($order['firstname'] . " " . $order['lastname']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>

  <h3>Items</h3>
  <table class="styled-table">
    <thead>
        <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
    </thead>
    <tbody>
      <?php while ($it = $items->fetch_assoc()): 
            $subtotal = $it['price'] * $it['quantity'];
            $total += $subtotal;
      ?>
      <tr>
        <td><?= htmlspecialchars($it['product_name']) ?></td>
        <td><?= intval($it['quantity']) ?></td>
        <td><?= number_format($it['price'], 2) ?></td>
        <td><?= number_format($subtotal, 2) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <p><strong>Total:</strong> <?= number_format($total, 2) ?></p>

  <a href="view_orders.php" class="btn">Back to Orders</a>
  <a href="index.php" class="btn">Back to Products</a>
</div>
</body>
</html>