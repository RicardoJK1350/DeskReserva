<?php

class LaboratoryController
{


    public function create()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        render_view('create_laboratory');
    }


    public function store()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_number = trim($_POST['room_number'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $capacity = intval($_POST['capacity'] ?? 0);

            if (empty($room_number) || empty($description) || $capacity <= 0 || $capacity > 30) {
                $_SESSION['error'] = "Todos os campos são obrigatórios. Ou a capacidade minima e maxima foi burlada";
                header('Location: ' . BASE_URL . '/index.php?route=laboratory_create');
                exit;
            }

            $imageTmpPath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageTmpPath = $_FILES['image']['tmp_name'];
            }

            $database = new Database();
            $db = $database->getConnection();
            $laboratoryModel = new Laboratory($db);

            $novoId = $laboratoryModel->create($room_number, $description, $capacity, $imageTmpPath);

            if ($novoId) {
                AuditController::registrar('CRIACAO_LABORATORIO', "Criou o laboratório: Nº $room_number (ID: $novoId)");

                $_SESSION['success'] = "Laboratório cadastrado com sucesso! ID: $novoId";
                header('Location: ' . BASE_URL . '/index.php?route=list');
            } else {
                $_SESSION['error'] = "Erro ao salvar no banco de dados.";
                header('Location: ' . BASE_URL . '/index.php?route=laboratory_create');
            }
            exit;
        }
    }
    public function delete()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'adm') {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = (new Database())->getConnection();
            $lab = new Laboratory($db);

            $item = $lab->getById($id);
            $room = $item['room_number'] ?? 'ID: ' . $id;

            if ($lab->delete($id)) {
                AuditController::registrar('EXCLUSAO_LABORATORIO', "Deletou laboratório: Sala $room (ID: $id)");

                $_SESSION['success'] = "Laboratório excluído com sucesso!";
                header('Location: ' . BASE_URL . '/index.php?route=list');
            } else {
                $_SESSION['error'] = "Erro ao excluir o laboratório.";
                header('Location: ' . BASE_URL . '/index.php?route=list');
            }
        }
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=list');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $room_number = $_POST['room_number'] ?? '';
        $description = $_POST['description'] ?? '';
        $capacity = $_POST['capacity'] ?? 0;
        $lab_status = $_POST['lab_status'] ?? 'disp';

        $db = (new Database())->getConnection();
        $labModel = new Laboratory($db);

        if ($labModel->update($id, $room_number, $description, $capacity, $lab_status)) {
            // 4. Auditoria
            AuditController::registrar('EDICAO_LABORATORIO', "Editou laboratório ID: $id (Sala: $room_number)");

            $_SESSION['success'] = "Laboratório atualizado com sucesso!";
            header('Location: ' . BASE_URL . '/index.php?route=list');
        } else {
            $_SESSION['error'] = "Erro ao atualizar no banco de dados.";
            header('Location: ' . BASE_URL . '/index.php?route=lab_edit&id=' . $id);
        }
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $db = (new Database())->getConnection();
        $labModel = new Laboratory($db);

        $lab = $labModel->getById($id);


        render_view('edit_laboratory', ['lab' => $lab]);
    }
}
