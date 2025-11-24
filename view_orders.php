<?php
include 'database.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<h1>Orders</h1>

<?php
$sql = "SELECT 
            o.id, 
            o.order_date,
            CONCAT(c.firstname, ' ', c.lastname) AS customer_name
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.id
        ORDER BY o.id DESC";

$res = $conn->query($sql);

if (!$res) {
    echo "<p style='color:red;'>SQL ERROR: " . $conn->error . "</p>";
    echo "<p>Query: $sql</p>";
    exit;
}
?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

<?php while ($row = $res->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= $row['order_date'] ?></td>
        <td>
            <a href="view_order.php?id=<?= $row['id'] ?>">View</a>
        </td>
    </tr>
<?php endwhile; ?>

    </tbody>
</table>

</div>
</body>
</html>
