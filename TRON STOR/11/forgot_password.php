<?php
session_start();
require 'config.php';

$email = $name = $new_password = "";
$step = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = trim(htmlspecialchars($_POST['email']));
        
        $sql = "SELECT nom FROM Utilisateurs WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $name = $row['nom'];
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_name'] = $name;
            $step = 2;
        } else {
            $_SESSION['error'] = "Email not found.";
        }
    } elseif (isset($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        $update_sql = "UPDATE Utilisateurs SET mot_de_passe = ? WHERE email = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ss", $new_password, $email);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Password reset successful! You are now logged in.";
            $_SESSION['user_name'] = $_SESSION['reset_name'];
            $_SESSION['user_email'] = $_SESSION['reset_email'];
            header("Location: home.php");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Try again!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Tron Store</title>
    <link rel="stylesheet" href="home.css">
    <style>
        body {  background: url('hichem/backgroun.jpg') no-repeat center center fixed;
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
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 18px;
        }

        .navbar a:hover {
            color: #ffcc00;
        }

        .container {
            background: #222;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            text-align: center;
            width: 350px;
            margin: 100px auto;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: scale(1.05);
        }

        .input-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #333;
            color: white;
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


        .error, .message {
            margin-top: 10px;
            font-size: 16px;
        }
        .error { color: red; }
        .message { color: green; }

        .forgot-links {
            margin-top: 15px;
        }

        .forgot-links a {
            color: #ffcc00;
            text-decoration: none;
        }

        .forgot-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header class="header">
    <a href="#" class="logo">Tron Store</a>
    <nav class="navbar">
        <a href="home.php">HOME</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<div class="container">
    <form action="" method="post">
        <h1>Forgot Password</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['message'])) {
            echo "<p class='message'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        ?>

        <?php if ($step == 1): ?>
            <div class="input-box">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn">Next</button>
        
        <?php elseif ($step == 2): ?>
            <div class="input-box">
                <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['reset_name']); ?>" disabled>
            </div>
            <div class="input-box">
                <input type="password" name="new_password" placeholder="Enter new password" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        <?php endif; ?>
        
        <div class="forgot-links">
            <a href="register.php">Create an account</a>
        </div>
    </form>
</div>
</body>
</html>
