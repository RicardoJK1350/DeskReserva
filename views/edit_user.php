<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Editar Usuário</h4>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/index.php?route=admin_updateuser" method="POST">
                <input type="hidden" name="id" value="<?= e($user['user_id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control" value="<?= e($user['cpf']) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?= e($user['phone']) ?>" required>
                    </div>
                </div>

        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="<?= BASE_URL ?>/index.php?route=admin_users" class="btn btn-secondary">Cancelar</a>
        </div>
        </form>
    </div>
</div>
</div>