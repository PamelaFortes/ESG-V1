<?php

$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'postgres';
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

/* teste diagnostico p teste local de variaveis de ambiente com apache
echo '<pre>';
echo 'HOST = [' . getenv('DB_HOST') . ']' . PHP_EOL;
echo 'PORT = [' . getenv('DB_PORT') . ']' . PHP_EOL;
echo 'NAME = [' . getenv('DB_NAME') . ']' . PHP_EOL;
echo 'USER = [' . getenv('DB_USER') . ']' . PHP_EOL;
echo 'PASSWORD = [' . (getenv('DB_PASSWORD') ? 'DEFINIDA' : 'VAZIA') . ']' . PHP_EOL;
echo '</pre>';

exit;

*/

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro na conexão com o banco: " . $e->getMessage());
}