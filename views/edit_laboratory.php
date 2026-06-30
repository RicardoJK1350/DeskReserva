<div class="container py-4">
    <h2 class="mb-4">Editar Laboratório</h2>

    <form action="<?= BASE_URL ?>/index.php?route=laboratory_update" method="POST">
        <input type="hidden" name="id" value="<?= e($lab['lab_id']) ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Número da Sala</label>
                <input type="text" name="room_number" class="form-control" value="<?= e($lab['room_number']) ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="lab_status" class="form-select">
                    <option value="disp" <?= $lab['lab_status'] == 'disp' ? 'selected' : '' ?>>Disponível</option>
                    <option value="ndisp" <?= $lab['lab_status'] == 'ndisp' ? 'selected' : '' ?>>Indisponível</option>
                    <option value="manu" <?= $lab['lab_status'] == 'manu' ? 'selected' : '' ?>>Manutenção</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3" required><?= e($lab['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Capacidade</label>
            <input type="number" name="capacity" class="form-control" value="<?= e($lab['capacity']) ?>" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="<?= BASE_URL ?>/index.php?route=list" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>