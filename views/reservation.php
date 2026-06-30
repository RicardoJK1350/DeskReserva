<script src="<?= js('reservation') ?>"></script>
<?php
$idEquip = isset($preSelectedEquipId) ? $preSelectedEquipId : ($_GET['equip_id'] ?? null);
$idLab = isset($preSelectedLabId) ? $preSelectedLabId : ($_GET['lab_id'] ?? null);
?>
<div class="container mt-4">
    <h2 class="mb-4">Nova Reserva</h2>
    <div class="alert alert-info">
        Debug: Equip ID recebido: <?= var_dump($idEquip) ?> | Lab ID recebido: <?= var_dump($idLab) ?>
    </div>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
                                            unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/index.php?route=reservation_store" method="POST">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Data da Reserva</label>
                <select class="form-select" name="res_date" id="res_date" required>
                    <?php foreach ($available_dates as $date): ?>
                        <option value="<?= $date ?>"><?= date('d/m/Y', strtotime($date)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Turno</label>
                <select class="form-select" name="turno" required>
                    <option value="manha">Manhã</option>
                    <option value="tarde">Tarde</option>
                    <option value="noite">Noite</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Equipamento</label>
                <select class="form-select" name="equip_id">
                    <option value="">Nenhum (Apenas Laboratório)</option>
                    <?php if (!empty($equipments)): ?>
                        <?php foreach ($equipments as $e): ?>
                            <?php
                            $isSelected = ((string)$e['equip_id'] === (string)$idEquip) ? 'selected' : '';
                            ?>
                            <option value="<?= $e['equip_id'] ?>" <?= $isSelected ?>>
                                <?= htmlspecialchars($e['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Laboratório</label>
                <select class="form-select" name="lab_id">
                    <option value="">Nenhum (Apenas Equipamento)</option>
                    <?php if (!empty($laboratories)): ?>
                        <?php foreach ($laboratories as $l): ?>
                            <?php
                            $isSelectedLab = ((string)$l['lab_id'] === (string)$idLab) ? 'selected' : '';
                            ?>
                            <option value="<?= $l['lab_id'] ?>" <?= $isSelectedLab ?>>
                                Sala <?= htmlspecialchars($l['room_number']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-dark px-5">Salvar Reserva</button>
        </div>
    </form>
</div>