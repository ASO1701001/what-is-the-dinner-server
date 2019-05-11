<?php
require_once '../../libs/MenuManager.php';

if (!isset($_GET['user_id']) || !isset($_GET['menu_id'])) {
    $json = ['status' => 'E00', 'msg' => 'REQUIRED_PARAM'];
} else {
    $data = MenuManager::menuDecision($_GET['user_id'], $_GET['menu_id']);
    $json = ['status' => 'S00', 'data' => $data];
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);