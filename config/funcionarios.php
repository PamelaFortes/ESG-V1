<?php

require_once __DIR__ . '/database.php';

$sql = "
    SELECT
        f.id,
        f.id AS registration,
        f.nome AS name,
        f.cpf,
        f.email,
        f.telefone AS phone,
        NULL AS dob,
        f.cargo AS role,
        f.setor AS sector,
        f.data_admissao AS admission,
        
        CASE
            WHEN f.status = 'ativo' THEN 'active'
            ELSE 'inactive'
        END AS status,

        COUNT(r.id) AS t_total,

        COUNT(
            CASE
                WHEN r.data_validade > CURRENT_DATE + INTERVAL '30 days'
                THEN 1
            END
        ) AS t_valid,

        COUNT(
            CASE
                WHEN r.data_validade >= CURRENT_DATE
                 AND r.data_validade <= CURRENT_DATE + INTERVAL '30 days'
                THEN 1
            END
        ) AS t_expiring,

        COUNT(
            CASE
                WHEN r.data_validade < CURRENT_DATE
                THEN 1
            END
        ) AS t_expired

    FROM funcionarios f

    LEFT JOIN registros_treinamento r
        ON r.funcionario_id = f.id

    GROUP BY
        f.id,
        f.nome,
        f.cpf,
        f.email,
        f.telefone,
        f.cargo,
        f.setor,
        f.data_admissao,
        f.status

    ORDER BY f.id
";

$stmt = $pdo->query($sql);

$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);