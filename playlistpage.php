<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$playlist_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$conn = new mysqli("localhost", "root", "mysql", "zehra_coskun");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ✅ Add song to playlist
if (isset($_POST['add_song_id'])) {
    $song_id = intval($_POST['add_song_id']);
    $date = date("Y-m-d");
    $check = $conn->query("SELECT * FROM PLAYLIST_SONGS WHERE playlist_id = $playlist_id AND song_id = $song_id");
    if ($check->num_rows === 0) {
        $conn->query("INSERT INTO PLAYLIST_SONGS (playlist_id, song_id, date_added) VALUES ($playlist_id, $song_id, '$date')");
    }
}

// ✅ Delete song from playlist
if (isset($_POST['delete_song_id'])) {
    $song_id = intval($_POST['delete_song_id']);
    $conn->query("DELETE FROM PLAYLIST_SONGS WHERE playlist_id = $playlist_id AND song_id = $song_id");
}

// Playlist name
$plistRes = $conn->query("SELECT title FROM PLAYLISTS WHERE playlist_id = $playlist_id");
$plistTitle = $plistRes && $plistRes->num_rows > 0 ? $plistRes->fetch_assoc()['title'] : "Playlist";

// Song search logic
$search_results = null;
if (isset($_GET['search_song'])) {
    $keyword = $conn->real_escape_string($_GET['search_song']);
    $search_results = $conn->query("
        SELECT SONGS.song_id, SONGS.title, ARTISTS.name AS artist_name, COUNTRY.country_name AS country
        FROM SONGS
        JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
        JOIN ARTISTS ON ALBUMS.artist_id = ARTISTS.artist_id
        JOIN COUNTRY ON ARTISTS.country_id = COUNTRY.country_id
        WHERE SONGS.title LIKE '%$keyword%'
    ");
}

// Songs already in playlist
$songs = $conn->query("
    SELECT 
        SONGS.song_id,
        SONGS.title AS song_title,
        ARTISTS.name AS artist_name,
        COUNTRY.country_name AS country
    FROM PLAYLIST_SONGS
    JOIN SONGS ON PLAYLIST_SONGS.song_id = SONGS.song_id
    JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
    JOIN ARTISTS ON ALBUMS.artist_id = ARTISTS.artist_id
    JOIN COUNTRY ON ARTISTS.country_id = COUNTRY.country_id
    WHERE PLAYLIST_SONGS.playlist_id = $playlist_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($plistTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container">
        <h3><?= htmlspecialchars($plistTitle) ?> 🎶</h3>

        <!-- 🔍 Search bar -->
        <form method="get" class="mb-3">
            <input type="hidden" name="id" value="<?= $playlist_id ?>">
            <input type="text" name="search_song" class="form-control" placeholder="Search song to add...">
        </form>

        <!-- 🔄 Search results -->
        <?php if ($search_results): ?>
            <h5>Search Results</h5>
            <ul class="list-group mb-4">
                <?php while ($row = $search_results->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($row['title']) ?> — <?= htmlspecialchars($row['artist_name']) ?> (<?= htmlspecialchars($row['country']) ?>)
                        <form method="post" style="margin: 0;">
                            <input type="hidden" name="add_song_id" value="<?= $row['song_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-success">Add</button>
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <!-- 🎧 Playlist songs -->
        <h5>Songs in Playlist</h5>
        <table class="table table-bordered bg-white">
            <thead class="table-light">
                <tr>
                    <th>Song</th>
                    <th>Artist</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $songs->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['song_title']) ?></td>
                        <td><?= htmlspecialchars($row['artist_name']) ?></td>
                        <td><?= htmlspecialchars($row['country']) ?></td>
                        <td class="d-flex gap-2">
                            <a href="currentmusic.php?song_id=<?= $row['song_id'] ?>" class="btn btn-sm btn-success">▶ Play</a>
                            <form method="post" style="margin: 0;">
                                <input type="hidden" name="delete_song_id" value="<?= $row['song_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
