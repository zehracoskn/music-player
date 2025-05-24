<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "mysql", "zehra_coskun");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

if (isset($_GET['global_search'])) {
    $keyword = $conn->real_escape_string($_GET['global_search']);
    $plist = $conn->query("SELECT playlist_id FROM PLAYLISTS WHERE title LIKE '%$keyword%' AND user_id = $user_id LIMIT 1");
    if ($plist->num_rows > 0) {
        $id = $plist->fetch_assoc()['playlist_id'];
        header("Location: playlistpage.php?id=$id");
        exit();
    }
    $song = $conn->query("SELECT song_id FROM SONGS WHERE title LIKE '%$keyword%' LIMIT 1");
    if ($song->num_rows > 0) {
        $id = $song->fetch_assoc()['song_id'];
        header("Location: currentmusic.php?song_id=$id");
        exit();
    }
    echo "<script>alert('No playlist or song found matching: $keyword');</script>";
}

if (isset($_GET['artist_search'])) {
    $artistName = $conn->real_escape_string($_GET['artist_search']);
    $artist = $conn->query("SELECT artist_id FROM ARTISTS WHERE name LIKE '%$artistName%' LIMIT 1");
    if ($artist->num_rows > 0) {
        $id = $artist->fetch_assoc()['artist_id'];
        header("Location: artistpage.php?id=$id");
        exit();
    } else {
        echo "<script>alert('No artist found matching \"$artistName\"');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_playlist'])) {
    $title = $conn->real_escape_string($_POST['new_title']);
    $img = $conn->real_escape_string($_POST['new_image']);
    $date = date('Y-m-d');
    $conn->query("INSERT INTO PLAYLISTS (user_id, title, date_created, image) 
                  VALUES ($user_id, '$title', '$date', '$img')");
    header("Location: homepage.php");
    exit();
}

$playlists = $conn->query("SELECT * FROM PLAYLISTS WHERE user_id = $user_id");
$countryRes = $conn->query("SELECT country_id FROM USERS WHERE user_id = $user_id");
$countryRow = $countryRes->fetch_assoc();
$country_id = $countryRow['country_id'];

$songResult = null;
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['song_name'])) {
    $songName = $conn->real_escape_string($_POST['song_name']);
    $songResult = $conn->query("
        SELECT SONGS.song_id, SONGS.title, SONGS.genre, SONGS.duration, ALBUMS.title AS album_title, ARTISTS.name AS artist_name
        FROM SONGS
        JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
        JOIN ARTISTS ON ALBUMS.artist_id = ARTISTS.artist_id
        WHERE SONGS.title LIKE '%$songName%'
        LIMIT 1
    ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hello, <?= htmlspecialchars($name) ?>!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid p-4">
    <h2 class="text-center mb-4">🎵 Welcome, <?= htmlspecialchars($name) ?>!</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="get" class="d-flex mb-2" style="max-width: 400px;">
                <input type="text" name="global_search" class="form-control me-2" placeholder="Search playlist..." required>
                <button class="btn btn-outline-dark" type="submit">Search</button>
            </form>
            <button class="btn btn-success mb-2" onclick="togglePlaylistForm()">+ New Playlist</button>
            <form method="post" id="playlistForm" class="d-none" style="max-width: 400px;">
                <input type="text" name="new_title" class="form-control mb-2" placeholder="Playlist Name" required>
                <input type="text" name="new_image" class="form-control mb-2" placeholder="Image URL">
                <button type="submit" name="create_playlist" class="btn btn-primary w-100">Create</button>
            </form>
        </div>

        <div class="col-md-6">
            <form method="post" class="d-flex" style="max-width: 400px; float: right;">
                <input type="text" name="song_name" class="form-control me-2" placeholder="Search song..." required>
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 border-end">
            <h5>Your Playlists</h5>
            <?php while ($row = $playlists->fetch_assoc()) { ?>
                <div class="card mb-3">
                    <img src="<?= $row['image'] ?>" class="card-img-top" alt="playlist image">
                    <div class="card-body">
                        <a href="playlistpage.php?id=<?= $row['playlist_id'] ?>" class="card-title text-decoration-none">
                            <?= htmlspecialchars($row['title']) ?>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-md-9">
            <?php if ($songResult && $songResult->num_rows > 0): 
                $song = $songResult->fetch_assoc(); ?>
                <div class="card p-3 mb-4">
                    <h5>🎶 Song Found: <?= htmlspecialchars($song['title']) ?></h5>
                    <p><strong>Artist:</strong> <?= htmlspecialchars($song['artist_name']) ?></p>
                    <p><strong>Album:</strong> <?= htmlspecialchars($song['album_title']) ?></p>
                    <p><strong>Genre:</strong> <?= htmlspecialchars($song['genre']) ?></p>
                    <p><strong>Duration:</strong> <?= htmlspecialchars($song['duration']) ?></p>
                    <a href='currentmusic.php?song_id=<?= $song['song_id'] ?>' class='btn btn-success mt-2'>▶ Play</a>
                </div>
            <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['song_name'])): ?>
                <p class='text-danger'>❌ No song found with that name.</p>
            <?php endif; ?>

            <div class="mb-4">
                <h5>Last 10 Played Songs</h5>
                <ul class="list-group">
                    <?php
                    $history = $conn->query("
                        SELECT SONGS.song_id, SONGS.title 
                        FROM PLAY_HISTORY 
                        JOIN SONGS ON PLAY_HISTORY.song_id = SONGS.song_id 
                        WHERE PLAY_HISTORY.user_id = $user_id 
                        ORDER BY playtime DESC 
                        LIMIT 10
                    ");
                    while ($row = $history->fetch_assoc()) {
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                " . htmlspecialchars($row['title']) . "
                                <a href='currentmusic.php?song_id=" . $row['song_id'] . "' class='btn btn-sm btn-success'>▶</a>
                              </li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="mb-3">
                <form method="get" class="d-flex" style="max-width: 400px;">
                    <input type="text" name="artist_search" class="form-control me-2" placeholder="Search artist to view..." required>
                    <button type="submit" class="btn btn-outline-dark">Search</button>
                </form>
            </div>

            <div>
                <h5>Top Artists from Your Country</h5>
                <ul class="list-group">
                    <?php
                    $result = $conn->query("
                        SELECT artist_id, name, listeners
                        FROM ARTISTS
                        WHERE country_id = $country_id
                        ORDER BY listeners DESC
                        LIMIT 5
                    ");
                    $found = 0;
                    while ($row = $result->fetch_assoc()) {
                        $found++;
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                <a href='artistpage.php?id=" . $row['artist_id'] . "' class='text-decoration-none'>
                                    " . htmlspecialchars($row['name']) . "
                                </a>
                                <span class='badge bg-primary rounded-pill'>" . $row['listeners'] . " listeners</span>
                              </li>";
                    }
                    if ($found < 5) {
                        echo "<li class='list-group-item text-muted fst-italic'>Only $found artist(s) found for your country.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePlaylistForm() {
        const form = document.getElementById("playlistForm");
        form.classList.toggle("d-none");
    }
</script>

</body>
</html>
