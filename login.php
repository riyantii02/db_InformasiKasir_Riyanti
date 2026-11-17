<?php
session_start();

if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Kasir</title>
    <link rel="stylesheet" href="src/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="container">
    <div class="card login-card">

        <h2>Login Kasir</h2>

        <?php if (isset($_GET['error'])): ?>
            <p>Username atau password salah!</p>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>

        </form>

    </div>
</div>

</body>
</html>