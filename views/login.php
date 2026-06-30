<div class="container d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="w-100" style="max-width: 400px;">

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4 fw-bold text-secondary">DeskReserva</h2>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger py-2" role="alert">
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($sucesso)): ?>
                    <div class="alert alert-success py-2" role="alert">
                        <?= htmlspecialchars($sucesso) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/index.php?route=auth_loginAction" method="POST">

                    <div class="mb-3">
                        <label for="email" class="form-label text-muted fw-semibold">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control form-control-lg fs-6" required placeholder="Ex: admin@admin.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-muted fw-semibold">Senha</label>
                        <input type="password" id="password" name="password" class="form-control form-control-lg fs-6" required placeholder="A sua senha">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fs-6 fw-bold mt-2">Entrar</button>
                   
                    <div class="mb-3 text-end">
                        <a href="<?= BASE_URL ?>/index.php?route=forgot_password" class="text-decoration-none small text-muted">Esqueci minha senha</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>