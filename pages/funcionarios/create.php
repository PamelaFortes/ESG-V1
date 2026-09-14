<?php

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
  <div class="alert alert-success">
    <?= ico('check', 14) ?> Funcionário salvo com sucesso!
  </div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="hidden" name="action" value="save_employee">

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <div class="form-section">
      <div class="form-sec-hdr">
        <div class="form-sec-title">Dados pessoais</div>
      </div>

      <div class="form-body">
        <div class="form-grid form-grid-2">

          <div class="form-group form-full">
            <label class="form-label">
              Nome completo <span class="req">*</span>
            </label>

            <input
              type="text"
              name="nome"
              class="form-ctrl"
              placeholder="Ex.: Carlos Eduardo Silva"
              required
            >
          </div>

          <div class="form-group">
            <label class="form-label">
              CPF <span class="req">*</span>
            </label>

            <input
              type="text"
              name="cpf"
              class="form-ctrl"
              placeholder="000.000.000-00"
              required
            >
          </div>

          <div class="form-group">
            <label class="form-label">Data de nascimento</label>

            <input
              type="date"
              name="data_nascimento"
              class="form-ctrl"
            >
          </div>

          <div class="form-group">
            <label class="form-label">E-mail</label>

            <input
              type="email"
              name="email"
              class="form-ctrl"
              placeholder="colaborador@empresa.com"
            >
          </div>

          <div class="form-group">
            <label class="form-label">Telefone</label>

            <input
              type="tel"
              name="telefone"
              class="form-ctrl"
              placeholder="(11) 99999-9999"
            >
          </div>

        </div>
      </div>
    </div>

    <div class="form-section">
      <div class="form-sec-hdr">
        <div class="form-sec-title">Dados profissionais</div>
      </div>

      <div class="form-body">
        <div class="form-grid form-grid-2">

          <div class="form-group">
            <label class="form-label">
              Cargo <span class="req">*</span>
            </label>

            <input
              type="text"
              name="cargo"
              class="form-ctrl"
              placeholder="Ex.: Técnico de Manutenção"
              required
            >
          </div>

          <div class="form-group">
            <label class="form-label">
              Setor <span class="req">*</span>
            </label>

            <input
              type="text"
              name="setor"
              class="form-ctrl"
              placeholder="Ex.: Manutenção Elétrica"
              required
            >
          </div>

          <div class="form-group">
            <label class="form-label">
              Data de admissão <span class="req">*</span>
            </label>

            <input
              type="date"
              name="data_admissao"
              class="form-ctrl"
              required
            >
          </div>

          <div class="form-group">
            <label class="form-label">Matrícula</label>

            <input
              type="text"
              name="matricula"
              class="form-ctrl"
              placeholder="Ex.: MAT-009"
            >
          </div>

          <div class="form-group form-full">
            <label class="form-label">Status do funcionário</label>

            <select class="form-ctrl" name="status">
              <option value="active">Ativo</option>
              <option value="inactive">Inativo</option>
            </select>
          </div>

        </div>
      </div>
    </div>

    <div class="form-actions">
      <span class="form-req-note">
        <span style="color:#ef4444">*</span> Campos obrigatórios
      </span>

      <a
        href="<?= url(['page'=>'employees']) ?>"
        class="btn btn-secondary"
      >
        Cancelar
      </a>

      <button type="submit" class="btn btn-primary">
        Salvar funcionário
      </button>
    </div>

  </form>
</div>