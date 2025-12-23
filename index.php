<?php
session_start();
require_once "config.php";
require_once "controllers/AuthController.php";
require_once __DIR__ . '/vendor/autoload.php';

$auth = new AuthController($conn);
$page = $_GET['page'] ?? "login";

// ROUTING
switch ($page) {

    case "login":
        include "views/auth/login.php";
        break;

    case "register":
        include "views/auth/register.php";
        break;

    case "login_action":
        $auth->login_action();
        break;

    case "register_action":
        $auth->register_action();
        break;

    case "admin_dashboard":
        include "views/admin/dashboard.php";
        break;

    case "admin_detail":
        require_once "controllers/PermohonanController.php";
        $ctrl = new PermohonanController($conn);
        $id = $_GET['id'] ?? null;
        if ($id) {
            $data = $ctrl->detail($id);
            $permohonan = $data['permohonan'];
            $user = $data['user'];
        }
        include "views/admin/admin_detail.php";
        break;

    case "user_dashboard":
        include "views/user/dashboard.php";
        break;

    case 'user_permohonan':
        include "views/user/form_permohonan.php";
        break;

    case "permohonan_action":
        require_once "controllers/PermohonanController.php";
        $ctrl = new PermohonanController($conn);
        $ctrl->create_action();
        break;

    case "permohonan_update":
        require_once "controllers/PermohonanController.php";
        $ctrl = new PermohonanController($conn);
        $id = $_POST['id'] ?? null;
        $action = $_POST['action'] ?? null;
        if ($id && $action) {
            $ctrl->updateStatus($id, $action);
        }
        break;

    case "generate_pdf":
        require_once "controllers/PermohonanPdfController.php";
        $ctrl = new PermohonanPdfController($conn);
        $id = $_POST['id'] ?? null;
        if ($id) {
            $ctrl->generate($id);
        }
        break;

    case "user_cek_status":
        include "views/user/user_cek_status.php";
        break;


    default:
        include "views/auth/login.php";
}
