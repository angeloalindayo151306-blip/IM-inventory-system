<?php
// edit_product.php
include 'database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    header('Location: index.php');
    exit;
}

// handle POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $category_id = ($_POST['category_id'] === '') ? null : intval($_POST['category_id']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $stmt = $conn->prepare("UPDATE products SET category_id = ?, product_name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
    $stmt->bind_param('issdii', $category_id, $product_name, $description, $price, $stock, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php');
    exit;
}

// fetch product
$stmt = $conn->prepare("SELECT id, category_id, product_name, description, price, stock FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "Product not found.";
    exit;
}

// categories for dropdown
$cats = $conn->query("SELECT id, category_name FROM categories ORDER BY category_name");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Product</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Edit Product</h1>
  <form method="post">
    <div class="form-row">
      <label>Product Name</label>
      <input type="text" name="product_name" required value="<?= htmlspecialchars($product['product_name']) ?>">
    </div>

    <div class="form-row">
      <label>Category</label>
      <select name="category_id">
        <option value="">-- Select category --</option>
        <?php while ($c = $cats->fetch_assoc()): ?>
          <option value="<?= $c['id'] ?>" <?= ($product['category_id'] == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['category_name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="form-row">
      <label>Description</label>
      <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <div class="form-row">
      <label>Price</label>
      <input type="number" name="price" step="0.01" required value="<?= htmlspecialchars($product['price']) ?>">
    </div>

    <div class="form-row">
      <label>Stock</label>
      <input type="number" name="stock" required value="<?= htmlspecialchars($product['stock']) ?>">
    </div>

    <input type="submit" value="Save Changes" class="btn">
    <a href="index.php" class="btn">Back</a>
  </form>
</div>
</body>
</html>
