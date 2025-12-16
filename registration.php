<?php

$usersFile = "users.json";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form values
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    // -----------------------
    // 1. VALIDATION
    // -----------------------
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }
    if (empty($errors)) {

        // Read current users
        $userData = file_get_contents($usersFile);
        $users = json_decode($userData, true);

        // Create new user array
        $newUser = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        // Add user to array
        $users[] = $newUser;

        // Save back to JSON file
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

        $success = "Registration successful!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<!-- Show success message -->
<?php if (!empty($success)): ?>
    <p style="color: green;"><?= $success ?></p>
<?php endif; ?>

<!-- Show errors -->
<?php
if (!empty($errors)) {
    echo "<ul style='color: red;'>";
    foreach ($errors as $err) {
        echo "<li>$err</li>";
    }
    echo "</ul>";
}
?>

<!-- Registration Form -->
<form method="POST">
    <label>Name:</label><br>
    <input type="text" name="name"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password"><br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password"><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>
