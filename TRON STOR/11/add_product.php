<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'config.php';


$categories_result = $conn->query("SELECT id_categorie, nom_categorie FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = trim($_POST['titre']);
    $prix = trim($_POST['prix']);
    $id_categorie = $_POST['id_categorie'];
    $id_admin = $_SESSION['user_id'];

    if (!empty($titre) && !empty($prix) && is_numeric($prix) && !empty($id_categorie)) {
        $stmt = $conn->prepare("INSERT INTO jeux (titre, prix, id_categorie, id_admin, date_ajout) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sdii", $titre, $prix, $id_categorie, $id_admin);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add product.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill all fields correctly.";
    }
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Tron Store</title>
    
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

       .form-container {
           background: rgba(34, 34, 34, 0.9);
           padding: 30px;
           border-radius: 10px;
           box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
           text-align: center;
           max-width: 400px;
           width: 100%;
           margin: auto;
           margin-top: 100px;
       }

       .input-box {
           width: 100%;
           margin-bottom: 15px;
       }

       .input-box input, .input-box select {
           width: 100%;
           padding: 16px;
           border-radius: 5px;
           border: none;
           background: #444;
           color: white;
           font-size: 16px;
       }

       .btn {
           background: #ffcc00;
           color: #222;
           padding: 18px;
           border-radius: 5px;
           font-size: 18px;
           text-align: center;
           width: 100%;
           border: none;
           cursor: pointer;
       }

       .btn:hover {
           background: #e6b800;
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
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_products.php">Manage Products</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    
    <div class="form-container">
        <h2 class="logo">Add Product</h2>
        <form action="add_product.php" method="POST">
            <div class="input-box">
                <input type="text" name="titre" placeholder="Product Name" required>
            </div>
            
            <div class="input-box">
                <input type="number" name="prix" placeholder="Price" step="0.01" required>
            </div>

            <div class="input-box">
                <select name="id_categorie" required>
                    <option value="">Select Category</option>
                    <?php while ($row = $categories_result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_categorie']; ?>"> <?php echo $row['nom_categorie']; ?> </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <button type="submit" class="btn">Add Product</button>
            
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
