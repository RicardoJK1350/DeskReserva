<div class="container py-4">
    <h2 class="mb-4">Lista de Equipamentos e Laboratórios</h2>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold py-3">⚙️ Equipamentos</div>
                <div class="table-responsive">
                    <div class="mb-3 p-3 pb-0">
                        <input type="text" id="searchEquipments" class="form-control" placeholder="🔍 Pesquisar Equipamentos...">
                    </div>
                    <table id="tableEquipments" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">Foto</th>
                                <th>Nome</th>
                                <th>Status Hoje</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($equipments)): ?>
                                <?php foreach ($equipments as $equip): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($equip['image'])): ?>
                                                <img src="data:image/jpeg;base64,<?= base64_encode($equip['image']) ?>" alt="Equip" class="img-thumbnail" style="width: 60px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light text-center rounded d-flex align-items-center justify-content-center border" style="width: 60px; height: 50px; font-size: 20px;">📦</div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block"><?= e($equip['name']) ?></span>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;"><?= e($equip['description']) ?></small>
                                        </td>
                                        <td>
                                            <?php if (($equip['equip_status'] ?? '') !== 'disp'): ?>
                                                <span class="badge bg-secondary">Inativo/Manutenção</span>
                                            <?php elseif (($equip['reservado_hoje'] ?? 0) > 0): ?>
                                                <span class="badge bg-warning text-dark">Com Reservas Hoje</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Livre Hoje</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <?php if (($equip['equip_status'] ?? '') === 'disp'): ?>
                                                    <a href="<?= BASE_URL ?>/index.php?route=reservation_create&equip_id=<?= $equip['equip_id'] ?>"
                                                        class="btn btn-sm btn-dark">Reservar</a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>Indisponível</button>
                                                <?php endif; ?>

                                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] === 'adm'): ?>
                                                    <div class="btn-group">
                                                        <a href="<?= BASE_URL ?>/index.php?route=equipment_edit&id=<?= $equip['equip_id'] ?>"
                                                            class="btn btn-sm btn-warning">Editar</a>
                                                        <a href="<?= BASE_URL ?>/index.php?route=equipment_delete&id=<?= $equip['equip_id'] ?>"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Confirmar exclusão?')">Deletar</a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhum equipamento cadastrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold py-3">🏫 Laboratórios / Salas</div>
                <div class="table-responsive">
                    <div class="mb-3 p-3 pb-0">
                        <input type="text" id="searchLabs" class="form-control" placeholder="🔍 Pesquisar Laboratórios...">
                    </div>
                    <table id="tableLabs" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">Foto</th>
                                <th>Sala / Detalhes</th>
                                <th>Status Hoje</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($laboratories)): ?>
                                <?php foreach ($laboratories as $lab): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($lab['image'])): ?>
                                                <img src="data:image/jpeg;base64,<?= base64_encode($lab['image']) ?>" alt="Lab" class="img-thumbnail" style="width: 60px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light text-center rounded d-flex align-items-center justify-content-center border" style="width: 60px; height: 50px; font-size: 20px;">🏫</div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">Sala <?= e($lab['room_number']) ?></span>
                                            <small class="text-muted d-block text-truncate" style="max-width: 220px;"><?= e($lab['description']) ?></small>
                                        </td>
                                        <td>
                                            <?php if (($lab['lab_status'] ?? '') !== 'disp'): ?>
                                                <span class="badge bg-secondary">Inativo/Manutenção</span>
                                            <?php elseif (($lab['reservado_hoje'] ?? 0) > 0): ?>
                                                <span class="badge bg-warning text-dark">Com Reservas Hoje</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Livre Hoje</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <?php if (($lab['lab_status'] ?? '') === 'disp'): ?>
                                                    <a href="<?= BASE_URL ?>/index.php?route=reservation_create&lab_id=<?= $lab['lab_id'] ?>"
                                                        class="btn btn-sm btn-dark">Reservar</a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>Indisponível</button>
                                                <?php endif; ?>

                                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] === 'adm'): ?>
                                                    <div class="btn-group">
                                                        <a href="<?= BASE_URL ?>/index.php?route=lab_edit&id=<?= $lab['lab_id'] ?>"
                                                            class="btn btn-sm btn-warning">Editar</a>
                                                        <a href="<?= BASE_URL ?>/index.php?route=lab_delete&id=<?= $lab['lab_id'] ?>"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Confirmar exclusão?')">Deletar</a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhum laboratório cadastrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>