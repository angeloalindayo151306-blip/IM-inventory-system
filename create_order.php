<?php
include 'database.php';

// FIX CUSTOMER QUERY
$customers = $conn->query("SELECT id, full_name FROM customers");

// FIX PRODUCT QUERY
$products = $conn->query("SELECT id, product_name FROM products");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];

    // Create order
    $conn->query("INSERT INTO orders (customer_id, order_date) VALUES ($customer_id, NOW())");
    $order_id = $conn->insert_id;

    // Add item
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $product_id, $qty)");

    header("Location: view_orders.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Create Order</title>
</head>
<body>

<h2>Create Order</h2>

<form method="POST">
    <label>Customer</label>
    <select name="customer_id">
        <option value="">-- Select Customer --</option>
        <?php while ($c = $customers->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= $c['full_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Product</label>
    <select name="product_id">
        <option value="">-- Select Product --</option>
        <?php while ($p = $products->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>"><?= $p['product_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Quantity</label>
    <input type="number" name="qty" min="1" required>

    <button type="submit">Create Order</button>
</form>

</body>
</html>
