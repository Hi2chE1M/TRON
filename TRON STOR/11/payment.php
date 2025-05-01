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

  $stmt_user = $conn->prepare("SELECT solde FROM utilisateurs WHERE id_utilisateur = ?");
  $stmt_user->bind_param("i", $user_id);
  $stmt_user->execute();
  $result_user = $stmt_user->get_result();
  $user = $result_user->fetch_assoc();
  $solde = $user['solde'];

      if (!isset($_GET['id'])) {
      echo "Invalid request.";
      exit();
      }

$game_id = intval($_GET['id']);

$stmt_game = $conn->prepare("SELECT titre, prix FROM Jeux WHERE id_jeu = ?");
$stmt_game->bind_param("i", $game_id);
$stmt_game->execute();
$result_game = $stmt_game->get_result();

if ($result_game->num_rows == 0) {
    echo "Game not found.";
    exit();
}

$game = $result_game->fetch_assoc();
$titre = $game['titre'];
$prix = $game['prix'];

if (isset($_POST['buy'])) {
    $adresse_livraison = trim($_POST['adresse_livraison']);
    $telephone = trim($_POST['telephone']);

    if ($solde >= $prix) {
        $new_balance = $solde - $prix;
        $update_balance = $conn->prepare("UPDATE utilisateurs SET solde = ? WHERE id_utilisateur = ?");
        $update_balance->bind_param("di", $new_balance, $user_id);
        $update_balance->execute();

        $insert_purchase = $conn->prepare("INSERT INTO achats (id_utilisateur, id_jeu, date_achat, montant, adresse_livraison, telephone) VALUES (?, ?, NOW(), ?, ?, ?)");
        if (!$insert_purchase) {
            die("Query preparation failed: " . $conn->error);
        }
        $insert_purchase->bind_param("iisss", $user_id, $game_id, $prix, $adresse_livraison, $telephone);
        $insert_purchase->execute();

        echo "<script>alert('✅ Purchase successful!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('❌ Insufficient balance! Please recharge.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy <?php echo htmlspecialchars($titre); ?> - Tron Store</title>
    <link rel="stylesheet" href="home.css">
    <style>
        body {
            background: url('hichem/backgroun.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
        }

        .container {
            background: rgba(34, 34, 34, 0.9);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
        }

        h2 {
            color: #ffcc00;
        }

        

        .price {
            font-size: 20px;
            color: #28a745;
            font-weight: bold;
        }

        .balance {
            color: #ffcc00;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .btn, .buy-btn {
            background: #ffcc00;
            color: black;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
            margin-top: 10px;
        }

        .btn:hover, .buy-btn:hover {
            background: #ffdb4d;
        }

        input[type="text"], input[type="tel"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }
    </style>
</head>
<body>
<header class="header">
    <a href="#" class="logo">Tron Store</a>
    <nav class="navbar">
        <a href="home.php"> Home</a>
        <a href="profile.php"> Profile</a>
        <a href="logout.php"> Logout</a>
    </nav>
</header>

<div class="container">
  <h2>Buy: <?php echo htmlspecialchars($titre); ?></h2>
 <p class="info"> Price: <span class="price"><?php echo number_format($prix, 2); ?> DZD</span></p>
 <p class="balance">Your Balance: <?php echo number_format($solde, 2); ?> DZD</p>

    <form method="POST">
 <label for="adresse_livraison"> Delivery Address:</label>
  <input type="text" name="adresse_livraison" id="adresse_livraison" required>

<label for="telephone"> Phone Number:</label>
   <input type="tel" name="telephone" id="telephone" required>

 <?php if ($solde >= $prix): ?>
     <button type="submit" name="buy" class="buy-btn">✅ Confirm Purchase</button>
    <?php else: ?>
  <p class="error">❌ Insufficient balance!</p>
     <?php endif; ?>
    </form>
</div>
</body>
</html>
