<?php
include 'database.php';

// Load products
$products = $conn->query("SELECT * FROM products ORDER BY product_name ASC");

// Load customers
$customers = $conn->query("SELECT * FROM customers ORDER BY firstname ASC, lastname ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];

    // 1. CREATE ORDER (Prepared Statement)
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, order_date) VALUES (?, NOW())");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // 2. INSERT ORDER ITEM
    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt2->bind_param("iii", $order_id, $product_id, $qty);
    $stmt2->execute();

    header("Location: view_orders.php?created=1");
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
    <select name="customer_id" required>
        <option value="">-- Select Customer --</option>
        <?php while ($c = $customers->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>">
                <?= $c['firstname'] . " " . $c['lastname'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Product</label>
    <select name="product_id" required>
        <option value="">-- Select Product --</option>
        <?php while ($p = $products->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>">
                <?= $p['product_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Quantity</label>
    <input type="number" name="qty" min="1" required>

    <button type="submit">Create Order</button>
</form>

</body>
</html>
