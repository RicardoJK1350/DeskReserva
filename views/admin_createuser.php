<div class="container py-4" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-bold py-3">
            ➕ Cadastrar Novo Usuário
        </div>
        <div class="card-body p-4">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= e($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/index.php?route=admin_storeuser">
                
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nome Completo</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="cpf" class="form-label fw-semibold">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold">Telefone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-4">
                    <label for="user_type" class="form-label fw-semibold">Tipo de Usuário</label>
                    <select class="form-select" id="user_type" name="user_type">
                        <option value="usu">Usuário</option>
                        <option value="adm">Admin</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-dark fw-bold px-4">Registrar Usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>