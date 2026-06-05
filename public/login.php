<?php
require_once '../config/database.php';
require_once '../app/controllers/AuthController.php';

$auth = new AuthController($conn);
$error = '';

if($_SERVER['REQUEST_METHOD']=='POST'){
    if($auth->login($_POST['username'], $_POST['password'])){
        header('Location: index.php');
        exit;
    }
    $error='Username atau password salah';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - AmanParkir</title>

<link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">

<div class="auth-card">

    <div class="auth-logo">
        <h1>AmanParkir</h1>
        <p>Sistem Manajemen Parkir FMIPA</p>
    </div>

    <?php if($error): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="auth-form">

        <div class="form-group">
            <label>Username</label>
            <input type="text"
                   name="username"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   required>
        </div>

        <button type="submit"
                class="btn btn-primary btn-large">
            Login
        </button>

    </form>

    <div class="auth-footer">
        Belum punya akun?
        <a href="register.php">Register</a>
    </div>

</div>

</body>
</html>
