<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Prepared statement
    $stmt = $conn->prepare("INSERT INTO customers (firstname, lastname, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $phone);
    $stmt->execute();

    header("Location: add_customer.php?success=1");
    exit;
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

    <label>First Name</label>
    <input type="text" name="firstname" required>

    <label>Last Name</label>
    <input type="text" name="lastname" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Phone</label>
    <input type="text" name="phone" required>

    <button type="submit">Add Customer</button>
</form>

</body>
</html>
