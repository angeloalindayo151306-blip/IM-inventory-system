<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Prepared statement
    $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sidi", $name, $category_id, $price, $stock);

    if ($stmt->execute()) {
        header("Location: add_product.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Add Product</title>
</head>
<body>

<h2>Add Product</h2>

<?php if (isset($_GET['success'])): ?>
<p style="color: green;">Product added successfully!</p>
<?php endif; ?>

<form method="POST">
    <label>Product Name</label>
    <input type="text" name="name" required>

    <label>Category</label>
    <select name="category_id" required>
        <?php while ($row = $categories->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['category_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Price</label>
    <input type="number" step="0.01" name="price" required>

    <label>Stock</label>
    <input type="number" name="stock" required>

    <button type="submit">Add Product</button>
</form>

</body>
</html>
