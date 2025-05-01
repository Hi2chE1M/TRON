<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tron");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
 exit();
}

$user_id = $_SESSION['user_id'];

$stmt_user = $conn->prepare("SELECT nom, email, solde FROM utilisateurs WHERE id_utilisateur = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

if (isset($_POST['submit_order'])) {
    $amount = floatval($_POST['amount']);
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    if ($amount > 0 && !empty($card_number) && !empty($expiry_date) && !empty($cvv)) {
        $stmt_order = $conn->prepare("INSERT INTO commandes (id_utilisateur, montant, statut, numero_carte, date_expiration, code_cvv) VALUES (?, ?, 'Pending', ?, ?, ?)");
        $stmt_order->bind_param("idsss", $user_id, $amount, $card_number, $expiry_date, $cvv);
        $stmt_order->execute();

        echo "<script>alert('Order submitted successfully. Waiting for admin approval.');</script>";
    } else {
        echo "<script>alert('Invalid input. Please check your details.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="home.css">
    <style>
        body {
            background: url('hichem/backgroun.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            background: #222;
            padding: 20px;
            border-radius: 8px;
            width: 40%;
            margin: auto;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        .info {
            font-size: 18px;
            margin: 10px 0;
            color: #ddd;
        }
        .balance {
            color: #ffcc00;
            font-size: 20px;
            font-weight: bold;
        }
        .btn {
            background: #ffcc00;
            color: black;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
            margin-top: 10px;
        }
        .btn:hover {
            background: #ffdb4d;
        }
        .input {
            padding: 8px;
            width: 60%;
            font-size: 16px;
            margin-top: 10px;
        }
        .submit-btn {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>👤 Profile</h2>
    <p class="info"> Name: <?php echo htmlspecialchars($user['nom']); ?></p>
    <p class="info"> Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p class="balance"> Balance: <?php echo number_format($user['solde'], 2); ?> DZD</p>

    <h3> Make a Purchase</h3>
    <form method="POST">
  <input type="number" name="amount" class="input" placeholder="Enter amount" required>
  <input type="text" name="card_number" class="input" placeholder="Card Number" required>
  <input type="text" name="expiry_date" class="input" placeholder="MM/YYYY" required>
  <input type="text" name="cvv" class="input" placeholder="CVV" required>
  <button type="submit" name="submit_order" class="submit-btn"> Submit Order</button>
    </form>

    <br>
    <a href="home.php" class="btn"> Home</a>
</div>

</body>
</html>
