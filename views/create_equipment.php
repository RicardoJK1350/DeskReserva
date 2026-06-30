<div class="container py-4" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-bold py-3">
            ➕ Cadastrar Novo Equipamento
        </div>
        <div class="card-body p-4">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?route=equipment_store" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nome do Equipamento</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Projetor Epson X41" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Descrição / Especificações</label>
                    <input type="text" class="form-control" id="description" name="description" maxlength="100" placeholder="Ex: Entrada HDMI, 3000 Lumens" required>
                    <div class="form-text">Máximo de 100 caracteres.</div>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold">Foto do Equipamento</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">Selecione uma imagem para ajudar na identificação do recurso.</div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-dark fw-bold px-4">Salvar Equipamento</button>
                </div>

            </form>
        </div>
    </div>
</div>  