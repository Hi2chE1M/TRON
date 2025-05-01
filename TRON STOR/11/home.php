<?php
session_start();
include 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}


$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tron Store</title>
    <link rel="stylesheet" href="home.css">
 <link rel="stylesheet" href="hichem.css">
    <style>
        body {
            background: url('hichem/backgroun.jpg') no-repeat center center fixed;
            background-size: cover;
          
        }   
    </style>
</head>
<body>

    <header class="header">
        <a href="#" class="logo">Tron Store</a>
        <nav class="navbar">
            <form method="GET" action="home.php" class="search-bar">
                <input type="text" name="search" placeholder="Search for a game..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
            <a href="home.php">HOME</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="user-info">Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <a href="profile.php" class="profile-btn">Profile</a>
                <a href="home.php?logout=true" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
            <a href="https://wa.me/213669974828" target="_blank">Support</a>
        </nav>
    </header>

    <div class="games-container">
        <?php
        $sql = "SELECT id_jeu, titre, prix FROM Jeux";
        if (!empty($search_query)) {
            $sql .= " WHERE titre LIKE '%$search_query%'";
        }
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $image = "hichem/" . $row['id_jeu'] . ".jpg";
                if (!file_exists($image)) {
                    $image = "game_placeholder.jpg";
                }
                echo "<div class='card'>
                        <img src='$image' alt='" . htmlspecialchars($row['titre']) . "'/>
                        <h3>" . htmlspecialchars($row['titre']) . "</h3>
                        <p>" . number_format($row['prix'], 2) . " DZD</p>
                        <a href='payment.php?id=" . $row['id_jeu'] . "' class='payment'>Buy Now</a>
                      </div>";
            }
        } else {
            echo "<p>No games available.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>