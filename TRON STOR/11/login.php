<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);

    $sql = "SELECT id_utilisateur, nom, mot_de_passe, role FROM Utilisateurs WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_password, $role);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = $role;
                
                if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: home.php");
        exit();
    }
}

            } else {
                $_SESSION['error'] = "Invalid email or password.";
            }
        } else {
            $_SESSION['error'] = "Invalid email or password.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tron Store</title>
    <link rel="stylesheet" href="home.css">
    <style>
       body {
           background: url('hichem/backgroun.jpg') no-repeat center center fixed;
           background-size: cover;
           background-color: #1a1a1a;
           color: white;
           font-family: Arial, sans-serif;
           margin: 0;
           padding: 0;
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



       .logo {
           font-size: 24px;
           font-weight: bold;
           color: #ffcc00;
           text-decoration: none;
       }

       .navbar a {
           color: white;
           text-decoration: none;
           margin: 0 15px;
           font-size: 16px;
       }

       .navbar a:hover {
           color: #ffcc00;
       }

       .form-container {
           background: rgba(34, 34, 34, 0.9);
           padding: 30px;
           border-radius: 10px;
           box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
           backdrop-filter: blur(10px);
           text-align: center;
           max-width: 400px;
           width: 100%;
           margin: auto;
           margin-top: 100px;
       }

       .input-box {
           width: 100%;
           margin-bottom: 15px;
           transition: transform 0.2s ease-in-out;
       }

       .input-box input {
           width: 100%;
           padding: 16px;
           border-radius: 5px;
           border: none;
           background: #444;
           color: white;
           font-size: 16px;
           transition: transform 0.2s ease-in-out;
       }

       .input-box input:focus {
           transform: scale(1.05);
           outline: none;
           box-shadow: 0 0 10px rgba(255, 204, 0, 0.5);
       }

       .btn {
           background: #ffcc00;
           color: #222;
           padding: 18px 28px;
           border-radius: 5px;
           font-size: 18px;
           transition: transform 0.2s, background 0.3s;
           text-align: center;
           width: 100%;
           border: none;
           cursor: pointer;
       }

       .btn:hover {
           background: #e6b800;
           transform: scale(1.05);
       }

       .error, .success {
           color: white;
           margin-top: 10px;
       }

       .forgot-password {
           margin-top: 10px;
       }

       .forgot-password a {
           color: #ffcc00;
           text-decoration: none;
       }

       .forgot-password a:hover {
           text-decoration: underline;
       }
    </style>
</head>
<body>
    
    <header class="header">
        <a href="#" class="logo">Tron Store</a>
        <nav class="navbar">
            <a href="home.php" class="homeh">HOME</a>
            <a href="register.php">Register</a>
            <a href="https://wa.me/213669974828" target="_blank">Support</a>
        </nav>
    </header>
    
    <div class="form-container">
        <h2 class="logo">Login</h2>
        <form action="login.php" method="POST">
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
            
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </p>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <p class="success"> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?> </p>
            <?php endif; ?>
            
            <p class="forgot-password"><a href="forgot_password.php">Forgot your password?</a></p>
        </form>
    </div>
</body>
</html>
