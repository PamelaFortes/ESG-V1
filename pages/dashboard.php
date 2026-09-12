<?php

require_once __DIR__ . '/../config/funcionarios.php';
require_once __DIR__ . '/../config/treinamentos.php';

// ============================================================
// CÁLCULOS DO DASHBOARD
// ============================================================

function calcularTotalFuncionarios($employees)
{
    return count($employees);
}

function calcularFuncionariosAtivos($employees)
{
    $total = 0;

    foreach ($employees as $employee) {
        if ($employee['status'] === 'ativo') {
            $total++;
        }
    }

    return $total;
}

$totalFuncionarios = calcularTotalFuncionarios($employees);
$funcionariosAtivos = calcularFuncionariosAtivos($employees);
$totalTreinamentos = count($trainings);


// ============================================================
// DADOS TEMPORÁRIOS DE TREINAMENTOS
// ============================================================

// Por enquanto, os treinamentos continuam vindo do mock_data.php.
// Depois vamos substituir pelo Supabase.

$expired = array_filter(
    $records,
    fn($r) => $r['status'] === 'expired'
);

$expiring = array_filter(
    $records,
    fn($r) => $r['status'] === 'expiring'
);

$valid = array_filter(
    $records,
    fn($r) => $r['status'] === 'valid'
);

$in7 = array_filter(
    $expiring,
    fn($r) => $r['days'] <= 7
);

$pending = array_merge(
    array_values($expired),
    array_values($expiring)
);

$pending = array_slice($pending, 0, 7);

$in30 = array_values($expiring);

?>

<div class="pg">

    <!-- CABEÇALHO -->
    <div class="pg-hdr">
        <div>
            <div class="pg-title">Visão geral</div>
            <div class="pg-sub">30 de agosto de 2026</div>
        </div>

        <a href="<?= url(['page' => 'pending']) ?>" class="btn btn-secondary">
            <?= ico('alert', 14) ?>
            Ver todas as pendências
        </a>
    </div>


    <!-- KPIs -->
    <div class="kpi-grid">

        <!-- Funcionários -->
        <div class="kpi-card">
            <div class="kpi-icon navy">
                <?= ico('users', 15) ?>
            </div>

            <div class="kpi-val">
                <?= $totalFuncionarios ?>
            </div>

            <div class="kpi-lbl">
                Funcionários cadastrados
            </div>

            <div class="kpi-sub">
                <?= $funcionariosAtivos ?> ativos
            </div>
        </div>


        <!-- Treinamentos válidos -->
        <div class="kpi-card">
            <div class="kpi-icon green">
                <?= ico('check', 15) ?>
            </div>

            <div class="kpi-val">
                <?= $totalTreinamentos ?>
            </div>

            <div class="kpi-lbl">
                Treinamentos válidos
            </div>

            <div class="kpi-sub">
                certificações em dia
            </div>
        </div>


        <!-- Próximos do vencimento -->
        <div class="kpi-card">
            <div class="kpi-icon amber">
                <?= ico('clock', 15) ?>
            </div>

            <div class="kpi-val">
                <?= count($expiring) ?>
            </div>

            <div class="kpi-lbl">
                Próximos do vencimento
            </div>

            <div class="kpi-sub">
                <?= count($in7) ?> vencem em até 7 dias
            </div>
        </div>


        <!-- Vencidos -->
        <div class="kpi-card">
            <div class="kpi-icon red">
                <?= ico('alert', 15) ?>
            </div>

            <div class="kpi-val">
                <?= count($expired) ?>
            </div>

            <div class="kpi-lbl">
                Treinamentos vencidos
            </div>

            <div class="kpi-sub">
                requerem renovação imediata
            </div>
        </div>

    </div>


    <!-- CONTEÚDO PRINCIPAL -->
    <div class="dash-grid">

        <!-- PENDÊNCIAS -->
        <div class="tbl-wrap">

            <div class="card-hdr">
                <div>
                    <div class="card-title">
                        Pendências de treinamento
                    </div>

                    <div class="card-sub">
                        <?= count($expired) + count($expiring) ?>
                        pendências identificadas
                    </div>
                </div>

                <a href="<?= url(['page' => 'pending']) ?>" class="card-link">
                    Ver todas →
                </a>
            </div>


            <table class="data-tbl">

                <thead>
                    <tr>
                        <th>Funcionário</th>
                        <th>Treinamento</th>
                        <th>Validade</th>
                        <th>Situação</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($pending as $r): ?>

                        <tr class="<?= $r['status'] === 'expired' ? 'row-exp' : '' ?>">

                            <td>
                                <div style="display:flex;align-items:center;gap:10px">

                                    <span class="avatar av-sm">
                                        <?= initials($r['emp_name']) ?>
                                    </span>

                                    <div>

                                        <div class="cell-primary">
                                            <?= h(
                                                implode(
                                                    ' ',
                                                    array_slice(
                                                        explode(' ', $r['emp_name']),
                                                        0,
                                                        2
                                                    )
                                                )
                                            ) ?>
                                        </div>

                                        <div class="cell-secondary">
                                            <?= h($r['emp_role']) ?>
                                        </div>

                                    </div>

                                </div>
                            </td>


                            <td style="font-size:12px;font-weight:500">
                                <?= h($r['training']) ?>
                            </td>


                            <td class="cell-mono">
                                <?= h($r['expiry']) ?>
                            </td>


                            <td>
                                <?= status_badge($r['status']) ?>
                            </td>


                            <td>
                                <a
                                    href="<?= url([
                                        'page' => 'employee-profile',
                                        'id' => $r['emp_id']
                                    ]) ?>"
                                    class="btn-link"
                                >
                                    Ver perfil
                                </a>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>


        <!-- PRÓXIMOS VENCIMENTOS -->
        <div class="card">

            <div class="card-hdr">

                <div>

                    <div class="card-title">
                        Próximos vencimentos
                    </div>

                    <div class="card-sub">
                        Próximos 30 dias
                    </div>

                </div>

            </div>


            <?php if (empty($in30)): ?>

                <div style="padding:48px 20px;text-align:center;color:#94a3b8;font-size:13px">

                    <?= ico('check', 24) ?>

                    <br><br>

                    Nenhum vencimento próximo

                </div>

            <?php else: ?>

                <?php foreach ($in30 as $r): ?>

                    <div class="upcoming-item">

                        <div style="min-width:0;flex:1">

                            <div class="up-name">

                                <?= h(
                                    implode(
                                        ' ',
                                        [
                                            explode(' ', $r['emp_name'])[0],
                                            array_slice(
                                                explode(' ', $r['emp_name']),
                                                -1
                                            )[0]
                                        ]
                                    )
                                ) ?>

                            </div>

                            <div class="up-training">
                                <?= h($r['training']) ?>
                            </div>

                        </div>


                        <span class="up-days <?= $r['days'] <= 7 ? 'urg' : 'warn' ?>">
                            <?= $r['days'] ?>d
                        </span>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

</div>