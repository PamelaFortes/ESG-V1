<div class="login-wrap">

    <div class="login-left">

        <div class="login-logo">
            <div class="login-logo-icon">
                <?= ico('shield', 18) ?>
            </div>

            <span class="login-logo-name">
                SafeTrack
            </span>
        </div>

        <div>

            <div class="login-label">
                Sistema de Gestão
            </div>

            <div class="login-title">
                Controle de<br>
                Treinamentos<br>
                e Segurança
            </div>

            <div class="login-desc">
                Gerencie certificações, monitore vencimentos e garanta
                a conformidade da sua equipe com uma ferramenta feita
                para o dia a dia.
            </div>

            <div class="login-stats">

                <div>
                    <div class="login-stat-v">48</div>
                    <div class="login-stat-l">Funcionários</div>
                </div>

                <div>
                    <div class="login-stat-v">6</div>
                    <div class="login-stat-l">Treinamentos</div>
                </div>

                <div>
                    <div class="login-stat-v">12</div>
                    <div class="login-stat-l">Alertas ativos</div>
                </div>

            </div>

        </div>

        <div class="login-foot">
            © 2026 SafeTrack. Todos os direitos reservados.
        </div>

    </div>


    <div class="login-right">

        <div class="login-form-wrap">

            <div class="login-form-title">
                Bem-vindo de volta
            </div>

            <div class="login-form-sub">
                Acesse sua conta para continuar
            </div>


            <?php if (isset($_GET['error'])): ?>

                <div class="alert alert-error">
                    <?= ico('alert', 14) ?>

                    E-mail ou senha incorretos.
                </div>

            <?php endif; ?>


            <form
                method="POST"
                action=""
                id="login-form"
            >

                <input
                    type="hidden"
                    name="action"
                    value="login"
                >


                <div class="login-fg">

                    <label class="login-lbl">
                        E-mail
                        <span style="color:#ef4444">*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="login-email"
                        class="login-inp"
                        placeholder="seu@email.com"
                        required
                    >

                </div>


                <div class="login-fg">

                    <label class="login-lbl">
                        Senha
                        <span style="color:#ef4444">*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="login-password"
                        class="login-inp"
                        placeholder="••••••••"
                        required
                    >

                </div>


                <div class="login-row">

                    <label class="login-check-lbl">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        Lembrar-me

                    </label>

                    <a href="#" class="login-forgot">
                        Esqueci minha senha
                    </a>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary w-full"
                    style="justify-content:center;padding:11px 16px;font-size:14px"
                >
                    Entrar no sistema
                </button>

            </form>

        </div>

    </div>

</div>