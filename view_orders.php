<?php
include "database.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
</head>
<body>

<h2>Orders</h2>

<?php
$query = "
    SELECT 
        o.id, 
        o.order_date, 
        c.full_name AS customer_name
    FROM orders o
    LEFT JOIN customers c ON o.customer_id = c.id
    ORDER BY o.id DESC
";

$result = $conn->query($query);

if (!$result) {
    echo "<p style='color:red;'>SQL ERROR: " . $conn->error . "</p>";
    echo "<p><strong>Query:</strong> $query</p>";
    exit;
}
?>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Order Date</th>
        <th>Customer</th>
    </tr>

<?php
while ($row = $result->fetch_assoc()):
?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['order_date'] ?></td>
        <td><?= $row['customer_name'] ?></td>
    </tr>
<?php endwhile; ?>

</table>

</body>
</html>
