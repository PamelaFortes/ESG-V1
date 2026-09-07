<?php
session_start();

// ── Contas de usuário ─────────────────────────────────────────────────────
// role: 'gestor' = acesso total | 'funcionario' = acesso ao próprio perfil
$users = [
    ['email'=>'marina@empresa.com',         'password'=>'gestor123',  'role'=>'gestor',      'name'=>'Marina Oliveira',       'emp_id'=>null],
    ['email'=>'rh@empresa.com',             'password'=>'gestor123',  'role'=>'gestor',      'name'=>'Ricardo Henrique',      'emp_id'=>null],
    ['email'=>'carlos.silva@empresa.com',   'password'=>'func123',    'role'=>'funcionario', 'name'=>'Carlos Eduardo Silva',  'emp_id'=>1],
    ['email'=>'ana.rodrigues@empresa.com',  'password'=>'func123',    'role'=>'funcionario', 'name'=>'Ana Paula Rodrigues',   'emp_id'=>2],
    ['email'=>'roberto.santos@empresa.com', 'password'=>'func123',    'role'=>'funcionario', 'name'=>'Roberto Ferreira Santos','emp_id'=>3],
    ['email'=>'juliana.costa@empresa.com',  'password'=>'func123',    'role'=>'funcionario', 'name'=>'Juliana Oliveira Costa','emp_id'=>4],
    ['email'=>'marcos.lima@empresa.com',    'password'=>'func123',    'role'=>'funcionario', 'name'=>'Marcos Antônio Lima',   'emp_id'=>5],
    ['email'=>'paulo.alves@empresa.com',    'password'=>'func123',    'role'=>'funcionario', 'name'=>'Paulo Henrique Alves',  'emp_id'=>7],
    ['email'=>'sandra.pinto@empresa.com',   'password'=>'func123',    'role'=>'funcionario', 'name'=>'Sandra Regina Pinto',   'emp_id'=>8],
];

// ── Login / Logout ────────────────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $found = null;
    foreach ($users as $u) {
        if ($u['email'] === $email && $u['password'] === $pass) { $found = $u; break; }
    }
    if ($found) {
        $_SESSION['logged_in'] = true;
        $_SESSION['role']      = $found['role'];
        $_SESSION['user_name'] = $found['name'];
        $_SESSION['emp_id']    = $found['emp_id'];
        $dest = $found['role'] === 'gestor' ? '?page=dashboard' : '?page=meu-perfil';
        header('Location: ' . $dest);
        exit;
    } else {
        header('Location: ?page=login&error=1');
        exit;
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ?page=login');
    exit;
}

$page        = $_GET['page'] ?? 'login';
$emp_id      = (int)($_GET['id'] ?? 1);
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

// ── Mock Data ─────────────────────────────────────────────────────────────

$employees = [
    ['id'=>1,'name'=>'Carlos Eduardo Silva','cpf'=>'123.456.789-01','role'=>'Técnico de Manutenção','sector'=>'Manutenção Elétrica','email'=>'carlos.silva@empresa.com','phone'=>'(11) 98765-4321','dob'=>'15/03/1985','admission'=>'10/02/2020','registration'=>'MAT-001','status'=>'active','t_total'=>6,'t_valid'=>3,'t_expiring'=>2,'t_expired'=>1],
    ['id'=>2,'name'=>'Ana Paula Rodrigues','cpf'=>'234.567.890-12','role'=>'Supervisora de Limpeza','sector'=>'Conservação','email'=>'ana.rodrigues@empresa.com','phone'=>'(11) 97654-3210','dob'=>'22/07/1990','admission'=>'15/05/2019','registration'=>'MAT-002','status'=>'active','t_total'=>5,'t_valid'=>5,'t_expiring'=>0,'t_expired'=>0],
    ['id'=>3,'name'=>'Roberto Ferreira Santos','cpf'=>'345.678.901-23','role'=>'Eletricista','sector'=>'Manutenção Elétrica','email'=>'roberto.santos@empresa.com','phone'=>'(11) 96543-2109','dob'=>'08/11/1978','admission'=>'20/08/2018','registration'=>'MAT-003','status'=>'active','t_total'=>7,'t_valid'=>2,'t_expiring'=>1,'t_expired'=>4],
    ['id'=>4,'name'=>'Juliana Oliveira Costa','cpf'=>'456.789.012-34','role'=>'Técnica de Segurança','sector'=>'Segurança do Trabalho','email'=>'juliana.costa@empresa.com','phone'=>'(11) 95432-1098','dob'=>'30/04/1992','admission'=>'05/01/2021','registration'=>'MAT-004','status'=>'active','t_total'=>8,'t_valid'=>8,'t_expiring'=>0,'t_expired'=>0],
    ['id'=>5,'name'=>'Marcos Antônio Lima','cpf'=>'567.890.123-45','role'=>'Pedreiro','sector'=>'Obras Civis','email'=>'marcos.lima@empresa.com','phone'=>'(11) 94321-0987','dob'=>'14/09/1982','admission'=>'12/03/2017','registration'=>'MAT-005','status'=>'active','t_total'=>4,'t_valid'=>1,'t_expiring'=>1,'t_expired'=>2],
    ['id'=>6,'name'=>'Fernanda Souza Mendes','cpf'=>'678.901.234-56','role'=>'Auxiliar de Manutenção','sector'=>'Manutenção Geral','email'=>'fernanda.mendes@empresa.com','phone'=>'(11) 93210-9876','dob'=>'01/12/1995','admission'=>'01/06/2022','registration'=>'MAT-006','status'=>'inactive','t_total'=>3,'t_valid'=>2,'t_expiring'=>0,'t_expired'=>1],
    ['id'=>7,'name'=>'Paulo Henrique Alves','cpf'=>'789.012.345-67','role'=>'Encanador','sector'=>'Manutenção Hidráulica','email'=>'paulo.alves@empresa.com','phone'=>'(11) 92109-8765','dob'=>'25/06/1988','admission'=>'15/11/2020','registration'=>'MAT-007','status'=>'active','t_total'=>5,'t_valid'=>2,'t_expiring'=>2,'t_expired'=>1],
    ['id'=>8,'name'=>'Sandra Regina Pinto','cpf'=>'890.123.456-78','role'=>'Operadora de Limpeza','sector'=>'Conservação','email'=>'sandra.pinto@empresa.com','phone'=>'(11) 91098-7654','dob'=>'17/01/1983','admission'=>'04/07/2016','registration'=>'MAT-008','status'=>'active','t_total'=>4,'t_valid'=>4,'t_expiring'=>0,'t_expired'=>0],
];

$trainings = [
    ['id'=>1,'name'=>'Integração de Segurança','desc'=>'Treinamento inicial sobre normas e procedimentos de segurança da empresa','hours'=>8,'validity'=>12,'status'=>'active','employees'=>42],
    ['id'=>2,'name'=>'Trabalho em Altura','desc'=>'NR-35 — Capacitação para atividades realizadas acima de 2 metros do nível inferior','hours'=>16,'validity'=>12,'status'=>'active','employees'=>18],
    ['id'=>3,'name'=>'Segurança com Ferramentas','desc'=>'Uso correto e seguro de ferramentas manuais e elétricas no trabalho','hours'=>4,'validity'=>24,'status'=>'active','employees'=>35],
    ['id'=>4,'name'=>'Primeiros Socorros','desc'=>'Procedimentos básicos de primeiros socorros, incluindo RCP e uso do DEA','hours'=>8,'validity'=>24,'status'=>'active','employees'=>12],
    ['id'=>5,'name'=>'Uso de EPI','desc'=>'Identificação, uso correto e conservação de Equipamentos de Proteção Individual','hours'=>4,'validity'=>12,'status'=>'active','employees'=>48],
    ['id'=>6,'name'=>'Elétrica de Baixa Tensão','desc'=>'NR-10 — Segurança em instalações e serviços em eletricidade de baixa tensão','hours'=>40,'validity'=>24,'status'=>'active','employees'=>10],
];

$records = [
    ['id'=>1, 'emp_id'=>3,'emp_name'=>'Roberto Ferreira Santos','emp_role'=>'Eletricista','emp_sector'=>'Manutenção Elétrica','training'=>'Integração de Segurança','done'=>'01/08/2025','expiry'=>'01/08/2026','status'=>'expired','days'=>-29],
    ['id'=>2, 'emp_id'=>3,'emp_name'=>'Roberto Ferreira Santos','emp_role'=>'Eletricista','emp_sector'=>'Manutenção Elétrica','training'=>'Trabalho em Altura','done'=>'15/07/2025','expiry'=>'15/07/2026','status'=>'expired','days'=>-46],
    ['id'=>3, 'emp_id'=>5,'emp_name'=>'Marcos Antônio Lima','emp_role'=>'Pedreiro','emp_sector'=>'Obras Civis','training'=>'Trabalho em Altura','done'=>'20/07/2025','expiry'=>'20/07/2026','status'=>'expired','days'=>-41],
    ['id'=>4, 'emp_id'=>7,'emp_name'=>'Paulo Henrique Alves','emp_role'=>'Encanador','emp_sector'=>'Manutenção Hidráulica','training'=>'Uso de EPI','done'=>'10/08/2025','expiry'=>'10/08/2026','status'=>'expired','days'=>-20],
    ['id'=>5, 'emp_id'=>1,'emp_name'=>'Carlos Eduardo Silva','emp_role'=>'Técnico de Manutenção','emp_sector'=>'Manutenção Elétrica','training'=>'Uso de EPI','done'=>'05/09/2025','expiry'=>'05/09/2026','status'=>'expiring','days'=>6],
    ['id'=>6, 'emp_id'=>7,'emp_name'=>'Paulo Henrique Alves','emp_role'=>'Encanador','emp_sector'=>'Manutenção Hidráulica','training'=>'Integração de Segurança','done'=>'12/09/2025','expiry'=>'12/09/2026','status'=>'expiring','days'=>13],
    ['id'=>7, 'emp_id'=>1,'emp_name'=>'Carlos Eduardo Silva','emp_role'=>'Técnico de Manutenção','emp_sector'=>'Manutenção Elétrica','training'=>'Segurança com Ferramentas','done'=>'25/09/2024','expiry'=>'25/09/2026','status'=>'expiring','days'=>26],
    ['id'=>8, 'emp_id'=>5,'emp_name'=>'Marcos Antônio Lima','emp_role'=>'Pedreiro','emp_sector'=>'Obras Civis','training'=>'Uso de EPI','done'=>'28/09/2025','expiry'=>'28/09/2026','status'=>'expiring','days'=>29],
    ['id'=>9, 'emp_id'=>2,'emp_name'=>'Ana Paula Rodrigues','emp_role'=>'Supervisora de Limpeza','emp_sector'=>'Conservação','training'=>'Integração de Segurança','done'=>'01/10/2025','expiry'=>'01/10/2026','status'=>'valid','days'=>32],
    ['id'=>10,'emp_id'=>4,'emp_name'=>'Juliana Oliveira Costa','emp_role'=>'Técnica de Segurança','emp_sector'=>'Segurança do Trabalho','training'=>'Primeiros Socorros','done'=>'15/06/2024','expiry'=>'15/06/2027','status'=>'valid','days'=>654],
    ['id'=>11,'emp_id'=>8,'emp_name'=>'Sandra Regina Pinto','emp_role'=>'Operadora de Limpeza','emp_sector'=>'Conservação','training'=>'Uso de EPI','done'=>'20/11/2025','expiry'=>'20/11/2026','status'=>'valid','days'=>82],
    ['id'=>12,'emp_id'=>1,'emp_name'=>'Carlos Eduardo Silva','emp_role'=>'Técnico de Manutenção','emp_sector'=>'Manutenção Elétrica','training'=>'Elétrica de Baixa Tensão','done'=>'10/02/2025','expiry'=>'10/02/2027','status'=>'valid','days'=>529],
    ['id'=>13,'emp_id'=>4,'emp_name'=>'Juliana Oliveira Costa','emp_role'=>'Técnica de Segurança','emp_sector'=>'Segurança do Trabalho','training'=>'Trabalho em Altura','done'=>'20/03/2025','expiry'=>'20/03/2026','status'=>'expired','days'=>-163],
    ['id'=>14,'emp_id'=>3,'emp_name'=>'Roberto Ferreira Santos','emp_role'=>'Eletricista','emp_sector'=>'Manutenção Elétrica','training'=>'Elétrica de Baixa Tensão','done'=>'05/04/2024','expiry'=>'05/04/2026','status'=>'expired','days'=>-147],
];

// ── Helpers ───────────────────────────────────────────────────────────────

function initials($name) {
    $parts = array_filter(explode(' ', $name));
    $out = '';
    foreach ($parts as $p) { if (strlen($out) < 2) $out .= strtoupper($p[0]); }
    return $out;
}

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function url($params) { return '?' . http_build_query($params); }

function status_badge($status) {
    $map = ['valid'=>['Válido','bdg-valid'],'expiring'=>['Próximo do vencimento','bdg-expiring'],'expired'=>['Vencido','bdg-expired']];
    [$lbl,$cls] = $map[$status] ?? ['—',''];
    return "<span class=\"bdg $cls\">$lbl</span>";
}

function emp_badge($status) {
    return $status === 'active'
        ? '<span class="bdg bdg-active">Ativo</span>'
        : '<span class="bdg bdg-inactive">Inativo</span>';
}

function ico($name, $size = 16, $cls = '') {
    $icons = [
        'dashboard' => 'M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z|M9 22V12h6v10',
        'users'     => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2|M9 7a4 4 0 100 8 4 4 0 000-8z',
        'book'      => 'M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z|M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z',
        'alert'     => 'M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z|M12 9v4|M12 17h.01',
        'chart'     => 'M18 20V10|M12 20V4|M6 20v-6',
        'search'    => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0',
        'bell'      => 'M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9|M13.73 21a2 2 0 01-3.46 0',
        'plus'      => 'M12 5v14|M5 12h14',
        'eye'       => 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z|M12 9a3 3 0 100 6 3 3 0 000-6z',
        'edit'      => 'M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7|M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z',
        'trash'     => 'M3 6h18|M8 6V4h8v2|M19 6l-1 14H6L5 6',
        'shield'    => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
        'logout'    => 'M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4|M16 17l5-5-5-5|M21 12H9',
        'x'         => 'M18 6L6 18|M6 6l12 12',
        'check'     => 'M20 6L9 17l-5-5',
        'chevron'   => 'M9 18l6-6-6-6',
        'upload'    => 'M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4|M17 8l-5-5-5 5|M12 3v12',
        'clock'     => 'M12 22a10 10 0 100-20 10 10 0 000 20z|M12 6v6l4 2',
    ];
    $d = $icons[$name] ?? '';
    $paths = '';
    foreach (explode('|', $d) as $p) $paths .= "<path d=\"$p\"/>";
    $ca = $cls ? " class=\"$cls\"" : '';
    return "<svg width=\"$size\" height=\"$size\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"$ca aria-hidden=\"true\">$paths</svg>";
}

function find_emp($employees, $id) {
    foreach ($employees as $e) { if ($e['id'] === $id) return $e; }
    return $employees[0];
}

function active_nav($page) {
    if (in_array($page, ['employees','employee-form','employee-profile'])) return 'employees';
    if (in_array($page, ['trainings','training-form','register-training'])) return 'trainings';
    return $page;
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

      <!-- Acessos de demonstração -->
      <div class="demo-box" style="margin-top:24px">
        <div class="demo-box-hdr">Acessos de demonstração — clique para preencher</div>

        <div style="padding:8px 14px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8">Gestores</div>
        <?php foreach ($users as $u): if ($u['role'] !== 'gestor') continue; ?>
        <div class="demo-account" onclick="fillLogin('<?= h($u['email']) ?>','<?= h($u['password']) ?>')">
          <span class="demo-role-pill gestor">Gestor</span>
          <span class="demo-email"><?= h($u['email']) ?></span>
          <span class="demo-pass"><?= h($u['password']) ?></span>
        </div>
        <?php endforeach; ?>

        <div style="padding:8px 14px 4px;border-top:1px solid #f1f5f9;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8">Funcionários</div>
        <?php foreach ($users as $u): if ($u['role'] !== 'funcionario') continue; ?>
        <div class="demo-account" onclick="fillLogin('<?= h($u['email']) ?>','<?= h($u['password']) ?>')">
          <span class="demo-role-pill func">Funcionário</span>
          <span class="demo-email"><?= h($u['email']) ?></span>
          <span class="demo-pass"><?= h($u['password']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
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
      <div class="kpi-val"><?= count($employees) ?></div>
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

<?php
// ====================================================================
// EMPLOYEES LIST
// ====================================================================
elseif ($page === 'employees'):
  $roles = array_unique(array_column($employees, 'role'));
  sort($roles);
  $filtered = $employees;
  if ($s_q)     $filtered = array_filter($filtered, fn($e) => stripos($e['name'],$s_q)!==false || strpos($e['cpf'],$s_q)!==false || stripos($e['sector'],$s_q)!==false);
  if ($s_role)  $filtered = array_filter($filtered, fn($e) => $e['role']===$s_role);
  if ($s_stat)  $filtered = array_filter($filtered, fn($e) => $e['status']===$s_stat);
  $filtered = array_values($filtered);
?>
<div class="pg">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Funcionários</div>
      <div class="pg-sub"><?= count($employees) ?> colaboradores cadastrados</div>
    </div>
    <a href="<?= url(['page'=>'employee-form']) ?>" class="btn btn-primary">
      <?= ico('plus', 13) ?> Novo funcionário
    </a>
  </div>

  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="employees">
    <div class="filter-input">
      <span class="filter-ico"><?= ico('search', 13) ?></span>
      <input type="text" name="q" placeholder="Buscar por nome, CPF ou setor..." value="<?= h($s_q) ?>">
    </div>
    <select name="role" class="filter-sel" onchange="this.form.submit()">
      <option value="">Todos os cargos</option>
      <?php foreach ($roles as $r): ?>
      <option value="<?= h($r) ?>" <?= $s_role===$r ? 'selected' : '' ?>><?= h($r) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="stat" class="filter-sel" onchange="this.form.submit()">
      <option value="">Todas as situações</option>
      <option value="active" <?= $s_stat==='active' ? 'selected' : '' ?>>Ativo</option>
      <option value="inactive" <?= $s_stat==='inactive' ? 'selected' : '' ?>>Inativo</option>
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
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
        <tr><td colspan="6" class="tbl-empty">Nenhum funcionário encontrado. Tente ajustar os filtros.</td></tr>
        <?php else: ?>
        <?php foreach ($filtered as $e): ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <span class="avatar av-md"><?= initials($e['name']) ?></span>
              <div>
                <div class="cell-primary"><?= h($e['name']) ?></div>
                <div class="cell-secondary"><?= h($e['email']) ?></div>
              </div>
            </div>
          </td>
          <td class="cell-mono"><?= h($e['cpf']) ?></td>
          <td>
            <div style="font-size:12px;font-weight:500;color:#1e293b"><?= h($e['role']) ?></div>
            <div class="cell-secondary"><?= h($e['sector']) ?></div>
          </td>
          <td>
            <div class="t-stats">
              <span class="t-dot t-green"><?= $e['t_valid'] ?></span>
              <?php if ($e['t_expiring'] > 0): ?><span class="t-dot t-amber"><?= $e['t_expiring'] ?></span><?php endif; ?>
              <?php if ($e['t_expired'] > 0): ?><span class="t-dot t-red"><?= $e['t_expired'] ?></span><?php endif; ?>
            </div>
          </td>
          <td><?= emp_badge($e['status']) ?></td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="<?= url(['page'=>'employee-profile','id'=>$e['id']]) ?>" class="btn-icon blue" title="Ver perfil"><?= ico('eye', 14) ?></a>
              <a href="<?= url(['page'=>'employee-form','id'=>$e['id']]) ?>" class="btn-icon" title="Editar"><?= ico('edit', 14) ?></a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <?php if (!empty($filtered)): ?>
    <div class="tbl-footer">Mostrando <?= count($filtered) ?> de <?= count($employees) ?> funcionários</div>
    <?php endif; ?>
  </div>
</div>

<?php
// ====================================================================
// EMPLOYEE FORM
// ====================================================================
elseif ($page === 'employee-form'):
  $saved = isset($_GET['saved']);
?>
<div class="pg max-w-3xl">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Novo funcionário</div>
      <div class="pg-sub">Preencha os dados para cadastrar um novo colaborador</div>
    </div>
  </div>
  <?php if ($saved): ?>
  <div class="alert alert-success"><?= ico('check', 14) ?> Funcionário salvo com sucesso!</div>
  <?php endif; ?>
  <form method="POST" action="">
    <input type="hidden" name="action" value="save_employee">

    <div class="form-section">
      <div class="form-sec-hdr"><div class="form-sec-title">Dados pessoais</div></div>
      <div class="form-body">
        <div class="form-grid form-grid-2">
          <div class="form-group form-full">
            <label class="form-label">Nome completo <span class="req">*</span></label>
            <input type="text" class="form-ctrl" placeholder="Ex.: Carlos Eduardo Silva">
          </div>
          <div class="form-group">
            <label class="form-label">CPF <span class="req">*</span></label>
            <input type="text" class="form-ctrl" placeholder="000.000.000-00">
          </div>
          <div class="form-group">
            <label class="form-label">Data de nascimento</label>
            <input type="date" class="form-ctrl">
          </div>
          <div class="form-group">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-ctrl" placeholder="colaborador@empresa.com">
          </div>
          <div class="form-group">
            <label class="form-label">Telefone</label>
            <input type="tel" class="form-ctrl" placeholder="(11) 99999-9999">
          </div>
        </div>
      </div>
    </div>

    <div class="form-section">
      <div class="form-sec-hdr"><div class="form-sec-title">Dados profissionais</div></div>
      <div class="form-body">
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label class="form-label">Cargo <span class="req">*</span></label>
            <input type="text" class="form-ctrl" placeholder="Ex.: Técnico de Manutenção">
          </div>
          <div class="form-group">
            <label class="form-label">Setor <span class="req">*</span></label>
            <input type="text" class="form-ctrl" placeholder="Ex.: Manutenção Elétrica">
          </div>
          <div class="form-group">
            <label class="form-label">Data de admissão <span class="req">*</span></label>
            <input type="date" class="form-ctrl">
          </div>
          <div class="form-group">
            <label class="form-label">Matrícula</label>
            <input type="text" class="form-ctrl" placeholder="Ex.: MAT-009">
          </div>
          <div class="form-group form-full">
            <label class="form-label">Status do funcionário</label>
            <select class="form-ctrl">
              <option value="active">Ativo</option>
              <option value="inactive">Inativo</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <span class="form-req-note"><span style="color:#ef4444">*</span> Campos obrigatórios</span>
      <a href="<?= url(['page'=>'employees']) ?>" class="btn btn-secondary">Cancelar</a>
      <a href="<?= url(['page'=>'employee-form','saved'=>'1']) ?>" class="btn btn-primary">Salvar funcionário</a>
    </div>
  </form>
</div>

<?php
// ====================================================================
// EMPLOYEE PROFILE
// ====================================================================
elseif ($page === 'employee-profile'):
  $emp = find_emp($employees, $emp_id);
  $emp_records = array_values(array_filter($records, fn($r) => $r['emp_id'] === $emp['id']));
?>
<div class="pg">
  <!-- Profile header -->
  <div class="profile-hdr">
    <div class="profile-inner">
      <span class="avatar av-lg"><?= initials($emp['name']) ?></span>
      <div class="profile-info">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px">
          <div>
            <div class="profile-name"><?= h($emp['name']) ?></div>
            <div class="profile-meta">
              <span class="profile-meta-txt"><?= h($emp['role']) ?></span>
              <span class="profile-meta-sep">·</span>
              <span class="profile-meta-txt"><?= h($emp['sector']) ?></span>
              <span class="profile-meta-sep">·</span>
              <span class="profile-meta-tag"><?= h($emp['registration']) ?></span>
            </div>
          </div>
          <div class="profile-actions">
            <?= emp_badge($emp['status']) ?>
            <a href="<?= url(['page'=>'register-training','id'=>$emp['id']]) ?>" class="btn btn-primary btn-sm">
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
      <div class="info-card-title">Dados pessoais</div>
      <?php foreach ([['CPF',$emp['cpf']],['E-mail',$emp['email']],['Telefone',$emp['phone']],['Nascimento',$emp['dob']]] as [$l,$v]): ?>
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

  <!-- Training history -->
  <div class="tbl-wrap">
    <div class="card-hdr">
      <div>
        <div class="card-title">Histórico de treinamentos</div>
      </div>
      <a href="<?= url(['page'=>'register-training','id'=>$emp['id']]) ?>" class="card-link" style="display:flex;align-items:center;gap:4px">
        <?= ico('plus', 12) ?> Registrar treinamento
      </a>
    </div>
    <?php if (empty($emp_records)): ?>
    <div class="tbl-empty">Nenhum treinamento registrado para este funcionário.</div>
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
        <tr class="<?= $r['status']==='expired' ? 'row-exp' : '' ?>">
          <td style="font-weight:600;font-size:12px;color:#1e293b"><?= h($r['training']) ?></td>
          <td class="cell-mono"><?= h($r['done']) ?></td>
          <td class="cell-mono"><?= h($r['expiry']) ?></td>
          <td><?= status_badge($r['status']) ?></td>
          <td><button class="btn-icon" title="Editar"><?= ico('edit', 13) ?></button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php
// ====================================================================
// TRAININGS LIST
// ====================================================================
elseif ($page === 'trainings'):
?>
<div class="pg">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Treinamentos</div>
      <div class="pg-sub"><?= count($trainings) ?> tipos de treinamento cadastrados</div>
    </div>
    <a href="<?= url(['page'=>'training-form']) ?>" class="btn btn-primary">
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
          <td style="font-weight:600;font-size:12px;color:#1e293b"><?= h($t['name']) ?></td>
          <td style="font-size:12px;color:#94a3b8;max-width:220px;white-space:normal"><?= h($t['desc']) ?></td>
          <td style="font-size:12px;font-weight:500"><?= $t['hours'] ?>h</td>
          <td style="font-size:12px"><?= $t['validity'] ?> meses</td>
          <td>
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#f1f5f9;font-size:12px;font-weight:700;color:#334155">
              <?= $t['employees'] ?>
            </span>
          </td>
          <td>
            <?php if ($t['status']==='active'): ?>
            <span class="bdg bdg-valid">Ativo</span>
            <?php else: ?>
            <span class="bdg bdg-inactive">Inativo</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:4px">
              <button class="btn-icon blue"><?= ico('edit', 13) ?></button>
              <button class="btn-icon red"><?= ico('trash', 13) ?></button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
// ====================================================================
// TRAINING FORM
// ====================================================================
elseif ($page === 'training-form'):
?>
<div class="pg max-w-2xl">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Novo treinamento</div>
      <div class="pg-sub">Cadastre um novo tipo de treinamento no sistema</div>
    </div>
  </div>
  <div class="form-section">
    <div class="form-sec-hdr"><div class="form-sec-title">Informações do treinamento</div></div>
    <div class="form-body">
      <div class="form-grid" style="gap:20px">
        <div class="form-group">
          <label class="form-label">Nome do treinamento <span class="req">*</span></label>
          <input type="text" class="form-ctrl" placeholder="Ex.: Trabalho em Altura">
        </div>
        <div class="form-group">
          <label class="form-label">Descrição</label>
          <textarea rows="3" class="form-ctrl" placeholder="Descreva o objetivo e o conteúdo do treinamento..."></textarea>
        </div>
        <div class="form-grid form-grid-2" style="gap:20px">
          <div class="form-group">
            <label class="form-label">Carga horária (horas) <span class="req">*</span></label>
            <input type="number" class="form-ctrl" placeholder="8" min="1">
          </div>
          <div class="form-group">
            <label class="form-label">Validade (meses) <span class="req">*</span></label>
            <input type="number" class="form-ctrl" placeholder="12" min="1">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select class="form-ctrl">
            <option value="active">Ativo</option>
            <option value="inactive">Inativo</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="form-actions">
    <a href="<?= url(['page'=>'trainings']) ?>" class="btn btn-secondary">Cancelar</a>
    <a href="<?= url(['page'=>'trainings']) ?>" class="btn btn-primary">Salvar treinamento</a>
  </div>
</div>

<?php
// ====================================================================
// REGISTER TRAINING
// ====================================================================
elseif ($page === 'register-training'):
  $emp = find_emp($employees, $emp_id);
?>
<div class="pg max-w-2xl">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Registrar treinamento</div>
      <div class="pg-sub">Registre a realização de um treinamento para o colaborador</div>
    </div>
  </div>

  <div class="emp-ref">
    <span class="avatar av-md"><?= initials($emp['name']) ?></span>
    <div style="flex:1;min-width:0">
      <div class="emp-ref-name"><?= h($emp['name']) ?></div>
      <div class="emp-ref-sub"><?= h($emp['role']) ?> — <?= h($emp['sector']) ?></div>
    </div>
    <span class="emp-ref-id"><?= h($emp['registration']) ?></span>
  </div>

  <div class="form-section">
    <div class="form-sec-hdr"><div class="form-sec-title">Dados do treinamento realizado</div></div>
    <div class="form-body">
      <div class="form-grid" style="gap:20px">
        <div class="form-group">
          <label class="form-label">Treinamento <span class="req">*</span></label>
          <select class="form-ctrl">
            <option value="">Selecione o treinamento...</option>
            <?php foreach ($trainings as $t): ?>
            <option value="<?= $t['id'] ?>"><?= h($t['name']) ?> (<?= $t['validity'] ?> meses)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-grid form-grid-2" style="gap:20px">
          <div class="form-group">
            <label class="form-label">Data de realização <span class="req">*</span></label>
            <input type="date" class="form-ctrl">
          </div>
          <div class="form-group">
            <label class="form-label">Data de validade <span class="req">*</span></label>
            <input type="date" class="form-ctrl">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Observações</label>
          <textarea rows="3" class="form-ctrl" placeholder="Informações adicionais sobre o treinamento realizado..."></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Certificado</label>
          <div class="upload-area" onclick="document.getElementById('cert-file').click()">
            <div class="upload-icon-wrap"><?= ico('upload', 18) ?></div>
            <div class="upload-title">Arraste o certificado aqui</div>
            <div class="upload-sub">ou <span class="upload-btn-txt">clique para selecionar</span></div>
            <div class="upload-hint">PDF, JPG ou PNG — máximo 10 MB</div>
            <input type="file" id="cert-file" accept=".pdf,.jpg,.jpeg,.png" style="display:none">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= url(['page'=>'employee-profile','id'=>$emp['id']]) ?>" class="btn btn-secondary">Cancelar</a>
    <a href="<?= url(['page'=>'employee-profile','id'=>$emp['id']]) ?>" class="btn btn-primary">Registrar treinamento</a>
  </div>
</div>

<?php
// ====================================================================
// PENDING
// ====================================================================
elseif ($page === 'pending'):
  $all_expired  = array_values(array_filter($records, fn($r) => $r['status']==='expired'));
  $all_expiring = array_values(array_filter($records, fn($r) => $r['status']==='expiring'));
  $in7  = array_filter($all_expiring, fn($r) => $r['days'] <= 7);
  $in30 = $all_expiring;

  $all_pending = array_merge($all_expired, $all_expiring);

  $sectors   = array_unique(array_column($records, 'emp_sector')); sort($sectors);
  $trainings_list = array_unique(array_column($records, 'training')); sort($trainings_list);

  $displayed = match($tab) {
    'expired'  => $all_expired,
    'expiring' => $all_expiring,
    default    => $all_pending,
  };
  if ($s_sect)  $displayed = array_values(array_filter($displayed, fn($r) => $r['emp_sector']===$s_sect));
  if ($s_train) $displayed = array_values(array_filter($displayed, fn($r) => $r['training']===$s_train));
?>
<div class="pg">
  <div class="pg-hdr">
    <div>
      <div class="pg-title">Pendências</div>
      <div class="pg-sub">Treinamentos vencidos ou com renovação próxima</div>
    </div>
  </div>

  <!-- Summary cards -->
  <div class="summary-grid">
    <div class="sc sc-red">
      <div class="sc-icon-row">
        <div class="sc-icon red"><?= ico('x', 13) ?></div>
        <span class="sc-lbl">Vencidos</span>
      </div>
      <div class="sc-val"><?= count($all_expired) ?></div>
      <div class="sc-meta">renovação imediata necessária</div>
    </div>
    <div class="sc sc-amber">
      <div class="sc-icon-row">
        <div class="sc-icon amber"><?= ico('alert', 13) ?></div>
        <span class="sc-lbl">Vencem em 7 dias</span>
      </div>
      <div class="sc-val"><?= count($in7) ?></div>
      <div class="sc-meta">ação urgente recomendada</div>
    </div>
    <div class="sc sc-orange">
      <div class="sc-icon-row">
        <div class="sc-icon orange"><?= ico('clock', 13) ?></div>
        <span class="sc-lbl">Vencem em 30 dias</span>
      </div>
      <div class="sc-val"><?= count($in30) ?></div>
      <div class="sc-meta">agendar renovação</div>
    </div>
  </div>

  <!-- Filters -->
  <form method="GET" action="" class="filter-bar" style="margin-bottom:16px">
    <input type="hidden" name="page" value="pending">
    <div class="tab-bar">
      <?php foreach ([['all','Todos ('.count($all_pending).')'],['expired','Vencidos ('.count($all_expired).')'],['expiring','Próximos ('.count($all_expiring).')']] as [$v,$lbl]): ?>
      <button type="submit" name="tab" value="<?= $v ?>" class="tab-btn <?= $tab===$v ? 'active' : '' ?>"><?= h($lbl) ?></button>
      <?php endforeach; ?>
    </div>
    <select name="sector" class="filter-sel" onchange="this.form.submit()">
      <option value="">Todos os setores</option>
      <?php foreach ($sectors as $s): ?>
      <option value="<?= h($s) ?>" <?= $s_sect===$s ? 'selected' : '' ?>><?= h($s) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="training" class="filter-sel" onchange="this.form.submit()">
      <option value="">Todos os treinamentos</option>
      <?php foreach ($trainings_list as $t): ?>
      <option value="<?= h($t) ?>" <?= $s_train===$t ? 'selected' : '' ?>><?= h($t) ?></option>
      <?php endforeach; ?>
    </select>
    <?php if ($s_sect || $s_train): ?>
    <a href="<?= url(['page'=>'pending','tab'=>$tab]) ?>" class="btn btn-secondary btn-sm"><?= ico('x', 12) ?> Limpar</a>
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
        <tr><td colspan="8" class="tbl-empty">Nenhuma pendência encontrada com os filtros selecionados.</td></tr>
        <?php else: ?>
        <?php foreach ($displayed as $r): ?>
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
          <td style="font-size:12px;font-weight:500;color:#1e293b"><?= h($r['training']) ?></td>
          <td style="font-size:12px;color:#64748b"><?= h($r['emp_sector']) ?></td>
          <td class="cell-mono"><?= h($r['done']) ?></td>
          <td class="cell-mono"><?= h($r['expiry']) ?></td>
          <td>
            <?php if ($r['days'] < 0): ?>
              <span class="days-exp"><?= abs($r['days']) ?>d atraso</span>
            <?php elseif ($r['days'] <= 7): ?>
              <span class="days-urg"><?= $r['days'] ?>d</span>
            <?php else: ?>
              <span class="days-ok"><?= $r['days'] ?>d</span>
            <?php endif; ?>
          </td>
          <td><?= status_badge($r['status']) ?></td>
          <td><a href="<?= url(['page'=>'employee-profile','id'=>$r['emp_id']]) ?>" class="btn-link">Ver perfil →</a></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <?php if (!empty($displayed)): ?>
    <div class="tbl-footer">
      <?= count($displayed) ?> <?= count($displayed)===1 ? 'pendência encontrada' : 'pendências encontradas' ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php endif; ?>

    </main>
  </div>
</div>

<?php endif; ?>
</body>
</html>
