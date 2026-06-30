<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Logs de Auditoria</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Data/Hora</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Nenhum registro de auditoria encontrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="ps-4 text-nowrap"><?= date('d/m/Y H:i:s', strtotime($log['timestamp'])) ?></td>
                                <td><strong><?= e($log['username']) ?></strong></td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">
                                        <?= e($log['action_type']) ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?= e($log['details']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>