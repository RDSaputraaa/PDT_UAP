<?php
class AuthController {
    private $conn;

    public function __construct($db) {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        $this->conn = $db;
    }

    public function login($username, $password) {

        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE username=? LIMIT 1"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();

        if (
            $user &&
            ($user['password'] === $password ||
             password_verify($password, $user['password']))
        ) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            return true;
        }

        return false;
    }

    public function register($username, $email, $password) {

        $check = $this->conn->prepare(
            "SELECT id FROM users
            WHERE username=? OR email=?"
        );

        $check->bind_param(
            "ss",
            $username,
            $email
        );

        $check->execute();

        if($check->get_result()->num_rows > 0){
            return "exists";
        }

        $result = $this->conn->query(
            "SELECT COUNT(*) as total_admin
            FROM users
            WHERE role='admin'"
        );

        $data = $result->fetch_assoc();

        if($data['total_admin'] == 0){
            $role = 'admin';
        } else {
            $role = 'petugas';
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO users(username,email,password,role)
            VALUES(?,?,?,?)"
        );

        $stmt->bind_param(
            "ssss",
            $username,
            $email,
            $hash,
            $role
        );

        if($stmt->execute()){
            return $role;
        }

        return false;
        }

    public function logout() {
        session_destroy();
    }
}
?>
