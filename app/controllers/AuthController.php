<?php
class AuthController {
    private $conn;

    public function __construct($db) {
        session_start();
        $this->conn = $db;
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && ($user['password'] === $password || password_verify($password, $user['password']))) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function register($username, $password, $role='petugas') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?)");
        $stmt->bind_param("sss", $username, $hash, $role);
        return $stmt->execute();
    }

    public function logout() {
        session_destroy();
    }
}
?>
