<?php

class EquipmentController
{

    public function list()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        echo "EquipmentController carregado com sucesso!";
    }


    public function create()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        render_view('create_equipment');
    }


    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=create_equipment');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imageTmpPath = $_FILES['image']['tmp_name'] ?? null;

        if (empty($name)) {
            $_SESSION['error'] = "O nome do equipamento é obrigatório.";
            header('Location: ' . BASE_URL . '/index.php?route=create_equipment');
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $equipment = new Equipment($db);

        $novoId = $equipment->create($name, $description, $imageTmpPath);

        if ($novoId) {
            AuditController::registrar('CRIACAO_EQUIPAMENTO', "Criou o equipamento: $name (ID: $novoId)");

            $_SESSION['success'] = "Equipamento cadastrado com sucesso!";
            header('Location: ' . BASE_URL . '/index.php?route=list');
            exit;
        } else {
            $_SESSION['error'] = "Erro ao salvar no banco de dados.";
            header('Location: ' . BASE_URL . '/index.php?route=create_equipment');
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
            $equipment = new Equipment($db);

            $item = $equipment->getById($id);
            $nome = $item['name'] ?? 'ID: ' . $id;

            if ($equipment->delete($id)) {
                AuditController::registrar('EXCLUSAO_EQUIPAMENTO', "Deletou o equipamento: $nome (ID: $id)");

                header('Location: ' . BASE_URL . '/index.php?route=list&msg=deleted');
            } else {
                header('Location: ' . BASE_URL . '/index.php?route=list&msg=error');
            }
        }
        exit;
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;

        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=list');
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $equipmentModel = new Equipment($db);

        $name        = $_POST['name'];
        $status      = $_POST['status'];
        $description = $_POST['description'];

        if ($equipmentModel->update($id, $name, $status, $description)) {
            AuditController::registrar('EDICAO_EQUIPAMENTO', "Editou equipamento: $name (ID: $id)");
            $_SESSION['success'] = "Equipamento atualizado com sucesso!";
            header('Location: ' . BASE_URL . '/index.php?route=list');
        } else {
            $_SESSION['error'] = "Erro ao atualizar equipamento.";
            header('Location: ' . BASE_URL . '/index.php?route=edit_equipment&id=' . $id);
        }
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?route=list');
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $model = new Equipment($db);

        $equipment = $model->getById($id);

        if (!$equipment) {
            die("Equipamento não encontrado.");
        }

        render_view('edit_equipment', ['equipment' => $equipment]);
    }
}
