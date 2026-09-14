<?php


require_once __DIR__ . '/config/funcionarios.php';
require_once __DIR__ . '/config/treinamentos.php';
require_once __DIR__ . '/config/registro_treinamento.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';


if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'register_training'
) {

    require_once __DIR__ . '/config/database.php';

    $funcionario_id = (int)($_POST['funcionario_id'] ?? 0);
    $treinamento_id = (int)($_POST['treinamento_id'] ?? 0);
    $data_realizacao = $_POST['data_realizacao'] ?? '';
    $data_validade = $_POST['data_validade'] ?? '';
    $observacoes = trim($_POST['observacoes'] ?? '');

    if (
        $funcionario_id > 0 &&
        $treinamento_id > 0 &&
        $data_realizacao !== '' &&
        $data_validade !== ''
    ) {

        $sql = "
            INSERT INTO registros_treinamento
            (
                funcionario_id,
                treinamento_id,
                data_realizacao,
                data_validade,
                observacoes
            )
            VALUES
            (
                :funcionario_id,
                :treinamento_id,
                :data_realizacao,
                :data_validade,
                :observacoes
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'funcionario_id' => $funcionario_id,
            'treinamento_id' => $treinamento_id,
            'data_realizacao' => $data_realizacao,
            'data_validade' => $data_validade,
            'observacoes' => $observacoes
        ]);

        header(
            'Location: ?page=employee-profile&id=' . $funcionario_id
        );
        exit;
    }
}
$emp_id      = (int)($_GET['id'] ?? 1);
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'save_employee'
) {

    require_once __DIR__ . '/config/database.php';

    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $setor = trim($_POST['setor'] ?? '');
    $data_admissao = $_POST['data_admissao'] ?? '';
    $status = $_POST['status'] ?? 'active';

    if (
        $nome !== '' &&
        $cpf !== '' &&
        $cargo !== '' &&
        $setor !== '' &&
        $data_admissao !== ''
    ) {

        // O sistema usa active/inactive,
        // mas o banco usa ativo/inativo.
        $statusBanco = $status === 'active'
            ? 'ativo'
            : 'inativo';

        $sql = "
            INSERT INTO funcionarios
            (
                nome,
                cpf,
                email,
                telefone,
                cargo,
                setor,
                data_admissao,
                status
            )
            VALUES
            (
                :nome,
                :cpf,
                :email,
                :telefone,
                :cargo,
                :setor,
                :data_admissao,
                :status
            )
        ";

        $stmt = $pdo->prepare($sql);

        try {

          $stmt->execute([
              'nome' => $nome,
              'cpf' => $cpf,
              'email' => $email !== '' ? $email : null,
              'telefone' => $telefone !== '' ? $telefone : null,
              'cargo' => $cargo,
              'setor' => $setor,
              'data_admissao' => $data_admissao,
              'status' => $statusBanco
          ]);

          header('Location: ?page=employee-form&saved=1');
          exit;

      } catch (PDOException $e) {

          if ($e->getCode() === '23505') {
              $error = 'CPF já cadastrado. Verifique os dados e tente novamente.';
          } else {
              $error = 'Erro ao cadastrar funcionário.';
          }
      }
    }
}
$tab         = $_GET['tab'] ?? 'all';
$s_sect      = $_GET['sector'] ?? '';
$s_train     = $_GET['training'] ?? '';
$s_role      = $_GET['role'] ?? '';
$s_stat      = $_GET['stat'] ?? '';
$s_q         = $_GET['q'] ?? '';
$sess_role   = $_SESSION['role'] ?? '';
$sess_name   = $_SESSION['user_name'] ?? '';
$sess_emp_id = (int)($_SESSION['emp_id'] ?? 0);

// Páginas de funcionário (acesso restrito ao próprio perfil)
$employee_pages = ['meu-perfil'];
// Páginas de gestor
$manager_pages  = ['dashboard','employees','employee-form','employee-profile','trainings','training-form','register-training','pending'];

if ($page !== 'login' && empty($_SESSION['logged_in'])) {
    header('Location: ?page=login'); exit;
}
// Funcionário tentando acessar páginas de gestor → redireciona
if ($sess_role === 'funcionario' && in_array($page, $manager_pages)) {
    header('Location: ?page=meu-perfil'); exit;
}
// Gestor tentando acessar páginas de funcionário → redireciona
if ($sess_role === 'gestor' && in_array($page, $employee_pages)) {
    header('Location: ?page=dashboard'); exit;
}



$active_nav = active_nav($page);

// ── Breadcrumb map ────────────────────────────────────────────────────────
$breadcrumbs = [
    'dashboard'         => [['Dashboard', null]],
    'employees'         => [['Funcionários', null]],
    'employee-form'     => [['Funcionários', url(['page'=>'employees'])], ['Novo funcionário', null]],
    'employee-profile'  => [['Funcionários', url(['page'=>'employees'])], ['Perfil do funcionário', null]],
    'trainings'         => [['Treinamentos', null]],
    'training-form'     => [['Treinamentos', url(['page'=>'trainings'])], ['Novo treinamento', null]],
    'register-training' => [['Perfil', url(['page'=>'employee-profile','id'=>$emp_id])], ['Registrar treinamento', null]],
    'pending'           => [['Pendências', null]],
];

?><!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SafeTrack — Controle de Treinamentos</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
<?php if ($page === 'login'): ?>

<!-- ── LOGIN PAGE ─────────────────────────────────────────────────────── -->
<div class="login-wrap">
  <div class="login-left">
    <div class="login-logo">
      <div class="login-logo-icon"><?= ico('shield', 18) ?></div>
      <span class="login-logo-name">SafeTrack</span>
    </div>
    <div>
      <div class="login-label">Sistema de Gestão</div>
      <div class="login-title">Controle de<br>Treinamentos<br>e Segurança</div>
      <div class="login-desc">Gerencie certificações, monitore vencimentos e garanta a conformidade da sua equipe com uma ferramenta feita para o dia a dia.</div>
      <div class="login-stats">
        <div><div class="login-stat-v">48</div><div class="login-stat-l">Funcionários</div></div>
        <div><div class="login-stat-v">6</div><div class="login-stat-l">Treinamentos</div></div>
        <div><div class="login-stat-v">12</div><div class="login-stat-l">Alertas ativos</div></div>
      </div>
    </div>
    <div class="login-foot">© 2026 SafeTrack. Todos os direitos reservados.</div>
  </div>
  <div class="login-right">
    <div class="login-form-wrap">
      <div class="login-form-title">Bem-vindo de volta</div>
      <div class="login-form-sub">Acesse sua conta para continuar</div>

      <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-error"><?= ico('alert', 14) ?> E-mail ou senha incorretos.</div>
      <?php endif; ?>

      <form method="POST" action="" id="login-form">
        <input type="hidden" name="action" value="login">
        <div class="login-fg">
          <label class="login-lbl">E-mail <span style="color:#ef4444">*</span></label>
          <input type="email" name="email" id="login-email" class="login-inp" placeholder="seu@email.com" required>
        </div>
        <div class="login-fg">
          <label class="login-lbl">Senha <span style="color:#ef4444">*</span></label>
          <input type="password" name="password" id="login-password" class="login-inp" placeholder="••••••••" required>
        </div>
        <div class="login-row">
          <label class="login-check-lbl">
            <input type="checkbox" name="remember"> Lembrar-me
          </label>
          <a href="#" class="login-forgot">Esqueci minha senha</a>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="justify-content:center;padding:11px 16px;font-size:14px">
          Entrar no sistema
        </button>
      </form>

<script>
function fillLogin(email, pass) {
  document.getElementById('login-email').value = email;
  document.getElementById('login-password').value = pass;
  document.getElementById('login-form').submit();
}
</script>

<?php elseif ($page === 'meu-perfil'): ?>

<!-- ── PORTAL DO FUNCIONÁRIO ─────────────────────────────────────────── -->
<?php
$emp = find_emp($employees, $sess_emp_id);
$emp_records = array_values(array_filter($records, fn($r) => $r['emp_id'] === $emp['id']));
$my_expired  = array_filter($emp_records, fn($r) => $r['status'] === 'expired');
$my_expiring = array_filter($emp_records, fn($r) => $r['status'] === 'expiring');
?>
<div class="portal-wrap">
  <!-- Header -->
  <header class="portal-header">
    <div class="portal-header-logo">
      <div class="portal-header-icon"><?= ico('shield', 14) ?></div>
      <span class="portal-header-name">SafeTrack</span>
    </div>
    <div class="portal-header-right">
      <div class="portal-user-pill">
        <div class="portal-user-av"><?= initials($sess_name) ?></div>
        <span class="portal-user-name"><?= h($sess_name) ?></span>
        <span class="portal-role-tag">Funcionário</span>
      </div>
      <a href="?logout=1" class="portal-logout"><?= ico('logout', 14) ?> Sair</a>
    </div>
  </header>

  <div class="portal-body">

    <!-- Avisos urgentes -->
    <?php if (!empty($my_expired)): ?>
    <div class="portal-notice red">
      <span class="portal-notice-ico"><?= ico('alert', 16) ?></span>
      <div>
        <strong>Atenção:</strong> você possui <?= count($my_expired) ?> treinamento(s) vencido(s).
        Entre em contato com o setor de segurança do trabalho para agendar a renovação.
      </div>
    </div>
    <?php endif; ?>
    <?php if (!empty($my_expiring)): ?>
    <div class="portal-notice amber">
      <span class="portal-notice-ico"><?= ico('clock', 16) ?></span>
      <div>
        <strong>Aviso:</strong> você possui <?= count($my_expiring) ?> treinamento(s) com vencimento próximo.
        Fique atento ao prazo e aguarde o contato da empresa.
      </div>
    </div>
    <?php endif; ?>

    <!-- Card de boas-vindas + resumo -->
    <div class="portal-welcome">
      <div class="portal-welcome-inner">
        <div class="portal-av-lg"><?= initials($emp['name']) ?></div>
        <div style="flex:1;min-width:0">
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">
            <div>
              <div class="portal-emp-name"><?= h($emp['name']) ?></div>
              <div class="portal-emp-sub">
                <span><?= h($emp['role']) ?></span>
                <span class="sep">·</span>
                <span><?= h($emp['sector']) ?></span>
                <span class="sep">·</span>
                <span class="tag"><?= h($emp['registration']) ?></span>
              </div>
            </div>
            <?= emp_badge($emp['status']) ?>
          </div>
        </div>
      </div>
      <div class="portal-stats">
        <div class="pstat pstat-all">
          <div class="pstat-val"><?= $emp['t_total'] ?></div>
          <div class="pstat-lbl">Total de treinamentos</div>
        </div>
        <div class="pstat pstat-green">
          <div class="pstat-val"><?= $emp['t_valid'] ?></div>
          <div class="pstat-lbl">Válidos</div>
        </div>
        <div class="pstat pstat-amber">
          <div class="pstat-val"><?= $emp['t_expiring'] ?></div>
          <div class="pstat-lbl">Próximos do vencimento</div>
        </div>
        <div class="pstat pstat-red">
          <div class="pstat-val"><?= $emp['t_expired'] ?></div>
          <div class="pstat-lbl">Vencidos</div>
        </div>
      </div>
    </div>

    <!-- Dados pessoais e profissionais -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
      <div class="info-card">
        <div class="info-card-title">Dados pessoais</div>
        <?php foreach ([['E-mail',$emp['email']],['Telefone',$emp['phone']],['Nascimento',$emp['dob']]] as [$l,$v]): ?>
        <div class="info-row"><span class="info-lbl"><?= h($l) ?></span><span class="info-val"><?= h($v) ?></span></div>
        <?php endforeach; ?>
      </div>
      <div class="info-card">
        <div class="info-card-title">Dados profissionais</div>
        <?php foreach ([['Cargo',$emp['role']],['Setor',$emp['sector']],['Admissão',$emp['admission']],['Matrícula',$emp['registration']]] as [$l,$v]): ?>
        <div class="info-row"><span class="info-lbl"><?= h($l) ?></span><span class="info-val"><?= h($v) ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Histórico de treinamentos -->
    <div class="portal-section-title">Meus treinamentos</div>
    <div class="tbl-wrap">
      <?php if (empty($emp_records)): ?>
      <div class="tbl-empty">Nenhum treinamento registrado até o momento.</div>
      <?php else: ?>
      <table class="data-tbl">
        <thead>
          <tr>
            <th>Treinamento</th>
            <th>Data de realização</th>
            <th>Data de validade</th>
            <th>Dias restantes</th>
            <th>Situação</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($emp_records as $r): ?>
          <tr class="<?= $r['status']==='expired' ? 'row-exp' : '' ?>">
            <td style="font-weight:600;font-size:12px;color:#1e293b"><?= h($r['training']) ?></td>
            <td class="cell-mono"><?= h($r['done']) ?></td>
            <td class="cell-mono"><?= h($r['expiry']) ?></td>
            <td>
              <?php if ($r['days'] < 0): ?>
                <span class="days-exp"><?= abs($r['days']) ?>d de atraso</span>
              <?php elseif ($r['days'] <= 7): ?>
                <span class="days-urg"><?= $r['days'] ?>d</span>
              <?php else: ?>
                <span class="days-ok"><?= $r['days'] ?>d</span>
              <?php endif; ?>
            </td>
            <td><?= status_badge($r['status']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="tbl-footer">
        <?= count($emp_records) ?> treinamento(s) registrado(s) — atualizado em 30/08/2026
      </div>
      <?php endif; ?>
    </div>

    <div style="margin-top:20px;padding:16px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;font-size:12px;color:#94a3b8;text-align:center">
      Para solicitar correções nos seus dados ou registrar um novo treinamento, entre em contato com o setor de Segurança do Trabalho.
    </div>
  </div>
</div>

<?php else: ?>

<!-- ── APP SHELL (GESTOR) ─────────────────────────────────────────────── -->
<div class="app">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sb-logo">
      <div class="sb-logo-icon"><?= ico('shield', 15) ?></div>
      <div>
        <div class="sb-logo-name">SafeTrack</div>
        <div class="sb-logo-sub">Segurança do Trabalho</div>
      </div>
    </div>
    <nav class="sb-nav">
      <div class="sb-section">Menu</div>
      <?php
      $nav_items = [
        ['id'=>'dashboard','label'=>'Dashboard','icon'=>'dashboard'],
        ['id'=>'employees','label'=>'Funcionários','icon'=>'users'],
        ['id'=>'trainings','label'=>'Treinamentos','icon'=>'book'],
        ['id'=>'pending','label'=>'Pendências','icon'=>'alert'],
        ['id'=>'reports','label'=>'Relatórios','icon'=>'chart','disabled'=>true],
      ];
      foreach ($nav_items as $ni):
        $is_active = $active_nav === $ni['id'];
        $is_dis = !empty($ni['disabled']);
        $cls = 'nav-item' . ($is_active ? ' active' : '') . ($is_dis ? ' disabled' : '');
        $href = $is_dis ? '#' : url(['page'=>$ni['id']]);
      ?>
      <a href="<?= h($href) ?>" class="<?= $cls ?>">
        <?= ico($ni['icon'], 15) ?>
        <?= h($ni['label']) ?>
        <?php if ($is_active): ?><span class="nav-dot"></span><?php endif; ?>
        <?php if ($is_dis): ?><span class="nav-soon">Em breve</span><?php endif; ?>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="sb-user">
      <a href="?logout=1" class="sb-user-inner" style="text-decoration:none">
        <div class="sb-avatar"><?= initials($sess_name) ?></div>
        <div style="min-width:0;flex:1">
          <div class="sb-user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= h($sess_name) ?></div>
          <div class="sb-user-role"><?= $sess_role === 'gestor' ? 'Gestor' : 'Funcionário' ?></div>
        </div>
        <div class="sb-user-icon"><?= ico('logout', 13) ?></div>
      </a>
    </div>
  </aside>

  <!-- Main -->
  <div class="app-main">

    <!-- Topbar -->
    <header class="topbar">
      <div class="topbar-bc">
        <?php if (!empty($breadcrumbs[$page])): ?>
          <?php $bc = $breadcrumbs[$page]; $last = count($bc)-1; ?>
          <?php foreach ($bc as $i => [$lbl, $href]): ?>
            <?php if ($i < $last): ?>
              <a href="<?= h($href) ?>"><?= h($lbl) ?></a>
              <span class="sep"><?= ico('chevron', 13, '') ?></span>
            <?php else: ?>
              <span class="cur"><?= h($lbl) ?></span>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="topbar-right">
        <form method="GET" action="" class="search-wrap" style="display:flex">
          <input type="hidden" name="page" value="<?= h($page) ?>">
          <span class="search-ico"><?= ico('search', 13) ?></span>
          <input type="text" name="q" placeholder="Buscar..." value="<?= h($s_q) ?>">
        </form>
        <button class="tb-btn">
          <?= ico('bell', 16) ?>
          <span class="notif-dot"></span>
        </button>
      </div>
    </header>

    <!-- Page Content -->
    <main class="app-content">

<?php

// DASHBOARD
if ($page === 'dashboard'):

    require_once __DIR__ . '/pages/dashboard.php';


// EMPLOYEES LIST
elseif ($page === 'employees'):

    require_once __DIR__ . '/pages/funcionarios/index.php';


// EMPLOYEE FORM
elseif ($page === 'employee-form'):

    require_once __DIR__ . '/pages/funcionarios/create.php';


// EMPLOYEE PROFILE
elseif ($page === 'employee-profile'):

    require_once __DIR__ . '/pages/funcionarios/show.php';


// TRAININGS LIST
elseif ($page === 'trainings'):

    require_once __DIR__ . '/pages/treinamentos/index.php';


// TRAINING FORM
elseif ($page === 'training-form'):

    require_once __DIR__ . '/pages/treinamentos/create.php';


// REGISTER TRAINING
elseif ($page === 'register-training'):

    require_once __DIR__ . '/pages/treinamentos/registrar.php';


// PENDING
elseif ($page === 'pending'):

    require_once __DIR__ . '/pages/pendencias/index.php';

endif;

?>

    </main>
  </div>
</div>

<?php endif; ?>

</body>
</html>