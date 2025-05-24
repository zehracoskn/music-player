<?php
// Zehra Coşkun - 20220702037

$nameFile = "name_input.txt";
$countryFile = "country_input.txt";
$outputFile = "output.sql";

$names = file($nameFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$countries = file($countryFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$output = fopen($outputFile, "w");
if (!$output) die("Cannot open output.sql");

// COUNTRY TABLE
foreach ($countries as $line) {
    list($id, $name, $code) = explode(",", $line);
    $sql = "INSERT INTO COUNTRY (country_id, country_name, country_code) VALUES ($id, '$name', '$code');\n";
    fwrite($output, $sql);
}

// USERS TABLE
for ($i = 1; $i <= 100; $i++) {
    $name = trim($names[array_rand($names)]);
    $username = strtolower($name) . $i;
    $email = $username . "@mail.com";
    $password = "123";
    $country_id = rand(1, 50);
    $age = rand(18, 60);
    $date_joined = "2023-0" . rand(1, 9) . "-" . rand(10, 28);
    $last_login = "2024-0" . rand(1, 9) . "-" . rand(10, 28) . " 12:00:00";
    $followers = rand(0, 500);
    $sub = rand(0, 1) ? "Free" : "Premium";
    $genre = "pop";
    $liked = rand(0, 100);
    $artist = trim($names[array_rand($names)]);
    $image = "user.png";

    $sql = "INSERT INTO USERS (country_id, age, name, username, email, password, date_joined, last_login, follower_num, subscription_type, top_genre, num_songs_liked, most_played_artist, image) 
    VALUES ($country_id, $age, '$name', '$username', '$email', '$password', '$date_joined', '$last_login', $followers, '$sub', '$genre', $liked, '$artist', '$image');\n";
    fwrite($output, $sql);
}

// ARTISTS TABLE
for ($i = 1; $i <= 100; $i++) {
    if ($i <= 50) {
        $name = trim($names[array_rand($names)]);
    } else {
        $name = trim($names[array_rand($names)]) . " Band";
    }
    $genre = ['pop','rock','hiphop','electronic','jazz','indie'][array_rand(['pop','rock','hiphop','electronic','jazz','indie'])];
    $joined = "2022-0" . rand(1, 9) . "-" . rand(10, 28);
    $music = rand(10, 100);
    $albums = rand(1, 10);
    $listeners = rand(1000, 99999);
    $bio = "Bio for $name";
    $country_id = rand(1, 50);
    $image = "artist.png";

    $sql = "INSERT INTO ARTISTS (name, genre, date_joined, total_num_music, total_albums, listeners, bio, country_id, image)
    VALUES ('$name', '$genre', '$joined', $music, $albums, $listeners, '$bio', $country_id, '$image');\n";
    fwrite($output, $sql);
}

// ALBUMS TABLE
for ($i = 1; $i <= 200; $i++) {
    $artist_id = rand(1, 100);
    $title = "Album $i";
    $release = "202" . rand(0, 3) . "-" . rand(1, 12) . "-" . rand(10, 28);
    $genre = ['pop','rock','hiphop','electronic','jazz','indie'][array_rand(['pop','rock','hiphop','electronic','jazz','indie'])];
    $music_number = rand(5, 15);
    $image = "album.png";

    $sql = "INSERT INTO ALBUMS (artist_id, title, release_date, genre, music_number, image)
    VALUES ($artist_id, '$title', '$release', '$genre', $music_number, '$image');\n";
    fwrite($output, $sql);
}

// SONGS TABLE
for ($i = 1; $i <= 1000; $i++) {
    $album_id = rand(1, 200);
    $country_id = rand(1, 50);
    $title = "Song $i";
    $duration = "00:" . rand(2, 4) . ":" . rand(10, 59);
    $genre = ['pop','rock','hiphop','electronic','jazz','indie'][array_rand(['pop','rock','hiphop','electronic','jazz','indie'])];
    $release = "202" . rand(0, 3) . "-" . rand(1, 12) . "-" . rand(10, 28);
    $rank = rand(1, 100);
    $image = "album.png";

    $sql = "INSERT INTO SONGS (album_id, country_id, title, duration, genre, release_date, `rank`, image)
    VALUES ($album_id, $country_id, '$title', '$duration', '$genre', '$release', $rank, '$image');\n";
    fwrite($output, $sql);
}



// PLAYLISTS TABLE
for ($i = 1; $i <= 500; $i++) {
    $user_id = rand(1, 100);
    $title = "Playlist $i";
    $desc = "Playlist for testing";
    $created = "2023-" . rand(1, 12) . "-" . rand(10, 28);
    $image = "playlist.png";

    $sql = "INSERT INTO PLAYLISTS (user_id, title, description, date_created, image)
    VALUES ($user_id, '$title', '$desc', '$created', '$image');\n";
    fwrite($output, $sql);
}

// PLAYLIST_SONGS TABLE
for ($i = 1; $i <= 500; $i++) {
    $playlist_id = rand(1, 500);
    $song_id = rand(1, 1000);
    $added = "2024-" . rand(1, 12) . "-" . rand(10, 28);

    $sql = "INSERT INTO PLAYLIST_SONGS (playlist_id, song_id, date_added)
    VALUES ($playlist_id, $song_id, '$added');\n";
    fwrite($output, $sql);
}

// PLAY_HISTORY TABLE
for ($i = 1; $i <= 100; $i++) {
    $user_id = rand(1, 100);
    $song_id = rand(1, 1000);
    $playtime = "2024-" . rand(1, 12) . "-" . rand(10, 28) . " 12:00:00";

    $sql = "INSERT INTO PLAY_HISTORY (user_id, song_id, playtime)
    VALUES ($user_id, $song_id, '$playtime');\n";
    fwrite($output, $sql);
}

fclose($output);
echo "✅ output.sql created successfully.";
?>
