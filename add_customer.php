<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = $_POST['full_name']; 
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Prepared statement
    $stmt = $conn->prepare("INSERT INTO customers (full_name, email, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $phone, $address);

    if ($stmt->execute()) {
        header("Location: add_customer.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Add Customer</title>
</head>
<body>

<h2>Add Customer</h2>

<?php if (isset($_GET['success'])): ?>
<p style="color: green;">Customer added successfully!</p>
<?php endif; ?>

<form method="POST">

    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Phone</label>
    <input type="text" name="phone">

    <label>Address</label>
    <textarea name="address"></textarea>

    <button type="submit">Add Customer</button>

</form>

</body>
</html>
