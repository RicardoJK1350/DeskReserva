<div class="container mt-4">
    <h2 class="mb-4">Minhas Reservas</h2>

    <a href="<?= BASE_URL ?>/index.php?route=reservation_create" class="btn btn-dark mb-3">
        + Nova Reserva
    </a>

    <?php if (empty($reservations)): ?>
        <div class="alert alert-info">
            Você não possui nenhuma reserva.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Data</th>
                        <th>Turno</th>
                        <th>Equipamento</th>
                        <th>Laboratório</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $res): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($res['res_date'])) ?></td>

                            <td><?= ucfirst($res['turno']) ?></td>

                            <td><?= !empty($res['equip_name']) ? htmlspecialchars($res['equip_name']) : '-' ?></td>
                            <td><?= !empty($res['lab_room']) ? 'Sala ' . htmlspecialchars($res['lab_room']) : '-' ?></td>
                            <td>
                                <span class="badge bg-<?=
                                                        $res['reservation_status'] == 'pendente' ? 'warning' : ($res['reservation_status'] == 'ativa' ? 'success' : ($res['reservation_status'] == 'cancelada' ? 'danger' : 'secondary'))
                                                        ?>">
                                    <?= ucfirst($res['reservation_status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($res['reservation_status'] !== 'cancelada'): ?>
                            
                                    <a href="<?= BASE_URL ?>/index.php?route=reservation_cancel&id=<?= $res['reservation_id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">Cancelar</a>
                                <?php else: ?>
                                    <span class="text-muted"><small>Sem ações disponíveis</small></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>