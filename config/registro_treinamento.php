<?php

require_once __DIR__ . '/database.php';

$sql = "
    SELECT
        r.id,
        r.funcionario_id AS emp_id,
        f.nome AS emp_name,
        f.cargo AS emp_role,
        f.setor AS emp_sector,
        r.treinamento_id,
        t.nome AS training,
        r.data_realizacao AS done,
        r.data_validade AS expiry,
        r.observacoes
    FROM registros_treinamento r
    INNER JOIN funcionarios f
        ON f.id = r.funcionario_id
    INNER JOIN treinamentos t
        ON t.id = r.treinamento_id
    ORDER BY r.data_validade
";

$stmt = $pdo->query($sql);

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = new DateTime();

foreach ($records as &$record) {

    $validade = new DateTime($record['expiry']);

    $diferenca = $today->diff($validade);

    if ($validade < $today) {

        $record['status'] = 'expired';
        $record['days'] = 0;

    } else {

        $record['days'] = (int) $diferenca->format('%r%a');

        if ($record['days'] <= 30) {
            $record['status'] = 'expiring';
        } else {
            $record['status'] = 'valid';
        }
    }
}

unset($record);