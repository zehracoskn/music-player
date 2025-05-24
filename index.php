<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: install.php?redirect=login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zehra Coşkun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-dark text-white">
    <div class="text-center">
        <h1 class="mb-3">Welcome to Zehra's Music App</h1>
        <p class="mb-4 fs-5 fst-italic text-light-emphasis">Ready to vibe?</p>
        <form method="post">
            <button type="submit" class="btn btn-lg btn-primary px-5">Initialize Database</button>
        </form>
    </div>
</body>
</html>
