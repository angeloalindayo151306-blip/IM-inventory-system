<?php
include 'database.php';

// Add Category
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $conn->query("INSERT INTO categories (category_name) VALUES ('$name')");
    header("Location: manage_categories.php");
    exit;
}

// Delete Category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: manage_categories.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Manage Categories</title>
</head>
<body>

<h2>Manage Categories</h2>

<form method="POST">
    <input type="text" name="name" placeholder="Category Name" required>
    <button type="submit" name="add">Add</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Action</th>
</tr>

<?php while ($row = $categories->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['category_name'] ?></td>
    <td><a href="manage_categories.php?delete=<?= $row['id'] ?>">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
