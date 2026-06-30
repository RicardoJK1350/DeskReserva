<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DeskReserva</title>
    <link rel="stylesheet" href="<?= css('bootstrap') ?>" />
</head>

<body>

    <?php if (isset($_SESSION['user'])): ?>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php?route=profile">RESERVA 1350</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?route=reservation_create">Fazer Reserva</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?route=list">Lista de Recursos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?route=reservation_index">Minhas Reservas</a>
                        </li>

                        <?php if ($_SESSION['user']['user_type'] == 'dire' || $_SESSION['user']['user_type'] == 'adm'): ?>
                            <li class="nav-item dropdown border-start ms-lg-2 ps-lg-3">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Gestão Administrativa
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="index.php?route=admin_users">Lista de Usuários</a></li>
                                    <li><a class="dropdown-item" href="index.php?route=audit_log">Auditoria</a></li>
                                    <li><a class="dropdown-item" href="index.php?route=admin_createuser">Novo Usuário</a></li>
                                    <li><a class="dropdown-item" href="index.php?route=create_equipment">Novo Equipamento</a></li>
                                    <li><a class="dropdown-item" href="index.php?route=create_laboratory">Novo Laboratório</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <div class="navbar-nav ms-auto align-items-center">
                        <span class="navbar-text me-3 text-white small">
                            Logado como: <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong>
                        </span>
                        <a href="<?= BASE_URL ?>/index.php?route=auth_logout" class="btn btn-outline-danger btn-sm">Sair</a>
                    </div>
                </div>
            </div>
        </nav>

    <?php endif; ?>
    <main>
        <div class="container mt-3">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </div>