<?php
include 'database.php';

/*
 * View all orders with:
 * - Order ID
 * - Customer name
 * - Items: "Product A (x2), Product B (x1)"
 * - Total Amount: SUM(quantity * product price)
 */

$sql = "
    SELECT
        o.id,
        c.full_name,
        -- Calculate total amount from items
        SUM(oi.quantity * p.price) AS total_amount,
        -- List of items per order
        GROUP_CONCAT(
            CONCAT(p.product_name, ' (x', oi.quantity, ')')
            ORDER BY p.product_name SEPARATOR ', '
        ) AS items
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p ON p.id = oi.product_id
    GROUP BY o.id, c.full_name
    ORDER BY o.id DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="orders-page">
<div class="page-wrapper">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Orders</h1>
            <a href="home.php" class="back-link">← Back to Dashboard</a>
        </div>

        <a href="create_order.php" class="btn-primary" style="margin-bottom:14px; display:inline-flex;">
            + Create Order
        </a>

        <?php if (isset($_GET['success'])): ?>
            <p class="success-message">
                <?= htmlspecialchars($_GET['success']) ?>
            </p>
        <?php endif; ?>

        <table class="orders-table">
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['items']) ?></td>
                        <td><?= number_format((float)$row['total_amount'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr class="empty-row">
                    <td colspan="4">No orders found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
