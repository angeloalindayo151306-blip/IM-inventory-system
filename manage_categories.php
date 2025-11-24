<?php
include 'database.php';

// Add Category (Prepared Statement)
if (isset($_POST['add'])) {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();

    header("Location: manage_categories.php?success=1");
    exit;
}

// Delete Category (Prepared Statement)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_categories.php?deleted=1");
    exit;
}

$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Manage Categories</title>
</head>
<body>

<h2>Manage Categories</h2>

<?php if (isset($_GET['success'])): ?>
<p style="color: green;">Category added successfully!</p>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
<p style="color: red;">Category deleted!</p>
<?php endif; ?>

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
    <td><?= $row['name'] ?></td>
    <td><a href="manage_categories.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this category?');">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
