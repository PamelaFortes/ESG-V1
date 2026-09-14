<?php

$emp = find_emp($employees, $emp_id);

?>

<div class="pg max-w-2xl">

  <div class="pg-hdr">
    <div>
      <div class="pg-title">Registrar treinamento</div>
      <div class="pg-sub">
        Registre a realização de um treinamento para o colaborador
      </div>
    </div>
  </div>


  <div class="emp-ref">

    <span class="avatar av-md">
      <?= initials($emp['name']) ?>
    </span>

    <div style="flex:1;min-width:0">

      <div class="emp-ref-name">
        <?= h($emp['name']) ?>
      </div>

      <div class="emp-ref-sub">
        <?= h($emp['role']) ?> — <?= h($emp['sector']) ?>
      </div>

    </div>

    <span class="emp-ref-id">
      <?= h($emp['registration']) ?>
    </span>

  </div>


  <form method="POST" action="">

    <input
      type="hidden"
      name="action"
      value="register_training"
    >

    <input
      type="hidden"
      name="funcionario_id"
      value="<?= (int)$emp['id'] ?>"
    >


    <div class="form-section">

      <div class="form-sec-hdr">
        <div class="form-sec-title">
          Dados do treinamento realizado
        </div>
      </div>


      <div class="form-body">

        <div class="form-grid" style="gap:20px">


          <div class="form-group">

            <label class="form-label">
              Treinamento
              <span class="req">*</span>
            </label>

            <select
              name="treinamento_id"
              class="form-ctrl"
              required
            >

              <option value="">
                Selecione o treinamento...
              </option>

              <?php foreach ($trainings as $t): ?>

                <option value="<?= $t['id'] ?>">
                  <?= h($t['name']) ?>
                  (<?= $t['validity'] ?> meses)
                </option>

              <?php endforeach; ?>

            </select>

          </div>


          <div class="form-grid form-grid-2" style="gap:20px">

            <div class="form-group">

              <label class="form-label">
                Data de realização
                <span class="req">*</span>
              </label>

              <input
                type="date"
                name="data_realizacao"
                class="form-ctrl"
                required
              >

            </div>


            <div class="form-group">

              <label class="form-label">
                Data de validade
                <span class="req">*</span>
              </label>

              <input
                type="date"
                name="data_validade"
                class="form-ctrl"
                required
              >

            </div>

          </div>


          <div class="form-group">

            <label class="form-label">
              Observações
            </label>

            <textarea
              rows="3"
              class="form-ctrl"
              placeholder="Informações adicionais sobre o treinamento realizado..."
            ></textarea>

          </div>


          <div class="form-group">

            <label class="form-label">
              Certificado
            </label>

            <div
              class="upload-area"
              onclick="document.getElementById('cert-file').click()"
            >

              <div class="upload-icon-wrap">
                <?= ico('upload', 18) ?>
              </div>

              <div class="upload-title">
                Arraste o certificado aqui
              </div>

              <div class="upload-sub">
                ou
                <span class="upload-btn-txt">
                  clique para selecionar
                </span>
              </div>

              <div class="upload-hint">
                PDF, JPG ou PNG — máximo 10 MB
              </div>

              <input
                type="file"
                id="cert-file"
                accept=".pdf,.jpg,.jpeg,.png"
                style="display:none"
              >

            </div>

          </div>


        </div>

      </div>

    </div>


    <div class="form-actions">

      <a
        href="<?= url(['page'=>'employee-profile','id'=>$emp['id']]) ?>"
        class="btn btn-secondary"
      >
        Cancelar
      </a>


      <button
        type="submit"
        class="btn btn-primary"
      >
        Registrar treinamento
      </button>

    </div>


  </form>

</div>