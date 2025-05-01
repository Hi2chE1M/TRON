<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'config.php';


if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
   $deleteQuery = $conn->prepare("DELETE FROM jeux WHERE id_jeu = ?");
    $deleteQuery->bind_param("i", $product_id);
 $deleteQuery->execute();

 if ($deleteQuery->affected_rows > 0) {
  $_SESSION['success'] = "Product deleted successfully.";
 } else {
 $_SESSION['error'] = "Failed to delete product.";
    }
    header("Location: manage_products.php");
 exit();
}


$sql = "SELECT jeux.id_jeu, jeux.titre, jeux.prix, utilisateurs.nom AS admin_name 
 FROM jeux 
 LEFT JOIN utilisateurs ON jeux.id_admin = utilisateurs.id_utilisateur 
  ORDER BY jeux.date_ajout DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Tron Store</title>
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

       .manage-products-content {
           background: rgba(34, 34, 34, 0.9);
           padding: 40px;
           border-radius: 10px;
           box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
           text-align: center;
           max-width: 900px;
           width: 100%;
           margin: auto;
           margin-top: 50px;
       }

       .products-table {
           width: 100%;
           border-collapse: collapse;
           margin-bottom: 20px;
       }

       .products-table th, .products-table td {
           padding: 15px;
           border: 1px solid #ccc;
       }

       .products-table th {
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
    </style>
</head>
<body>
    <header class="header">
    <a href="#" class="logo">Tron Store</a>
    <nav class="navbar">
       <a href="admin_dashboard.php">Admin Dashboard</a>
       
   <a href="logout.php"> Logout</a>
        </nav>
    </header>

    <div class="manage-products-content">
        <h2>Manage Products</h2>

   <?php if (isset($_SESSION['error'])): ?>
    <p class="error"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </p>
 <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
 <p class="success"> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?> </p>
   <?php endif; ?>

  <table class="products-table">
        <thead>
            <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Host</th>
                    <th>Actions</th>
        </tr>
       </thead>
      <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
               <td><?php echo $row['titre']; ?></td>
                        <td><?php echo $row['prix']; ?> DA</td>
                <td><?php echo $row['admin_name']; ?></td>
              <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $row['id_jeu']; ?>">
                                <button type="submit" name="delete_product" class="btn delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>
        <a href="add_product.php" class="btn">Add Product</a>
    </div>
</body>
</html>