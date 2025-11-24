<?php
include 'database.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Products - Inventory</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

  <img src="/mnt/data/2de2a0a5-9409-48f2-8c21-e0387c4aff36.png" alt="logo" class="logo">

  <h1>Products</h1>
  <div class="actions">
    <a class="button" href="add_product.php">Add Product</a>
    <a class="button" href="manage_categories.php">Manage Categories</a>
    <a class="button" href="add_customer.php">Add Customer</a>
    <a class="button" href="create_order.php">Create Order</a>
    <a class="button" href="view_orders.php">View Orders</a>
  </div>

<?php
$sql = "SELECT p.id, p.product_name, p.price, p.stock, c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.id DESC";

$res = $conn->query($sql); // FIXED
?>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Product</th>
      <th>Category</th>
      <th>Price</th>
      <th>Stock</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
<?php while ($row = $res->fetch_assoc()): ?>
  <tr>
    <td><?= htmlspecialchars($row['id']) ?></td>
    <td><?= htmlspecialchars($row['product_name']) ?></td>
    <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
    <td><?= number_format($row['price'],2) ?></td>
    <td><?= htmlspecialchars($row['stock']) ?></td>
    <td>
      <a href="edit_product.php?id=<?= urlencode($row['id']) ?>">Edit</a> |
      <a href="delete_product.php?id=<?= urlencode($row['id']) ?>" onclick="return confirm('Delete this product?')">Delete</a>
    </td>
  </tr>
<?php endwhile; ?>
  </tbody>
</table>

</div>
</body>
</html>
