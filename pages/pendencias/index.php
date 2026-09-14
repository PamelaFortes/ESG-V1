<?php

$all_expired = array_values(
    array_filter(
        $records,
        fn($r) => $r['status'] === 'expired'
    )
);

$all_expiring = array_values(
    array_filter(
        $records,
        fn($r) => $r['status'] === 'expiring'
    )
);

$in7 = array_filter(
    $all_expiring,
    fn($r) => $r['days'] <= 7
);

$in30 = $all_expiring;

$all_pending = array_merge(
    $all_expired,
    $all_expiring
);


$sectors = array_unique(
    array_column($records, 'emp_sector')
);

sort($sectors);


$trainings_list = array_unique(
    array_column($records, 'training')
);

sort($trainings_list);


$displayed = match ($tab) {

    'expired' => $all_expired,

    'expiring' => $all_expiring,

    default => $all_pending
};


if ($s_sect) {
    $displayed = array_values(
        array_filter(
            $displayed,
            fn($r) => $r['emp_sector'] === $s_sect
        )
    );
}


if ($s_train) {
    $displayed = array_values(
        array_filter(
            $displayed,
            fn($r) => $r['training'] === $s_train
        )
    );
}

?>


<div class="pg">

  <div class="pg-hdr">

    <div>

      <div class="pg-title">
        Pendências
      </div>

      <div class="pg-sub">
        Treinamentos vencidos ou com renovação próxima
      </div>

    </div>

  </div>


  <!-- Summary cards -->

  <div class="summary-grid">

    <div class="sc sc-red">

      <div class="sc-icon-row">

        <div class="sc-icon red">
          <?= ico('x', 13) ?>
        </div>

        <span class="sc-lbl">
          Vencidos
        </span>

      </div>

      <div class="sc-val">
        <?= count($all_expired) ?>
      </div>

      <div class="sc-meta">
        renovação imediata necessária
      </div>

    </div>


    <div class="sc sc-amber">

      <div class="sc-icon-row">

        <div class="sc-icon amber">
          <?= ico('alert', 13) ?>
        </div>

        <span class="sc-lbl">
          Vencem em 7 dias
        </span>

      </div>

      <div class="sc-val">
        <?= count($in7) ?>
      </div>

      <div class="sc-meta">
        ação urgente recomendada
      </div>

    </div>


    <div class="sc sc-orange">

      <div class="sc-icon-row">

        <div class="sc-icon orange">
          <?= ico('clock', 13) ?>
        </div>

        <span class="sc-lbl">
          Vencem em 30 dias
        </span>

      </div>

      <div class="sc-val">
        <?= count($in30) ?>
      </div>

      <div class="sc-meta">
        agendar renovação
      </div>

    </div>

  </div>


  <!-- Filters -->

  <form
    method="GET"
    action=""
    class="filter-bar"
    style="margin-bottom:16px"
  >

    <input
      type="hidden"
      name="page"
      value="pending"
    >


    <div class="tab-bar">

      <?php foreach (
          [
              ['all', 'Todos (' . count($all_pending) . ')'],
              ['expired', 'Vencidos (' . count($all_expired) . ')'],
              ['expiring', 'Próximos (' . count($all_expiring) . ')']
          ]
          as [$v, $lbl]
      ): ?>

        <button
          type="submit"
          name="tab"
          value="<?= $v ?>"
          class="tab-btn <?= $tab === $v ? 'active' : '' ?>"
        >
          <?= h($lbl) ?>
        </button>

      <?php endforeach; ?>

    </div>


    <select
      name="sector"
      class="filter-sel"
      onchange="this.form.submit()"
    >

      <option value="">
        Todos os setores
      </option>

      <?php foreach ($sectors as $s): ?>

        <option
          value="<?= h($s) ?>"
          <?= $s_sect === $s ? 'selected' : '' ?>
        >
          <?= h($s) ?>
        </option>

      <?php endforeach; ?>

    </select>


    <select
      name="training"
      class="filter-sel"
      onchange="this.form.submit()"
    >

      <option value="">
        Todos os treinamentos
      </option>

      <?php foreach ($trainings_list as $t): ?>

        <option
          value="<?= h($t) ?>"
          <?= $s_train === $t ? 'selected' : '' ?>
        >
          <?= h($t) ?>
        </option>

      <?php endforeach; ?>

    </select>


    <?php if ($s_sect || $s_train): ?>

      <a
        href="<?= url(['page' => 'pending', 'tab' => $tab]) ?>"
        class="btn btn-secondary btn-sm"
      >
        <?= ico('x', 12) ?>
        Limpar
      </a>

    <?php endif; ?>

  </form>


  <!-- Table -->

  <div class="tbl-wrap">

    <table class="data-tbl">

      <thead>

        <tr>

          <th>Funcionário</th>
          <th>Treinamento</th>
          <th>Setor</th>
          <th>Realização</th>
          <th>Validade</th>
          <th>Dias restantes</th>
          <th>Situação</th>
          <th>Ação</th>

        </tr>

      </thead>


      <tbody>

        <?php if (empty($displayed)): ?>

          <tr>

            <td
              colspan="8"
              class="tbl-empty"
            >
              Nenhuma pendência encontrada com os filtros selecionados.
            </td>

          </tr>

        <?php else: ?>

          <?php foreach ($displayed as $r): ?>

            <tr
              class="<?= $r['status'] === 'expired' ? 'row-exp' : '' ?>"
            >

              <td>

                <div
                  style="
                    display:flex;
                    align-items:center;
                    gap:10px
                  "
                >

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


              <td
                style="
                  font-size:12px;
                  font-weight:500;
                  color:#1e293b
                "
              >
                <?= h($r['training']) ?>
              </td>


              <td
                style="
                  font-size:12px;
                  color:#64748b
                "
              >
                <?= h($r['emp_sector']) ?>
              </td>


              <td class="cell-mono">
                <?= h($r['done']) ?>
              </td>


              <td class="cell-mono">
                <?= h($r['expiry']) ?>
              </td>


              <td>

                <?php if ($r['days'] < 0): ?>

                  <span class="days-exp">
                    <?= abs($r['days']) ?>d atraso
                  </span>

                <?php elseif ($r['days'] <= 7): ?>

                  <span class="days-urg">
                    <?= $r['days'] ?>d
                  </span>

                <?php else: ?>

                  <span class="days-ok">
                    <?= $r['days'] ?>d
                  </span>

                <?php endif; ?>

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
                  Ver perfil →
                </a>

              </td>

            </tr>

          <?php endforeach; ?>

        <?php endif; ?>

      </tbody>

    </table>


    <?php if (!empty($displayed)): ?>

      <div class="tbl-footer">

        <?= count($displayed) ?>

        <?= count($displayed) === 1
            ? 'pendência encontrada'
            : 'pendências encontradas'
        ?>

      </div>

    <?php endif; ?>

  </div>

</div>