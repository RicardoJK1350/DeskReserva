<?php

class AuthController
{


    public function login()
    {
        $erro = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']); 

        $sucesso = $_GET['msg'] ?? null;

        render_view('login', [
            'erro' => $erro,
            'sucesso' => $sucesso
        ]);
    }

    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $db = (new Database())->getConnection();
            $userModel = new User($db);
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['user_id'],
                    'name' => $user['username'], 
                    'email' => $user['email'],
                    'user_type' => $user['user_type']
                ];
                
                header('Location: ' . BASE_URL . '/index.php?route=profile');
                exit;
            } else {
                $_SESSION['login_error'] = 'E-mail ou senha inválidos.';
                header('Location: ' . BASE_URL . '/index.php?route=auth_login');
                exit;
            }
        }
        header('Location: ' . BASE_URL . '/index.php?route=auth_login');
        exit;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}