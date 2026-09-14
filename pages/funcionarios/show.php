<?php

require_once __DIR__ . '/../../includes/functions.php';

$emp = find_emp($employees, $emp_id);

$emp_records = array_values(
    array_filter(
        $records,
        fn($r) => $r['emp_id'] === $emp['id']
    )
);
?>

<div class="pg">

  <!-- Profile header -->
  <div class="profile-hdr">
    <div class="profile-inner">

      <span class="avatar av-lg">
        <?= initials($emp['name']) ?>
      </span>

      <div class="profile-info">

        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px">

          <div>
            <div class="profile-name">
              <?= h($emp['name']) ?>
            </div>

            <div class="profile-meta">
              <span class="profile-meta-txt">
                <?= h($emp['role']) ?>
              </span>

              <span class="profile-meta-sep">·</span>

              <span class="profile-meta-txt">
                <?= h($emp['sector']) ?>
              </span>

              <span class="profile-meta-sep">·</span>

              <span class="profile-meta-tag">
                <?= h($emp['registration']) ?>
              </span>
            </div>
          </div>

          <div class="profile-actions">

            <?= emp_badge($emp['status']) ?>

            <a
              href="<?= url(['page'=>'register-training','id'=>$emp['id']]) ?>"
              class="btn btn-primary btn-sm"
            >
              <?= ico('plus', 12) ?> Registrar treinamento
            </a>

          </div>

        </div>

        <div class="profile-stats">

          <div class="profile-stat ps-all">
            <div class="ps-val"><?= $emp['t_total'] ?></div>
            <div class="ps-lbl">Total</div>
          </div>

          <div class="profile-stat ps-green">
            <div class="ps-val"><?= $emp['t_valid'] ?></div>
            <div class="ps-lbl">Válidos</div>
          </div>

          <div class="profile-stat ps-amber">
            <div class="ps-val"><?= $emp['t_expiring'] ?></div>
            <div class="ps-lbl">Próximos do vencimento</div>
          </div>

          <div class="profile-stat ps-red">
            <div class="ps-val"><?= $emp['t_expired'] ?></div>
            <div class="ps-lbl">Vencidos</div>
          </div>

        </div>

      </div>
    </div>
  </div>

  <!-- Info cards -->
  <div class="info-grid">

    <div class="info-card">

      <div class="info-card-title">
        Dados pessoais
      </div>

      <?php foreach (
        [
          ['CPF', $emp['cpf']],
          ['E-mail', $emp['email']],
          ['Telefone', $emp['phone']],
          ['Nascimento', $emp['dob']]
        ] as [$l, $v]
      ): ?>

        <div class="info-row">
          <span class="info-lbl"><?= h($l) ?></span>
          <span class="info-val"><?= h($v) ?></span>
        </div>

      <?php endforeach; ?>

    </div>

    <div class="info-card">

      <div class="info-card-title">
        Dados profissionais
      </div>

      <?php foreach (
        [
          ['Cargo', $emp['role']],
          ['Setor', $emp['sector']],
          ['Admissão', $emp['admission']],
          ['Matrícula', $emp['registration']]
        ] as [$l, $v]
      ): ?>

        <div class="info-row">
          <span class="info-lbl"><?= h($l) ?></span>
          <span class="info-val"><?= h($v) ?></span>
        </div>

      <?php endforeach; ?>

    </div>

  </div>

  <!-- Training history -->
  <div class="tbl-wrap">

    <div class="card-hdr">

      <div>
        <div class="card-title">
          Histórico de treinamentos
        </div>
      </div>

      <a
        href="<?= url(['page'=>'register-training','id'=>$emp['id']]) ?>"
        class="card-link"
        style="display:flex;align-items:center;gap:4px"
      >
        <?= ico('plus', 12) ?> Registrar treinamento
      </a>

    </div>

    <?php if (empty($emp_records)): ?>

      <div class="tbl-empty">
        Nenhum treinamento registrado para este funcionário.
      </div>

    <?php else: ?>

      <table class="data-tbl">

        <thead>
          <tr>
            <th>Treinamento</th>
            <th>Data de realização</th>
            <th>Data de validade</th>
            <th>Situação</th>
            <th>Ações</th>
          </tr>
        </thead>

        <tbody>

          <?php foreach ($emp_records as $r): ?>

            <tr class="<?= $r['status'] === 'expired' ? 'row-exp' : '' ?>">

              <td style="font-weight:600;font-size:12px;color:#1e293b">
                <?= h($r['training']) ?>
              </td>

              <td class="cell-mono">
                <?= h($r['done']) ?>
              </td>

              <td class="cell-mono">
                <?= h($r['expiry']) ?>
              </td>

              <td>
                <?= status_badge($r['status']) ?>
              </td>

              <td>
                <button class="btn-icon" title="Editar">
                  <?= ico('edit', 13) ?>
                </button>
              </td>

            </tr>

          <?php endforeach; ?>

        </tbody>

      </table>

    <?php endif; ?>

  </div>

</div>