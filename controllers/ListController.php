<?php

class ListController
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?route=auth_login');
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();

        $equipment = new Equipment($db);
        $laboratory = new Laboratory($db);

        $equipments = $equipment->getAllWithStatus();
        $laboratories = $laboratory->getAllWithStatus();
        
        render_view('list', [
            'equipments' => $equipments,
            'laboratories' => $laboratories
        ]);
    }
}
