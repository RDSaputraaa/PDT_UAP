<?php
require_once '../config/database.php';
require_once '../app/controllers/AuthController.php';

$auth = new AuthController($conn);
$msg='';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $msg = $auth->register($_POST['username'], $_POST['password'])
        ? 'Registrasi berhasil'
        : 'Registrasi gagal';
}
?>
<!DOCTYPE html>
<html><body>
<h2>Register</h2>
<p><?php echo $msg; ?></p>
<form method="post">
<input name="username" required placeholder="Username"><br><br>
<input type="password" name="password" required placeholder="Password"><br><br>
<button type="submit">Register</button>
</form>
<a href="login.php">Login</a>
</body></html>
