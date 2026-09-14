<?php
?>

<div class="pg max-w-2xl">

  <div class="pg-hdr">
    <div>
      <div class="pg-title">Novo treinamento</div>
      <div class="pg-sub">
        Cadastre um novo tipo de treinamento no sistema
      </div>
    </div>
  </div>


  <div class="form-section">

    <div class="form-sec-hdr">
      <div class="form-sec-title">
        Informações do treinamento
      </div>
    </div>


    <div class="form-body">

      <div class="form-grid" style="gap:20px">

        <div class="form-group">

          <label class="form-label">
            Nome do treinamento
            <span class="req">*</span>
          </label>

          <input
            type="text"
            class="form-ctrl"
            placeholder="Ex.: Trabalho em Altura"
          >

        </div>


        <div class="form-group">

          <label class="form-label">
            Descrição
          </label>

          <textarea
            rows="3"
            class="form-ctrl"
            placeholder="Descreva o objetivo e o conteúdo do treinamento..."
          ></textarea>

        </div>


        <div class="form-grid form-grid-2" style="gap:20px">

          <div class="form-group">

            <label class="form-label">
              Carga horária (horas)
              <span class="req">*</span>
            </label>

            <input
              type="number"
              class="form-ctrl"
              placeholder="8"
              min="1"
            >

          </div>


          <div class="form-group">

            <label class="form-label">
              Validade (meses)
              <span class="req">*</span>
            </label>

            <input
              type="number"
              class="form-ctrl"
              placeholder="12"
              min="1"
            >

          </div>

        </div>


        <div class="form-group">

          <label class="form-label">
            Status
          </label>

          <select class="form-ctrl">

            <option value="active">
              Ativo
            </option>

            <option value="inactive">
              Inativo
            </option>

          </select>

        </div>

      </div>

    </div>

  </div>


  <div class="form-actions">

    <a
      href="<?= url(['page' => 'trainings']) ?>"
      class="btn btn-secondary"
    >
      Cancelar
    </a>


    <a
      href="<?= url(['page' => 'trainings']) ?>"
      class="btn btn-primary"
    >
      Salvar treinamento
    </a>

  </div>

</div>