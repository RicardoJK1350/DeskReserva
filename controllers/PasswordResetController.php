<?php
// controllers/PasswordResetController.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 1. IMPORTANDO O PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PasswordResetController
{
    /**
     * Renderiza a tela de solicitação de token
     */
    public function forgot()
    {
        $message = $_SESSION['forgot_msg'] ?? null;
        $message_type = $_SESSION['forgot_type'] ?? null;
        unset($_SESSION['forgot_msg'], $_SESSION['forgot_type']);

        render_view('forgot_password', [
            'message' => $message,
            'message_type' => $message_type
        ]);
    }

    /**
     * Processa o envio do formulário de e-mail e telefone (POST)
     */
    /**
     * Processa o envio do formulário de e-mail e telefone (POST)
     */
    public function forgotAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=passwordReset_forgot');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');

        $db = (new Database())->getConnection();
        $userModel = new User($db);
        $user = $userModel->findByEmailAndPhone($email, $phone);

        if ($user) {
            // 1. Gerar token numérico aleatório seguro de 6 dígitos
            $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiryTime = date("Y-m-d H:i:s", time() + (5 * 60)); // 05 minutos

            // 2. Salvar no banco
            if ($userModel->setResetToken($user['user_id'], $token, $expiryTime)) {

                // 3. Incluir PHPMailer
                require_once BASE_PATH . '/libs/PHPMailer/src/Exception.php';
                require_once BASE_PATH . '/libs/PHPMailer/src/PHPMailer.php';
                require_once BASE_PATH . '/libs/PHPMailer/src/SMTP.php';

                // 4. Instanciar e Configurar
                $mail = new PHPMailer(true);

                try {
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host       = '127.0.0.1';
                    $mail->Port       = 1025;
                    $mail->SMTPAuth   = false;
                    $mail->SMTPSecure = false;
                    $mail->CharSet    = 'UTF-8';

                    $mail->setFrom('sistema@gestao.com', 'Sistema Gestão de Compras');
                    $mail->addAddress($email, $user['name']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Código de Recuperação de Senha';
                    $mail->Body = "
                        <div style='font-family: Arial; padding: 20px;'>
                            <h2>Recuperação de Senha</h2>
                            <p>Olá, {$user['name']}. Seu código é: <strong>{$token}</strong></p>
                        </div>";

                    $mail->send();
                } catch (Exception $e) {
                    // Opcional: logar $mail->ErrorInfo
                }
            } else {
                // Erro interno ao salvar token
            }
        }

        // Blindagem de Segurança: Sempre enviamos o mesmo feedback para evitar enumeração de usuários
        $_SESSION['reset_msg'] = "Um código de verificação foi enviado para o seu e-mail cadastrado (se o usuário existir).";
        $_SESSION['reset_type'] = "success";
        header('Location: ' . BASE_URL . '/index.php?route=passwordReset_reset');
        exit;
    }
    /**
     * Exibe o formulário de redefinição (onde o usuário digita o token recebido)
     */
    public function reset()
    {
        $message = $_SESSION['reset_msg'] ?? null;
        $message_type = $_SESSION['reset_type'] ?? null;
        unset($_SESSION['reset_msg'], $_SESSION['reset_type']);

        render_view('reset_password', [
            'message' => $message,
            'message_type' => $message_type,
            'show_form' => true
        ]);
    }

    /**
     * Valida o token e efetiva a gravação da nova senha
     */
    public function resetAction()
    {
        // Log para saber se a função foi chamada
        error_log("DEBUG: resetAction chamado.");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("DEBUG: Request não é POST.");
            header('Location: ' . BASE_URL . '/index.php?route=passwordReset_reset');
            exit;
        }

        $token = $_POST['token'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        error_log("DEBUG: Token recebido: " . $token);

        if ($newPassword !== $confirmPassword) {
            error_log("DEBUG: Senhas diferentes.");
            $_SESSION['reset_msg'] = "As senhas não coincidem.";
            header('Location: ' . BASE_URL . '/index.php?route=passwordReset_reset');
            exit;
        }

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        $user = $userModel->getUserByResetToken($token);

        if (!$user) {
            error_log("DEBUG: Token não encontrado ou expirado no banco.");
            $_SESSION['reset_msg'] = "Código inválido ou expirado.";
            header('Location: ' . BASE_URL . '/index.php?route=passwordReset_reset');
            exit;
        }

        $userId = $user['user_id'];
        error_log("DEBUG: Usuário encontrado ID: " . $userId);

        if ($userModel->resetPassword($userId, $newPassword)) {
            error_log("DEBUG: Senha atualizada com sucesso.");
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        } else {
            error_log("DEBUG: Falha na atualização SQL.");
            $_SESSION['reset_msg'] = "Erro ao gravar a nova senha.";
            header('Location: ' . BASE_URL . '/index.php?route=passwordReset_reset');
            exit;
        }
    }
}
