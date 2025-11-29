<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name        = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price       = (float)($_POST['price'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);

    if ($name !== '' && $category_id > 0) {
        $stmt = $conn->prepare(
            "INSERT INTO products (product_name, category_id, price, stock)
             VALUES (?, ?, ?, ?)"
        );
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sidi", $name, $category_id, $price, $stock);
        $stmt->execute();

        header("Location: add_product.php?success=1");
        exit;
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY category_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="add-product-page">
<div class="page-wrapper">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Add Product</h1>
            <a href="home.php" class="back-link">← Back to Dashboard</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <p class="success-message">Product added successfully!</p>
        <?php endif; ?>

        <!-- always show View Products button -->
        <div style="margin-bottom:14px;">
            <!-- change index.php to your actual products list page if different -->
            <a href="view_products.php" class="btn-primary">
                View Products
            </a>
        </div>

        <form method="POST" class="form-vertical">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="" disabled selected>Select a category</option>
                    <?php while ($row = $categories->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>">
                            <?= htmlspecialchars($row['category_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">Add Product</button>
        </form>
    </div>
</div>
</body>
</html>