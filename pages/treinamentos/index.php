<?php
?>

<div class="pg">

  <div class="pg-hdr">

    <div>
      <div class="pg-title">Treinamentos</div>

      <div class="pg-sub">
        <?= count($trainings) ?> tipos de treinamento cadastrados
      </div>
    </div>

    <a
      href="<?= url(['page' => 'training-form']) ?>"
      class="btn btn-primary"
    >
      <?= ico('plus', 13) ?> Novo treinamento
    </a>

  </div>


  <div class="tbl-wrap">

    <table class="data-tbl">

      <thead>
        <tr>
          <th>Nome do treinamento</th>
          <th>Descrição</th>
          <th>C.H.</th>
          <th>Validade</th>
          <th>Funcionários</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>

      <tbody>

        <?php foreach ($trainings as $t): ?>

        <tr>

          <td
            style="font-weight:600;font-size:12px;color:#1e293b"
          >
            <?= h($t['name']) ?>
          </td>


          <td
            style="font-size:12px;color:#94a3b8;max-width:220px;white-space:normal"
          >
            <?= h($t['desc']) ?>
          </td>


          <td
            style="font-size:12px;font-weight:500"
          >
            <?= $t['hours'] ?>h
          </td>


          <td
            style="font-size:12px"
          >
            <?= $t['validity'] ?> meses
          </td>


          <td>

            <span
              style="
                display:inline-flex;
                align-items:center;
                justify-content:center;
                width:28px;
                height:28px;
                border-radius:50%;
                background:#f1f5f9;
                font-size:12px;
                font-weight:700;
                color:#334155
              "
            >
              <?= $t['employees'] ?>
            </span>

          </td>


          <td>

            <?php if ($t['status'] === 'active'): ?>

              <span class="bdg bdg-valid">
                Ativo
              </span>

            <?php else: ?>

              <span class="bdg bdg-inactive">
                Inativo
              </span>

            <?php endif; ?>

          </td>


          <td>

            <div style="display:flex;gap:4px">

              <button class="btn-icon blue">
                <?= ico('edit', 13) ?>
              </button>

              <button class="btn-icon red">
                <?= ico('trash', 13) ?>
              </button>

            </div>

          </td>

        </tr>

        <?php endforeach; ?>

      </tbody>

    </table>

  </div>

</div>