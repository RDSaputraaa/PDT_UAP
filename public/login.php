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
<html><body>
<h2>Login</h2>
<?php if($error) echo "<p>$error</p>"; ?>
<form method="post">
<input name="username" placeholder="Username" required><br><br>
<input type="password" name="password" placeholder="Password" required><br><br>
<button type="submit">Login</button>
</form>
<a href="register.php">Register</a>
</body></html>
