<?php
/*
inicia sessão
verifica login
cria sessao
redireciona
logout
protege paginas
*/
session_start();

require_once __DIR__ . '/../config/usuarios.php';



if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'login'
) {

    $pass = $_POST['password'] ?? '';

    if ($user && password_verify($pass, $user['senha'])) {

        $_SESSION['logged_in'] = true;
        $_SESSION['role']      = $user['tipo'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['emp_id']    = $user['funcionario_id'];

        if ($user['tipo'] === 'gestor') {
            header('Location: ?page=dashboard');
        } else {
            header('Location: ?page=meu-perfil');
        }

        exit;

    } else {

        header('Location: ?page=login&error=1');
        exit;
    }
}



// LOGOUT


if (isset($_GET['logout'])) {

    session_destroy();

    header('Location: ?page=login');
    exit;
}



// PÁGINA ATUAL

$page = $_GET['page'] ?? 'login';



// CONTROLE DE ACESSO


$employee_pages = [
    'meu-perfil'
];

$manager_pages = [
    'dashboard',
    'employees',
    'employee-form',
    'employee-profile',
    'trainings',
    'training-form',
    'register-training',
    'pending'
];

$sess_role   = $_SESSION['role'] ?? '';
$sess_name   = $_SESSION['user_name'] ?? '';
$sess_emp_id = (int)($_SESSION['emp_id'] ?? 0);


// Usuário não logado tentando acessar uma página protegida
if (
    $page !== 'login'
    && empty($_SESSION['logged_in'])
) {
    header('Location: ?page=login');
    exit;
}


// Funcionário tentando acessar área do gestor
if (
    $sess_role === 'funcionario'
    && in_array($page, $manager_pages)
) {
    header('Location: ?page=meu-perfil');
    exit;
}


// Gestor tentando acessar área exclusiva do funcionário
if (
    $sess_role === 'gestor'
    && in_array($page, $employee_pages)
) {
    header('Location: ?page=dashboard');
    exit;
}