<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "mysql", "zehra_coskun");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$song_id = isset($_GET['song_id']) ? intval($_GET['song_id']) : 0;

// ✅ Add song to play history
if ($song_id > 0 && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $timestamp = date("Y-m-d H:i:s");
    $conn->query("INSERT INTO PLAY_HISTORY (user_id, song_id, playtime) VALUES ($user_id, $song_id, '$timestamp')");
}

$song = $conn->query("
    SELECT SONGS.title, SONGS.duration, SONGS.genre, ALBUMS.title AS album_title, ARTISTS.name AS artist_name
    FROM SONGS
    JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
    JOIN ARTISTS ON ALBUMS.artist_id = ARTISTS.artist_id
    WHERE SONGS.song_id = $song_id
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Now Playing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex justify-content-center align-items-center vh-100">
    <div class="card text-center p-4 bg-secondary" style="width: 24rem;">
        <h4 class="mb-4">Now Playing 🎧</h4>
        <?php if ($song): ?>
            <p><strong>Title:</strong> <?= htmlspecialchars($song['title']) ?></p>
            <p><strong>Artist:</strong> <?= htmlspecialchars($song['artist_name']) ?></p>
            <p><strong>Album:</strong> <?= htmlspecialchars($song['album_title']) ?></p>
            <p><strong>Genre:</strong> <?= htmlspecialchars($song['genre']) ?></p>
            <p><strong>Duration:</strong> <?= htmlspecialchars($song['duration']) ?></p>
        <?php else: ?>
            <p class="text-danger">❌ No song selected or song not found.</p>
        <?php endif; ?>
        <a href="homepage.php" class="btn btn-light mt-3">⬅ Back</a>
    </div>
</body>
</html>
