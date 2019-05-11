<?php
class MenuManager {
    public static function menuCandidateSet($user_id, $menu_name_1, $menu_name_2, $menu_name_3, $menu_name_4) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "INSERT INTO MENU_MASTER (menu_name) VALUES (:menu_name)";
        $menu_id_1 = $db->insert($sql, array('menu_name' => $menu_name_1));
        $menu_id_2 = $db->insert($sql, array('menu_name' => $menu_name_2));
        $menu_id_3 = $db->insert($sql, array('menu_name' => $menu_name_3));
        $menu_id_4 = $db->insert($sql, array('menu_name' => $menu_name_4));

        $sql = "INSERT INTO MENU_CANDIDATE_LIST (t_user_id, menu_c_no, menu_c_id) VALUES (:user_id, :menu_number, :menu_id)";
        $db->insert($sql, array('user_id' => $user_id, 'menu_number' => 1, 'menu_id' => $menu_id_1));
        $db->insert($sql, array('user_id' => $user_id, 'menu_number' => 2, 'menu_id' => $menu_id_2));
        $db->insert($sql, array('user_id' => $user_id, 'menu_number' => 3, 'menu_id' => $menu_id_3));
        $db->insert($sql, array('user_id' => $user_id, 'menu_number' => 4, 'menu_id' => $menu_id_4));
    }

    public static function menuCandidateList($user_id) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "SELECT menu_c_no, menu_c_id FROM MENU_CANDIDATE_LIST WHERE t_user_id = :user_id";
        $data = $db->fetchAll($sql, array('user_id' => $user_id));
        $result = array();
        foreach ($data as $row) {
            $result[] = array('menu_number' => $row['menu_c_no'], 'menu_id' => $row['menu_c_id']);
        }
        return $result;
    }

    public static function menuDecision($user_id, $menu_id) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "UPDATE FAMILY_USER_INFO SET menu_log_id = :menu_id WHERE f_user_id = :user_id";
        $result = $db->execute($sql, array('menu_id' => $menu_id, 'user_id' => (int)$user_id));
        return $result;
    }
    /*
    public static function menuGet() {

    }

    public static function menuSet() {

    }

    public static function menuSelect() {

    }

    public static function menuDecision() {

    }

    public static function menuLogSet(string $user_id, string $menu_name) {

    }
    */
}