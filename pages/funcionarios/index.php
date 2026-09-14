<?php

require_once __DIR__ . '/../../includes/functions.php';

$roles = array_unique(array_column($employees, 'role'));
sort($roles);

$filtered = $employees;

if ($s_q) {
    $filtered = array_filter(
        $filtered,
        fn($e) =>
            stripos($e['name'], $s_q) !== false ||
            strpos($e['cpf'], $s_q) !== false ||
            stripos($e['sector'], $s_q) !== false
    );
}

if ($s_role) {
    $filtered = array_filter(
        $filtered,
        fn($e) => $e['role'] === $s_role
    );
}

if ($s_stat) {
    $filtered = array_filter(
        $filtered,
        fn($e) => $e['status'] === $s_stat
    );
}

$filtered = array_values($filtered);

?>

<div class="pg">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Funcionários</div>
      <div class="pg-sub"><?= count($employees) ?> colaboradores cadastrados</div>
    </div>

    <a href="<?= url(['page' => 'employee-form']) ?>" class="btn btn-primary">
      <?= ico('plus', 13) ?> Novo funcionário
    </a>
  </div>

  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="employees">

    <div class="filter-input">
      <span class="filter-ico"><?= ico('search', 13) ?></span>
      <input
        type="text"
        name="q"
        placeholder="Buscar por nome, CPF ou setor..."
        value="<?= h($s_q) ?>"
      >
    </div>

    <select name="role" class="filter-sel" onchange="this.form.submit()">
      <option value="">Todos os cargos</option>

      <?php foreach ($roles as $r): ?>
        <option
          value="<?= h($r) ?>"
          <?= $s_role === $r ? 'selected' : '' ?>
        >
          <?= h($r) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="stat" class="filter-sel" onchange="this.form.submit()">
      <option value="">Todas as situações</option>
      <option value="active" <?= $s_stat === 'active' ? 'selected' : '' ?>>
        Ativo
      </option>
      <option value="inactive" <?= $s_stat === 'inactive' ? 'selected' : '' ?>>
        Inativo
      </option>
    </select>

    <button type="submit" class="btn btn-secondary btn-sm">
      Filtrar
    </button>
  </form>

  <div class="tbl-wrap">
    <table class="data-tbl">
      <thead>
        <tr>
          <th>Funcionário</th>
          <th>CPF</th>
          <th>Cargo / Setor</th>
          <th>Treinamentos</th>
          <th>Situação</th>
          <th>Ações</th>
        </tr>
      </thead>

      <tbody>

        <?php if (empty($filtered)): ?>

          <tr>
            <td colspan="6" class="tbl-empty">
              Nenhum funcionário encontrado.
              Tente ajustar os filtros.
            </td>
          </tr>

        <?php else: ?>

          <?php foreach ($filtered as $e): ?>

            <tr>

              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <span class="avatar av-md">
                    <?= initials($e['name']) ?>
                  </span>

                  <div>
                    <div class="cell-primary">
                      <?= h($e['name']) ?>
                    </div>

                    <div class="cell-secondary">
                      <?= h($e['email']) ?>
                    </div>
                  </div>
                </div>
              </td>

              <td class="cell-mono">
                <?= h($e['cpf']) ?>
              </td>

              <td>
                <div style="font-size:12px;font-weight:500;color:#1e293b">
                  <?= h($e['role']) ?>
                </div>

                <div class="cell-secondary">
                  <?= h($e['sector']) ?>
                </div>
              </td>

              <td>
                <div class="t-stats">
                  <span class="t-dot t-green">
                    <?= $e['t_valid'] ?>
                  </span>

                  <?php if ($e['t_expiring'] > 0): ?>
                    <span class="t-dot t-amber">
                      <?= $e['t_expiring'] ?>
                    </span>
                  <?php endif; ?>

                  <?php if ($e['t_expired'] > 0): ?>
                    <span class="t-dot t-red">
                      <?= $e['t_expired'] ?>
                    </span>
                  <?php endif; ?>
                </div>
              </td>

              <td>
                <?= emp_badge($e['status']) ?>
              </td>

              <td>
                <div style="display:flex;gap:4px">

                  <a
                    href="<?= url([
                        'page' => 'employee-profile',
                        'id' => $e['id']
                    ]) ?>"
                    class="btn-icon blue"
                    title="Ver perfil"
                  >
                    <?= ico('eye', 14) ?>
                  </a>

                  <a
                    href="<?= url([
                        'page' => 'employee-form',
                        'id' => $e['id']
                    ]) ?>"
                    class="btn-icon"
                    title="Editar"
                  >
                    <?= ico('edit', 14) ?>
                  </a>

                </div>
              </td>

            </tr>

          <?php endforeach; ?>

        <?php endif; ?>

      </tbody>
    </table>

    <?php if (!empty($filtered)): ?>

      <div class="tbl-footer">
        Mostrando <?= count($filtered) ?>
        de <?= count($employees) ?> funcionários
      </div>

    <?php endif; ?>

  </div>
</div>