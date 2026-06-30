<div class="container py-4" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-bold py-3">
            ➕ Cadastrar Novo Laboratório / Sala
        </div>
        <div class="card-body p-4">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?route=laboratory_store" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="room_number" class="form-label fw-semibold">Nº da Sala</label>
                        <input type="text" class="form-control" id="room_number" name="room_number" placeholder="Ex: 302, Lab A" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="capacity" class="form-label fw-semibold">Capacidade (Alunos)</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" min="1" max="30" placeholder="Ex: 30" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Descrição dos Recursos</label>
                    <input type="text" class="form-control" id="description" name="description" maxlength="255" placeholder="Ex: 20 Computadores, Ar-condicionado, Projetor" required>
                    <div class="form-text">Descreva o que há na sala para ajudar os professores.</div>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold">Foto do Laboratório</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-dark fw-bold px-4">Salvar Laboratório</button>
                </div>

            </form>
        </div>
    </div>
</div>