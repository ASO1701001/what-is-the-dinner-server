<?php
require_once '../../libs/MenuManager.php';

if (!isset($_GET['user_id']) || !isset($_GET['menu_name_1']) || !isset($_GET['menu_name_2']) || !isset($_GET['menu_name_3']) || !isset($_GET['menu_name_4'])) {
    $json = ['status' => 'E00', 'msg' => 'REQUIRED_PARAM'];
} else {
    $result = MenuManager::menuCandidateSet($_GET['user_id'], $_GET['menu_name_1'], $_GET['menu_name_2'], $_GET['menu_name_3'], $_GET['menu_name_4']);
    if ($result) {
        $json = ['status' => 'S00'];
    } else {
        $json = ['status' => 'E00', 'msg' => 'UNKNOWN_ERROR'];
    }
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);