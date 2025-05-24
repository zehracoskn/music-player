<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$artist_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$conn = new mysqli("localhost", "root", "mysql", "zehra_coskun");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch artist info
$artist = $conn->query("SELECT * FROM ARTISTS WHERE artist_id = $artist_id")->fetch_assoc();

// Fetch last 5 albums
$albums = $conn->query("
    SELECT title 
    FROM ALBUMS 
    WHERE artist_id = $artist_id 
    ORDER BY release_date DESC 
    LIMIT 5
");

// Fetch top 5 songs by rank
$songs = $conn->query("
    SELECT SONGS.title 
    FROM SONGS 
    JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id 
    WHERE ALBUMS.artist_id = $artist_id 
    ORDER BY SONGS.rank ASC 
    LIMIT 5

");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($artist['name']) ?> - Artist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container">
        <h2><?= htmlspecialchars($artist['name']) ?> 🎤</h2>
        <p><strong>Genre:</strong> <?= htmlspecialchars($artist['genre']) ?></p>
        <p><strong>Listeners:</strong> <?= number_format($artist['listeners']) ?></p>
        <p><strong>Bio:</strong> <?= htmlspecialchars($artist['bio']) ?></p>
        <button class="btn btn-primary">Follow</button>

        <hr>

        <h4>Last 5 Albums</h4>
        <ul class="list-group mb-4">
            <?php while ($row = $albums->fetch_assoc()) {
                echo "<li class='list-group-item'>" . htmlspecialchars($row['title']) . "</li>";
            } ?>
        </ul>

        <h4>Top 5 Songs</h4>
        <ul class="list-group">
            <?php while ($row = $songs->fetch_assoc()) {
                echo "<li class='list-group-item'>" . htmlspecialchars($row['title']) . "</li>";
            } ?>
        </ul>
    </div>
</body>
</html>
