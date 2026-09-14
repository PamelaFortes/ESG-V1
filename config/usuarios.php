<?php

require_once __DIR__ . '/database.php';

$email = trim($_POST['email'] ?? '');

$sql = "
    SELECT
        id,
        nome,
        email,
        senha,
        tipo,
        funcionario_id
    FROM usuarios
    WHERE email = :email
    LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'email' => $email
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);