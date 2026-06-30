<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciamento de Usuários</h2>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success shadow-sm"><?= e($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger shadow-sm"><?= e($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Pesquisar por ID ou Nome...">
            </div>
            <table id="tableUsers" class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Nível</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Nenhum usuário encontrado</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php
                            if (!isset($user['user_id'])) continue;
                            ?>
                            <tr>
                                <td class="ps-4"><?= e($user['user_id']) ?></td>
                                <td><?= e($user['name'] ?? 'N/A') ?></td>
                                <td><?= e($user['cpf'] ?? 'N/A') ?></td>
                                <td><?= e($user['email'] ?? 'N/A') ?></td>
                                <td><?= e($user['phone'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge rounded-pill <?= ($user['user_type'] ?? '') === 'adm' ? 'bg-danger' : 'bg-primary' ?>">
                                        <?= strtoupper($user['user_type'] ?? 'USU') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $meuId = $_SESSION['user_id'] ?? null;
                                    ?>

                                    <a href="<?= BASE_URL ?>/index.php?route=admin_edituser&id=<?= $user['user_id'] ?>"
                                        class="btn btn-sm btn-outline-primary me-2">
                                        Editar
                                    </a>

                                    <?php if ($user['user_id'] != $meuId): ?>
                                        <a href="<?= BASE_URL ?>/index.php?route=admin_deleteuser&id=<?= $user['user_id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Tem certeza que deseja deletar <?= e($user['name'] ?? 'este usuário') ?>?')">
                                            Excluir
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small"><em>(Você)</em></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>