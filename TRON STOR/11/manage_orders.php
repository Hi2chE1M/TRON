<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (isset($_POST['accept_order'])) {
    $order_id = $_POST['order_id'];

    $query = $conn->prepare("SELECT id_utilisateur, montant FROM commandes WHERE id_commande = ?");
    $query->bind_param("i", $order_id);
    $query->execute();
    $result = $query->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        $user_id = $order['id_utilisateur'];
        $amount = $order['montant'];

        $update_balance = $conn->prepare("UPDATE utilisateurs SET solde = solde + ? WHERE id_utilisateur = ?");
        $update_balance->bind_param("di", $amount, $user_id);
        $update_balance->execute();

        $update_order = $conn->prepare("UPDATE commandes SET statut = 'Accepted' WHERE id_commande = ?");
        $update_order->bind_param("i", $order_id);
        $update_order->execute();

        $_SESSION['success'] = "Order accepted and balance transferred.";
    } else {
        $_SESSION['error'] = "Order not found.";
    }
    header("Location: manage_orders.php");
    exit();
}

if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    $deleteQuery = $conn->prepare("DELETE FROM commandes WHERE id_commande = ?");
    $deleteQuery->bind_param("i", $order_id);
    $deleteQuery->execute();

    if ($deleteQuery->affected_rows > 0) {
        $_SESSION['success'] = "Order deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete order.";
    }
    header("Location: manage_orders.php");
    exit();
}

$sql = "SELECT * FROM commandes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Tron Store</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .header {
            background-color: #111;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: absolute;
            top: 0;
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

        .manage-orders-content {
            background: rgba(34, 34, 34, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            text-align: center;
            max-width: 900px;
            width: 100%;
        }

        .manage-orders-content h2 {
            font-size: 36px;
            margin-bottom: 30px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .orders-table th, .orders-table td {
            padding: 15px;
            border: 1px solid #ccc;
        }

        .orders-table th {
            background-color: #ffcc00;
            color: #222;
        }

        .btn {
            background: #ffcc00;
            color: #222;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            transition: transform 0.2s, background 0.3s;
            text-align: center;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #e6b800;
            transform: scale(1.05);
        }

        .delete-btn {
            background: red;
            color: white;
        }

        .delete-btn:hover {
            background: darkred;
        }

        .error, .success {
            color: white;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header class="header">
    <a href="#" class="logo">Tron Store</a>
    <nav class="navbar">
        <a href="admin_dashboard.php">admin dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="manage-orders-content">
    <h2>Manage Orders</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <table class="orders-table">
        <thead>
            <tr>
                <th>numero_carte</th>
                <th>date_expiration</th>
                <th>code_cvv</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['numero_carte']; ?></td>
                    <td><?php echo $row['date_expiration']; ?></td>
                    <td><?php echo $row['code_cvv']; ?></td>
                    <td><?php echo $row['montant']; ?></td>
                    <td><?php echo $row['statut']; ?></td>
                    <td><?php echo $row['date_commande']; ?></td>
                    <td>
                        <?php if ($row['statut'] == 'Pending'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $row['id_commande']; ?>">
                                <button type="submit" name="accept_order" class="btn">Accept</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['id_commande']; ?>">
                            <button type="submit" name="delete_order" class="btn delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
