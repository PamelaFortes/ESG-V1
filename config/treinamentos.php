<?php

require_once __DIR__ . '/database.php';

$sql = "SELECT * FROM treinamentos ORDER BY id";

$stmt = $pdo->query($sql);

$trainings = $stmt->fetchAll(PDO::FETCH_ASSOC);