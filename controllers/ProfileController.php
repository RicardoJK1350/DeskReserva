<?php

class ProfileController
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        $db = (new Database())->getConnection();
        $userModel = new User($db);
        $user = $userModel->getProfile($_SESSION['user']['id']);

        $msgPerfil = $_SESSION['msg_perfil'] ?? null;
        unset($_SESSION['msg_perfil']);

        render_view('profile', [
            'user' => $user,
            'msgPerfil' => $msgPerfil
        ]);
    }

    public function uploadFoto()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

            $maxSize = 2 * 1024 * 1024;
            if ($_FILES['imagem']['size'] > $maxSize) {
                $_SESSION['msg_perfil'] = "Erro: O tamanho da imagem deve ser menor que 2MB.";
                header('Location: ' . BASE_URL . '/index.php?route=profile');
                exit;
            }

            $fileExt = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($fileExt, $allowedExts)) {
                $_SESSION['msg_perfil'] = "Erro: Formato de imagem não permitido. Use apenas JPG, JPEG, PNG ou WEBP.";
                header('Location: ' . BASE_URL . '/index.php?route=profile');
                exit;
            }

            $imagemTmp = $_FILES['imagem']['tmp_name'];
            $conteudo = file_get_contents($imagemTmp);

            $db = (new Database())->getConnection();
            $userModel = new User($db);
            $userModel->updateProfilePhoto($_SESSION['user']['id'], $conteudo);

            $audit = new Audit($db);
            $detalhes = "O usuário atualizou sua foto de perfil.";
            $audit->logAction($_SESSION['user']['id'], 'ATUALIZAR_FOTO_PERFIL', $detalhes);

            $_SESSION['msg_perfil'] = "Foto de perfil atualizada com sucesso!";
        } else {
            $_SESSION['msg_perfil'] = "Erro: Nenhuma imagem selecionada ou falha no upload.";
        }

        header('Location: ' . BASE_URL . '/index.php?route=profile');
        exit;
    }
}