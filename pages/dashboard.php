<?php

function calcularTotalFuncionarios($employees) {
    return count($employees);
}

function calcularTotalTreinamentos($trainings) {
    return count($trainings);
}

function calcularTreinamentosPendentes($records) {
    $total = 0;

    foreach ($records as $record) {
        if ($record['status'] === 'expiring') {
            $total++;
        }
    }

    return $total;
}

function calcularTreinamentosVencidos($records) {
    $total = 0;

    foreach ($records as $record) {
        if ($record['status'] === 'expired') {
            $total++;
        }
    }

    return $total;
}

// cálculos do dashboard
$totalFuncionarios = calcularTotalFuncionarios($employees);
$totalTreinamentos = calcularTotalTreinamentos($trainings);//AINDA não existe um card no Dashboard atual mostrando o total de treinamentos cadastrados. 

$treinamentosPendentes = calcularTreinamentosPendentes($records);
$treinamentosVencidos = calcularTreinamentosVencidos($records);

// ====================================================================
// DASHBOARD
// ====================================================================
if ($page === 'dashboard'):
  $expired  = array_filter($records, fn($r) => $r['status'] === 'expired');
  $expiring = array_filter($records, fn($r) => $r['status'] === 'expiring');
  $valid    = array_filter($records, fn($r) => $r['status'] === 'valid');
  $in7      = array_filter($expiring, fn($r) => $r['days'] <= 7);
  $pending  = array_merge(array_values($expired), array_values($expiring));
  $pending  = array_slice($pending, 0, 7);
  $in30     = array_values($expiring);
?>
<div class="pg">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Visão geral</div>
      <div class="pg-sub">30 de agosto de 2026</div>
    </div>
    <a href="<?= url(['page'=>'pending']) ?>" class="btn btn-secondary">
      <?= ico('alert', 14) ?> Ver todas as pendências
    </a>
  </div>

  <!-- KPIs -->
  <div class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-icon navy"><?= ico('users', 15) ?></div>
      <div class="kpi-val"><?= $totalFuncionarios ?></div>
      <div class="kpi-lbl">Funcionários cadastrados</div>
      <div class="kpi-sub"><?= count(array_filter($employees, fn($e) => $e['status']==='active')) ?> ativos</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon green"><?= ico('check', 15) ?></div>
      <div class="kpi-val"><?= count($valid) ?></div>
      <div class="kpi-lbl">Treinamentos válidos</div>
      <div class="kpi-sub">certificações em dia</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon amber"><?= ico('clock', 15) ?></div>
      <div class="kpi-val"><?= count($expiring) ?></div>
      <div class="kpi-lbl">Próximos do vencimento</div>
      <div class="kpi-sub"><?= count($in7) ?> vencem em até 7 dias</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon red"><?= ico('alert', 15) ?></div>
      <div class="kpi-val"><?= count($expired) ?></div>
      <div class="kpi-lbl">Treinamentos vencidos</div>
      <div class="kpi-sub">requerem renovação imediata</div>
    </div>
  </div>

  <!-- Main + Sidebar -->
  <div class="dash-grid">
    <!-- Pending table -->
    <div class="tbl-wrap">
      <div class="card-hdr">
        <div>
          <div class="card-title">Pendências de treinamento</div>
          <div class="card-sub"><?= count($expired)+count($expiring) ?> pendências identificadas</div>
        </div>
        <a href="<?= url(['page'=>'pending']) ?>" class="card-link">Ver todas →</a>
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
          <tr class="<?= $r['status']==='expired' ? 'row-exp' : '' ?>">
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                <span class="avatar av-sm"><?= initials($r['emp_name']) ?></span>
                <div>
                  <div class="cell-primary"><?= h(implode(' ', array_slice(explode(' ',$r['emp_name']),0,2))) ?></div>
                  <div class="cell-secondary"><?= h($r['emp_role']) ?></div>
                </div>
              </div>
            </td>
            <td style="font-size:12px;font-weight:500"><?= h($r['training']) ?></td>
            <td class="cell-mono"><?= h($r['expiry']) ?></td>
            <td><?= status_badge($r['status']) ?></td>
            <td><a href="<?= url(['page'=>'employee-profile','id'=>$r['emp_id']]) ?>" class="btn-link">Ver perfil</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Upcoming -->
    <div class="card">
      <div class="card-hdr">
        <div>
          <div class="card-title">Próximos vencimentos</div>
          <div class="card-sub">Próximos 30 dias</div>
        </div>
      </div>
      <?php if (empty($in30)): ?>
        <div style="padding:48px 20px;text-align:center;color:#94a3b8;font-size:13px">
          <?= ico('check', 24) ?><br><br>Nenhum vencimento próximo
        </div>
      <?php else: ?>
        <?php foreach ($in30 as $r): ?>
        <div class="upcoming-item">
          <div style="min-width:0;flex:1">
            <div class="up-name"><?= h(implode(' ', [explode(' ',$r['emp_name'])[0], array_slice(explode(' ',$r['emp_name']),-1)[0]])) ?></div>
            <div class="up-training"><?= h($r['training']) ?></div>
          </div>
          <span class="up-days <?= $r['days'] <= 7 ? 'urg' : 'warn' ?>"><?= $r['days'] ?>d</span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
