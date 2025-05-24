<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "mysql", "zehra_coskun");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = null;
$error = '';
$query = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST['sql'];
    $result = $conn->query($query);

    if (!$result && $conn->error) {
        $error = $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>General SQL Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
    <h3 class="mb-4">🧠 General SQL Query Tool</h3>

    <form method="post" class="mb-4">
        <textarea name="sql" class="form-control" rows="4" placeholder="Write your SQL query here..."><?= htmlspecialchars($query) ?></textarea>
        <button type="submit" class="btn btn-dark mt-2">Run Query</button>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-light">
                <tr>
                    <?php while ($field = $result->fetch_field()): ?>
                        <th><?= htmlspecialchars($field->name) ?></th>
                    <?php endwhile; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?= htmlspecialchars($cell) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($query): ?>
        <div class="alert alert-warning">✅ Query ran successfully (but returned no data).</div>
    <?php endif; ?>
</div>
</body>
</html>
