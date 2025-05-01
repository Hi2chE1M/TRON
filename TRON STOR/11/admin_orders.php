<?php
session_start();
include 'config.php';


if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$stmt = $conn->prepare("SELECT * FROM commandes WHERE statut = 'Pending'");
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $stmt_update = $conn->prepare("UPDATE commandes SET statut = ? WHERE id_commande = ?");
    $stmt_update->bind_param("si", $new_status, $order_id);
    $stmt_update->execute();
    
    header("Location: admin_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #222;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #333;
            color: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #555;
        }
        .btn {
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            margin: 5px;
            font-size: 14px;
        }
        .approve { background: #28a745; color: white; }
        .reject { background: #dc3545; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>📋 Pending Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Amount (DZD)</th>
            <th>Card Number</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id_commande']; ?></td>
            <td><?php echo $row['id_utilisateur']; ?></td>
            <td><?php echo number_format($row['montant'], 2); ?> DZD</td>
            <td><?php echo htmlspecialchars($row['num_carte']); ?></td>
            <td><?php echo $row['statut']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="order_id" value="<?php echo $row['id_commande']; ?>">
                    <button type="submit" name="update_status" value="Accepted" class="btn approve">✅ Accept</button>
                    <button type="submit" name="update_status" value="Rejected" class="btn reject">❌ Reject</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>