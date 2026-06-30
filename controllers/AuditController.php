<?php

class AuditController
{
    public function log()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'adm') {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        $db = (new Database())->getConnection();
        $audit = new Audit($db);
        $logs = $audit->getLogs();

        render_view('admin_auditlog', ['logs' => $logs]);
    }

    public static function registrar($acao, $detalhes)
    {
        $db = (new Database())->getConnection();
        $audit = new Audit($db);
        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['user_id'] ?? $_SESSION['user_id'] ?? null;
        if ($userId) {
            $audit->logAction($userId, $acao, $detalhes);
        }
    }
}
