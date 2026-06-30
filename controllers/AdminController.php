<?php

class AdminController
{
    private function getAdminId()
    {
        return $_SESSION['user']['id'] ?? $_SESSION['user']['user_id'] ?? $_SESSION['user_id'] ?? null;
    }

    private function registrarLog($acao, $detalhes)
    {
        $db = (new Database())->getConnection();
        $adminId = $this->getAdminId();

        if ($adminId) {
            $audit = new Audit($db);
            $audit->logAction($adminId, $acao, $detalhes);
        }
    }

    private function checkAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'adm') {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }
    }

    public function users()
    {
        $this->checkAdmin();
        $db = (new Database())->getConnection();
        $userModel = new User($db);
        $users = $userModel->getAllUsers();
        render_view('admin_users', ['users' => $users]);
    }

    public function createuser()
    {
        $this->checkAdmin();
        render_view('admin_createuser');
    }

    public function storeuser()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=admin_createuser');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user_type = $_POST['user_type'] ?? 'usu';
        
        // Limpeza igual ao updateUser
        $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
        $phone = !empty($_POST['phone']) ? preg_replace('/\D/', '', $_POST['phone']) : null;

        if (empty($name) || empty($cpf) || empty($email) || empty($password)) {
            $_SESSION['error'] = "Campos obrigatórios faltando.";
            header('Location: ' . BASE_URL . '/index.php?route=admin_createuser');
            exit;
        }

        // Validação de E-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "O e-mail informado não é válido.";
            header('Location: ' . BASE_URL . '/index.php?route=admin_createuser');
            exit;
        }

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        // Certifique-se de que o método register aceita estes parâmetros
        if ($userModel->register($name, $cpf, $email, $phone, $password, $user_type)) {
            $novoUsuario = $userModel->getUserByEmail($email);
            $novoId = $novoUsuario['user_id'] ?? 'N/A';

            $this->registrarLog('CRIACAO_USUARIO', "Criou o usuário: $name (ID: $novoId)");

            $_SESSION['success'] = "Usuário registrado! ID: $novoId";
            header('Location: ' . BASE_URL . '/index.php?route=admin_users');
            exit;
        } else {
            $_SESSION['error'] = "Erro ao registrar usuário.";
            header('Location: ' . BASE_URL . '/index.php?route=admin_createuser');
            exit;
        }
    }

    public function deleteUser()
    {
        $this->checkAdmin();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $db = (new Database())->getConnection();
            $userModel = new User($db);

            $userToDelete = $userModel->getUserById($id);
            $nome = $userToDelete['name'] ?? 'ID: ' . $id;

            if ($userModel->deleteUser($id)) {
                $this->registrarLog('EXCLUSAO_USUARIO', "Deletou o usuário: $nome (ID: $id)");
                header('Location: index.php?route=admin_users&msg=deleted');
            } else {
                header('Location: index.php?route=admin_users&msg=error_delete');
            }
        }
        exit;
    }

    public function editUser()
    {
        $this->checkAdmin();
        $id = $_GET['id'];
        $db = (new Database())->getConnection();
        $userModel = new User($db);
        $user = $userModel->getUserById($id);

        render_view('edit_user', ['user' => $user]);
    }

    public function updateUser()
    {
        $this->checkAdmin();
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $phone = !empty($_POST['phone']) ? preg_replace('/\D/', '', $_POST['phone']) : null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "O e-mail informado não é válido.";
            header('Location: index.php?route=admin_edituser&id=' . $id);
            exit;
        }

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->updateAdmin($id, $name, $email, $cpf, $phone)) {
            $this->registrarLog('EDICAO_USUARIO', "Editou o usuário: $name (ID: $id)");
            header('Location: index.php?route=admin_users&msg=success');
        } else {
            header('Location: index.php?route=admin_users&msg=error');
        }
        exit;
    }
}