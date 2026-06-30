<?php

class ReservationController
{

    public function create()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        $url = $_SERVER['REQUEST_URI'];
        $parsedUrl = parse_url($url, PHP_URL_QUERY);
        parse_str($parsedUrl, $params);

        $preSelectedEquipId = $params['equip_id'] ?? ($_GET['equip_id'] ?? null);
        $preSelectedLabId = $params['lab_id'] ?? ($_GET['lab_id'] ?? null);

        $database = new Database();
        $db = $database->getConnection();

        $Equipment = new Equipment($db);
        $Laboratory = new Laboratory($db);

        $available_dates = [];
        $hoje = new DateTime();
        for ($i = 0; $i < 30; $i++) {
            $data = clone $hoje;
            $data->modify("+$i days");
            // Ignora domingos (0)
            if ($data->format('w') != 0) {
                $available_dates[] = $data->format('Y-m-d');
            }
        }

        render_view('reservation', [
            'equipments' => $Equipment->getAll(),
            'laboratories' => $Laboratory->getAll(),
            'available_dates' => $available_dates,
            'preSelectedEquipId' => $preSelectedEquipId,
            'preSelectedLabId' => $preSelectedLabId
        ]);
    }

    public function store()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=reservation_create');
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $reservationModel = new Reservation($db);

        $user_id    = $_SESSION['user']['id'] ?? $_SESSION['user']['user_id'];
        $equip_id   = !empty($_POST['equip_id']) ? $_POST['equip_id'] : null;
        $lab_id     = !empty($_POST['lab_id']) ? $_POST['lab_id'] : null;

        $res_date   = $_POST['res_date'] ?? '';
        $turno      = $_POST['turno'] ?? '';

        if (empty($res_date) || empty($turno)) {
            $_SESSION['error'] = "Data ou turno não selecionados.";
            header("Location: " . BASE_URL . "/index.php?route=reservation_create");
            exit;
        }

        if (empty($equip_id) && empty($lab_id)) {
            $_SESSION['error'] = "Você deve selecionar pelo menos um Equipamento ou um Laboratório.";
            header("Location: " . BASE_URL . "/index.php?route=reservation_create");
            exit;
        }

        if ($reservationModel->create($user_id, $equip_id, $lab_id, $turno, $res_date)) {

            $stmtUser = $db->prepare("SELECT name FROM user WHERE user_id = ?");
            $stmtUser->bind_param("i", $user_id);
            $stmtUser->execute();
            $userName = $stmtUser->get_result()->fetch_assoc()['name'] ?? 'Desconhecido';

            $itemName = "N/A";
            if ($equip_id) {
                $stmtEquip = $db->prepare("SELECT name FROM equipment WHERE equip_id = ?");
                $stmtEquip->bind_param("i", $equip_id);
                $stmtEquip->execute();
                $itemName = $stmtEquip->get_result()->fetch_assoc()['name'] ?? 'ID: ' . $equip_id;
            } elseif ($lab_id) {
                $stmtLab = $db->prepare("SELECT room_number FROM laboratory WHERE lab_id = ?");
                $stmtLab->bind_param("i", $lab_id);
                $stmtLab->execute();
                $itemName = "Sala " . ($stmtLab->get_result()->fetch_assoc()['room_number'] ?? 'ID: ' . $lab_id);
            }

            $mensagem = "Reserva realizada: Recurso '$itemName' por '$userName' (ID: $user_id) para o dia $res_date, turno $turno";
            AuditController::registrar('CRIACAO_RESERVA', $mensagem);

            $_SESSION['success'] = "Reserva realizada com sucesso para o turno da $turno!";
            header('Location: ' . BASE_URL . '/index.php?route=reservation_index');
            exit;
        } else {
            $_SESSION['error'] = "Erro ao salvar a reserva no banco de dados.";
            header('Location: ' . BASE_URL . '/index.php?route=reservation_create');
            exit;
        }
    }

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $reservationModel = new Reservation($db);

        $user_id = $_SESSION['user']['id'] ?? $_SESSION['user']['user_id'];
        $reservations = $reservationModel->getByUserId($user_id);

        render_view('myreservations', [
            'reservations' => $reservations
        ]);
    }
    public function cancel()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        $reservation_id = $_GET['id'] ?? null;
        $user_id = $_SESSION['user']['id'] ?? $_SESSION['user']['user_id'];

        if ($reservation_id) {
            $database = new Database();
            $db = $database->getConnection();
            $reservationModel = new Reservation($db);

            if ($reservationModel->cancel($reservation_id, $user_id)) {

                AuditController::registrar('CANCELAMENTO_RESERVA', "Usuário (ID: $user_id) cancelou a reserva (ID: $reservation_id)");

                $_SESSION['success'] = "Reserva cancelada com sucesso!";
            } else {
                $_SESSION['error'] = "Erro ao cancelar. A reserva pode não existir, já estar cancelada, ou não pertencer a você.";
            }
        } else {
            $_SESSION['error'] = "ID da reserva não fornecido.";
        }

        header('Location: ' . BASE_URL . '/index.php?route=reservation_index');
        exit;
    }
}
