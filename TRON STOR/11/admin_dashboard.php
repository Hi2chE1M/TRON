<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}


include 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_achat'])) {
    $id_achat = $_POST['id_achat'];
    $update_sql = "UPDATE achats SET statut = 'complet' WHERE id_achat = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $id_achat);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT achats.id_achat, utilisateurs.nom, utilisateurs.email, achats.montant, achats.date_achat, 
               achats.adresse_livraison, achats.telephone, achats.statut, jeux.titre
        FROM achats 
        JOIN utilisateurs ON achats.id_utilisateur = utilisateurs.id_utilisateur
        JOIN jeux ON achats.id_jeu = jeux.id_jeu
        ORDER BY achats.date_achat DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tron Store</title>
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
           width: 100%;
           position: fixed;
           top: 0;
           left: 0;
       }

       .logo {
           font-size: 30px;
           font-weight: bold;
           color: #ffcc00;
           text-decoration: none;
       }

       .navbar a {
           color: white;
           text-decoration: none;
           margin: 0 15px;
           font-size: 18px;
       }

       .navbar a:hover {
           color: #ffcc00;
       }

       .admin-dashboard-content {
           margin-top: 80px;
           text-align: center;
       }

       .btn {
           background: #ffcc00;
           color: #222;
           padding: 10px 15px;
           border-radius: 5px;
           font-size: 14px;
           transition: 0.3s;
           text-decoration: none;
           display: inline-block;
           margin: 5px;
           border: none;
           cursor: pointer;
       }

       .btn:hover {
           background: #e6b800;
       }

       .purchases {
           background: rgba(34, 34, 34, 0.9);
           padding: 20px;
           border-radius: 10px;
           box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
           max-width: 1000px;
           margin: auto;
           margin-top: 40px;
       }

       table {
           width: 100%;
           border-collapse: collapse;
           margin-top: 20px;
       }

       th, td {
           border: 1px solid #fff;
           padding: 10px;
           text-align: left;
       }

       th {
           background: #ffcc00;
           color: black;
       }

       tr:nth-child(even) {
           background: #333;
       }

       tr:hover {
           background: #444;
       }

       .completed {
           background: #4CAF50;
           color: white;
           padding: 5px 10px;
           border-radius: 5px;
           font-size: 14px;
       }
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">Tron Store</a>
        <nav class="navbar">
           <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="admin-dashboard-content">
        <h2>Welcome to the Admin Dashboard, <?php echo $_SESSION['user_name']; ?></h2>
        <a href="manage_orders.php" class="btn">View Orders</a>
        <a href="manage_users.php" class="btn">View Users</a>
        <a href="manage_products.php" class="btn">View Products</a>

        <div class="purchases">
            <h2>All Purchases</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Game Title</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Amount (DZD)</th>
                    <th>Delivery Address</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_achat']; ?></td>
                        <td><?php echo htmlspecialchars($row['titre']); ?></td>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['date_achat']; ?></td>
                        <td><?php echo number_format($row['montant'], 2); ?> DZD</td>
                        <td><?php echo htmlspecialchars($row['adresse_livraison']); ?></td>
                        <td><?php echo htmlspecialchars($row['telephone']); ?></td>
                        <td>
                            <?php if ($row['statut'] == 'complet'): ?>
                                <span class="completed">Completed</span>
                            <?php else: ?>
                                <span style="color: red;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['statut'] != 'complet'): ?>
                                <form method="post">
                                    <input type="hidden" name="id_achat" value="<?php echo $row['id_achat']; ?>">
                                    <button type="submit" class="btn">✅ Delivered</button>
                                </form>
                            <?php else: ?>
                                ✔️
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
