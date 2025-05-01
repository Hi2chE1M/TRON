<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

  $deleteQuery = $conn->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
 $deleteQuery->bind_param("i", $user_id);
  $deleteQuery->execute();

 if ($deleteQuery->affected_rows > 0) {
 $_SESSION['success'] = "User deleted successfully.";
    } else {
  $_SESSION['error'] = "Failed to delete user.";
    }
    header("Location: manage_users.php");
    exit();
}

$sql = "SELECT * FROM utilisateurs ORDER BY date_inscription DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Tron Store</title>
    <link rel="stylesheet" href="home.css">
    <style>
        body {
            background: url('hichem/backgroun.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
         padding: 0;
          display: flex;
          flex-direction: column;
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

        .manage-users-content {
            background: rgba(34, 34, 34, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            text-align: center;
            max-width: 900px;
            width: 100%;
            margin-top: 80px;
        }

        .manage-users-content h2 {
            font-size: 36px;
            margin-bottom: 30px;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .users-table th, .users-table td {
            padding: 15px;
            border: 1px solid #ccc;
        }

        .users-table th {
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
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="manage-users-content">
    <h2>Manage Users</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="success"> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?> </p>
    <?php endif; ?>

    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Signup Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
               <td><?php echo $row['id_utilisateur']; ?></td>
                <td><?php echo $row['nom']; ?></td>
               <td><?php echo $row['email']; ?></td>
           <td><?php echo $row['role']; ?></td>
             <td><?php echo $row['date_inscription']; ?></td>
         <td>
       <form method="POST" style="display:inline;">
     <input type="hidden" name="user_id" value="<?php echo $row['id_utilisateur']; ?>">
   <button type="submit" name="delete_user" class="btn delete-btn">Delete</button>
               </form>
                   </td>
          </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
