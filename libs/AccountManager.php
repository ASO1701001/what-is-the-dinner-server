<?php
class AccountManager {
    /** ログイン
     * @param string $email
     * @param string $password
     * @return int|null
     */
    public static function login(string $email, string $password) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();

        $sql = "SELECT t_user_id FROM TUSER_INFO WHERE t_email = :email AND password = :password";
        $data = $db->fetchColumn($sql, array('email' => $email, 'password' => $password));
        if ($data != '') {
            return $data;
        } else {
            $sql = "SELECT f_user_id FROM FAMILY_USER_INFO WHERE f_email = :email AND password = :password";
            $data = $db->fetchColumn($sql, array('email' => $email, 'password' => $password));
            if ($data != '') {
                return $data;
            }
        }
        return null;
    }

    /**
     * @param string $user_id
     * @return array
     */
    public static function getFamilyUsrList(string $user_id) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();

        $sql = "SELECT f_user_id, f_email FROM FAMILY_USER_INFO WHERE t_user_id = :user_id";
        $data = $db->fetchAll($sql, array('user_id' => $user_id));
        $r_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $r_data[] = array('user_id' => $row['f_user_id'], 'email' => $row['f_email']);
            }
        }
        return $r_data;
    }
}