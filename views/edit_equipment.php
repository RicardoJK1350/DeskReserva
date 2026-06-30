<?php
if (!isset($equipment)) {
    die("Erro: A variável \$equipment não chegou na view. Verifique o Controller.");
}
?>

<div class="container py-4">
    <h2 class="mb-4">Editar Equipamento: <?= e($equipment['name']) ?></h2>
    <form action="<?= BASE_URL ?>/index.php?route=equipment_update&id=<?= $equipment['equip_id'] ?>" method="POST">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="<?= e($equipment['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="disp" <?= $equipment['equip_status'] == 'disp' ? 'selected' : '' ?>>Disponível</option>
                <option value="indisp" <?= $equipment['equip_status'] == 'indisp' ? 'selected' : '' ?>>Indisponível</option>
                <option value="manutencao" <?= $equipment['equip_status'] == 'manutencao' ? 'selected' : '' ?>>Em Manutenção</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3"><?= e($equipment['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-dark">Salvar Alterações</button>
        <a href="<?= BASE_URL ?>/index.php?route=list" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>