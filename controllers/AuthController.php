<?php
require_once "models/User.php";
require_once "models/Admin.php";

class AuthController {

    private $db;
    private $user;
    private $admin;

    public function __construct($conn) {
        $this->db = $conn;
        $this->user = new User($conn);
        $this->admin = new Admin($conn);
    }

    // LOGIN
    public function login_action() {
        $username = $_POST['username']; 
        $password = $_POST['password'];

        // ADMIN LOGIN
        $admin = $this->admin->login($username, $password);
        if ($admin) {
            $_SESSION['admin'] = $admin;
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: index.php?page=admin_dashboard");
            exit;
        }

        // USER LOGIN
        $user = $this->user->login($username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php?page=user_dashboard");
            exit;
        }

        $_SESSION['error'] = "Username atau password salah!";
        header("Location: index.php?page=login");
        exit;
    }

    // REGISTER PROCESS
    public function register_action() {
        $register = $this->user->register($_POST);

        if ($register) {
            $_SESSION['success'] = "Registrasi berhasil. Silakan login.";
            header("Location: index.php?page=login");
            exit;
        } else {
            $_SESSION['error'] = "Gagal mendaftar!";
            header("Location: index.php?page=register");
            exit;
        }
    }
}
