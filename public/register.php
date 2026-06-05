<?php
require_once '../config/database.php';
require_once '../app/controllers/AuthController.php';

$auth = new AuthController($conn);
$msg = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['password'] != $_POST['confirm_password']){
        $msg = "Password dan Konfirmasi Password tidak cocok";

    } else {

        $role = $auth->register(
            $_POST['username'],
            $_POST['email'],
            $_POST['password']
        );

        if($role === "exists"){
            $msg = "Username atau Email sudah digunakan";
        }
        elseif($role){
            $msg = "Registrasi berhasil sebagai ".$role;
        }
        else{
            $msg = "Registrasi gagal";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - AmanParkir</title>

<link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

<div class="auth-card">

    <div class="auth-logo">
        <h1>AmanParkir</h1>
        <p>Sistem Manajemen Parkir FMIPA</p>
    </div>

    <?php if($msg): ?>
        <div class="alert alert-success">
            <?php echo $msg; ?>
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
            <label>Email</label>
            <input type="email"
                   name="email"
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

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password"
                   name="confirm_password"
                   class="form-control"
                   required>
        </div>

        <button type="submit"
                class="btn btn-primary btn-large">
            Register
        </button>

    </form>

    <div class="auth-footer">
        Sudah punya akun?
        <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>
