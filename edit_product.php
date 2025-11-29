<?php
include 'database.php';

// 1. Validate and get ID
if (!isset($_GET['id'])) {
    header("Location: view_products.php");
    exit;
}

$id = (int)$_GET['id'];
if ($id <= 0) {
    header("Location: view_products.php");
    exit;
}

// 2. If form submitted: update product
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name        = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price       = (float)($_POST['price'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);

    if ($name !== '' && $category_id > 0) {
        $stmt = $conn->prepare(
            "UPDATE products
             SET product_name = ?, category_id = ?, price = ?, stock = ?
             WHERE id = ?"
        );
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sidii", $name, $category_id, $price, $stock, $id);
        $stmt->execute();

        header("Location: view_products.php");
        exit;
    }
}

// 3. Load product data
$stmt = $conn->prepare(
    "SELECT id, product_name, category_id, price, stock
     FROM products
     WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

// 4. Load categories for dropdown
$categories = $conn->query("SELECT id, category_name FROM categories ORDER BY category_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="add-product-page">
<div class="page-wrapper">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Edit Product</h1>
            <a href="view_products.php" class="back-link">← Back to Products</a>
        </div>

        <form method="POST" class="form-vertical">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($product['product_name']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="" disabled>Select a category</option>
                    <?php while ($row = $categories->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>"
                            <?= $row['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['category_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input
                        type="number"
                        step="0.01"
                        id="price"
                        name="price"
                        value="<?= htmlspecialchars($product['price']) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="<?= htmlspecialchars($product['stock']) ?>"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </div>
</div>
</body>
</html>