<?php
require_once '../../libs/AccountManager.php';

if (!isset($_GET['email']) || !isset($_GET['password'])) {
    $json = ['status' => 'E00', 'msg' => 'REQUIRED_PARAM'];
} else {
    $data = AccountManager::sign_in($_GET['email'], $_GET['password']);

    if (is_null($data)) {
        $json = ['status' => 'E00', 'msg' => 'UNKNOWN_USER'];
    } else {
        $json = ['status' => 'S00', 'data' => array('user_id' => $data)];
    }
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);