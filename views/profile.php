<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <?php if (!empty($msgPerfil)): ?>
                <div class="alert <?= str_contains($msgPerfil, 'Erro') ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show" role="alert">
                    <?= e($msgPerfil) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white p-3">
                    <h4 class="mb-0 fw-bold">Meu Perfil</h4>
                </div>
                <div class="card-body p-4 text-center">
                    
                    <div class="mb-4">
                        <?php if (!empty($user['photo'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($user['photo']) ?>" alt="Foto de Perfil" class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded-circle shadow-sm" style="width: 150px; height: 150px; font-size: 3rem;">
                                <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <form action="<?= BASE_URL ?>/index.php?route=profile_uploadFoto" method="POST" enctype="multipart/form-data" class="mb-4 mx-auto" style="max-width: 400px;">
                        <div class="input-group input-group-sm mb-2">
                            <input type="file" name="imagem" class="form-control" id="inputFoto" required accept="image/*">
                            <button class="btn btn-primary" type="submit">Atualizar Foto</button>
                        </div>
                        <small class="text-muted d-block">Formatos aceitos: JPG, PNG ou WEBP. Máx: 2MB.</small>
                    </form>

                    <hr class="my-4">

                    <div class="text-start mx-auto" style="max-width: 500px;">
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold text-muted">Nome de Usuário:</div>
                            <div class="col-sm-8 text-dark fw-bold"><?= e($user['username']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold text-muted">E-mail:</div>
                            <div class="col-sm-8 text-dark"><?= e($user['email']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold text-muted">Nível de Acesso:</div>
                            <div class="col-sm-8">
                                <?php if (($user['user_type'] ?? '') === 'dire' || ($user['user_type'] ?? '') === 'adm'): ?>
                                    <span class="badge bg-danger">Diretoria / Administrador</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Professor</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>