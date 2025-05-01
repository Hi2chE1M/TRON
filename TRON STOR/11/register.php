<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);
    $admin_code = trim($_POST['admin_code']);

    $check_sql = "SELECT id_utilisateur FROM Utilisateurs WHERE email = ?";
    if ($stmt = $conn->prepare($check_sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
      $_SESSION['error'] = "This email is already registered.";
      header("Location: register.php");
      exit();
        }
        $stmt->close();
    }

    $role = ($admin_code === "1111") ? "admin" : "user";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful. You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "An error occurred. Please try again.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Database error.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tron Store</title>
    <link rel="stylesheet" href="home.css">
<link rel="stylesheet" href="register.css">
    <style>
       body {  background: url('hichem/backgroun.jpg') no-repeat center center fixed;
background-size: cover;
}
.header {
    background-color: #111;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed; 
    top: 0; 
    left: 0;
    width: 100%; 
    z-index: 1000; 
}
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">Tron Store</a>
        <nav class="navbar">
            <a href="home.php" class="homeh">HOME</a>
            <a href="login.php">Login</a>
            <a href="https://wa.me/213669974828" target="_blank">Support</a>
        </nav>
    </header>
    
    <div class="form-container">
        <h2 class="logo">Register</h2>
        <form action="register.php" method="POST">
            <div class="input-box">
                <input type="text" name="name" placeholder="Name" required>
            </div>
            
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <div class="input-box">
                <input type="text" name="admin_code" placeholder="Admin Code (Optional)">
            </div>
            
            <button type="submit" class="btn">Register</button>
            
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </p>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <p class="success"> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?> </p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>