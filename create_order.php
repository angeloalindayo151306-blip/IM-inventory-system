<?php
include 'database.php';

$customers = $conn->query("SELECT id, full_name FROM customers ORDER BY full_name");
$products  = $conn->query("SELECT id, product_name FROM products ORDER BY product_name");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id = (int)($_POST['customer_id'] ?? 0);
    $product_ids = $_POST['product_id'] ?? [];
    $quantities  = $_POST['quantity'] ?? [];

    if ($customer_id > 0 && is_array($product_ids) && is_array($quantities)) {
        $items = [];

        // Collect valid product/quantity pairs
        for ($i = 0; $i < count($product_ids); $i++) {
            $pid = (int)($product_ids[$i] ?? 0);
            $qty = (int)($quantities[$i] ?? 0);

            if ($pid > 0 && $qty > 0) {
                $items[] = [
                    'product_id' => $pid,
                    'quantity'   => $qty
                ];
            }
        }

        // Only create order if there is at least one valid item
        if (count($items) > 0) {
            // Create order with 0 total_amount for now
            $stmt = $conn->prepare(
                "INSERT INTO orders (customer_id, total_amount) VALUES (?, 0)"
            );
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            // Insert all order items
            $stmt2 = $conn->prepare(
                "INSERT INTO order_items (order_id, product_id, quantity)
                 VALUES (?, ?, ?)"
            );
            $pid = 0;
            $qty = 0;
            $stmt2->bind_param("iii", $order_id, $pid, $qty);

            foreach ($items as $item) {
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                $stmt2->execute();
            }

            // Redirect back with success message and order id
            header("Location: create_order.php?success=1&order_id=" . $order_id);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="orders-create-page">
<div class="page-wrapper">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Create Order</h1>
            <a href="home.php" class="back-link">← Back to Dashboard</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <p class="success-message">
                Order created successfully!
                <?php if (!empty($_GET['order_id'])): ?>
                    (ID: <?= htmlspecialchars($_GET['order_id']) ?>)
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <a href="view_orders.php" class="btn-primary" style="margin-bottom:14px; display:inline-flex;">
            View Orders
        </a>

        <form method="POST" class="form-vertical">
            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="" disabled selected>Select Customer</option>
                    <?php while ($c = $customers->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($c['id']) ?>">
                            <?= htmlspecialchars($c['full_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Products</label>

                <div id="product-rows">
                    <!-- Initial product row (will be cloned by JS) -->
                    <div class="product-row" style="display:flex; gap:10px; margin-bottom:8px;">
                        <select name="product_id[]" required>
                            <option value="" disabled selected>Select Product</option>
                            <?php while ($p = $products->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($p['id']) ?>">
                                    <?= htmlspecialchars($p['product_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <input name="quantity[]" type="number" min="1" value="1" required
                               placeholder="Quantity">

                        <button type="button" class="btn-secondary remove-product-row">
                            Remove
                        </button>
                    </div>
                </div>

                <button type="button" id="add-product-row" class="btn-secondary" style="margin-top:8px;">
                    + Add Another Product
                </button>
            </div>

            <button type="submit" class="btn-primary">Create Order</button>
        </form>
    </div>
</div>

<!-- JavaScript to handle adding/removing product rows -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productRowsContainer = document.getElementById('product-rows');
    const addBtn = document.getElementById('add-product-row');

    // Keep a template of the first row to clone later
    const template = productRowsContainer.querySelector('.product-row');

    addBtn.addEventListener('click', function () {
        const newRow = template.cloneNode(true);

        const select = newRow.querySelector('select[name="product_id[]"]');
        const qty    = newRow.querySelector('input[name="quantity[]"]');

        // Reset row values
        select.selectedIndex = 0;
        qty.value = 1;

        productRowsContainer.appendChild(newRow);
    });

    // Handle remove row clicks (using event delegation)
    productRowsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product-row')) {
            const rows = productRowsContainer.querySelectorAll('.product-row');
            // Keep at least one row
            if (rows.length > 1) {
                e.target.closest('.product-row').remove();
            }
        }
    });
});
</script>
</body>
</html>