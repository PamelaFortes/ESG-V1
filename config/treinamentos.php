<?php

require_once __DIR__ . '/database.php';

$sql = "
    SELECT
        t.id,
        t.nome AS name,
        t.descricao AS desc,
        t.carga_horaria AS hours,
        ROUND(t.validade_dias / 30.0) AS validity,
        CASE
        WHEN t.status = 'ativo' THEN 'active'
        ELSE 'inactive'
    END AS status,

    COUNT(r.id) AS employees
        COUNT(r.id) AS employees

    FROM treinamentos t

    LEFT JOIN registros_treinamento r
        ON r.treinamento_id = t.id

    GROUP BY
        t.id,
        t.nome,
        t.descricao,
        t.carga_horaria,
        t.validade_dias,
        t.status

    ORDER BY t.id
";
$stmt = $pdo->query($sql);

$trainings = $stmt->fetchAll(PDO::FETCH_ASSOC);