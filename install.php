<?php
// Zehra Coşkun - CSE348 Project - install.php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "zehra_coskun";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// USERS
$conn->query("CREATE TABLE IF NOT EXISTS USERS (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    country_id INT,
    age INT,
    name VARCHAR(100),
    username VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(50),
    date_joined DATE,
    last_login DATETIME,
    follower_num INT,
    subscription_type VARCHAR(50),
    top_genre VARCHAR(50),
    num_songs_liked INT,
    most_played_artist VARCHAR(100),
    image VARCHAR(255)
)");

// PLAY_HISTORY
$conn->query("CREATE TABLE IF NOT EXISTS PLAY_HISTORY (
    play_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    song_id INT,
    playtime DATETIME
)");

// ARTISTS
$conn->query("CREATE TABLE IF NOT EXISTS ARTISTS (
    artist_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    genre VARCHAR(50),
    date_joined DATE,
    total_num_music INT,
    total_albums INT,
    listeners INT,
    bio TEXT,
    country_id INT,
    image VARCHAR(255)
)");

// ALBUMS
$conn->query("CREATE TABLE IF NOT EXISTS ALBUMS (
    album_id INT AUTO_INCREMENT PRIMARY KEY,
    artist_id INT,
    title VARCHAR(100),
    release_date DATE,
    genre VARCHAR(50),
    music_number INT,
    image VARCHAR(255)
)");

// SONGS (with country_id)
$conn->query("CREATE TABLE IF NOT EXISTS SONGS (
    song_id INT AUTO_INCREMENT PRIMARY KEY,
    album_id INT,
    country_id INT,
    title VARCHAR(100),
    duration TIME,
    genre VARCHAR(50),
    release_date DATE,
    `rank` INT,
    image VARCHAR(255)
)");

// COUNTRY
$conn->query("CREATE TABLE IF NOT EXISTS COUNTRY (
    country_id INT PRIMARY KEY,
    country_name VARCHAR(100),
    country_code VARCHAR(10)
)");

// PLAYLISTS
$conn->query("CREATE TABLE IF NOT EXISTS PLAYLISTS (
    playlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(100),
    description TEXT,
    date_created DATE,
    image VARCHAR(255)
)");

// PLAYLIST_SONGS
$conn->query("CREATE TABLE IF NOT EXISTS PLAYLIST_SONGS (
    playlistsong_id INT AUTO_INCREMENT PRIMARY KEY,
    playlist_id INT,
    song_id INT,
    date_added DATE
)");

echo "<h3 style='text-align:center; color:green;'>Tables are created in  $dbname</h3>";

// 🔁 Redirect if requested
if (isset($_GET['redirect'])) {
    $to = $_GET['redirect'];
    echo "<script>setTimeout(() => { window.location.href = '$to'; }, 1000);</script>";
}

$conn->close();
?>
