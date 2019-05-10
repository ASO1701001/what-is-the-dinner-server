<?php
class AccountManager {
    /** ログイン
     * @param string $email
     * @param string $password
     * @return array
     */
    public static function login(string $email, string $password) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();

        $sql = "SELECT t_user_id, t_email FROM TUSER_INFO WHERE t_email = :email AND password = :password";
        $data = $db->fetch($sql, array('email' => $email, 'password' => $password));
        if ($data) {
            return [
                'account_type' => 'target',
                'user_id' => $data['t_user_id'],
                'email' => $data['t_email']
            ];
        } else {
            $sql = "SELECT f_user_id, f_email, t_user_id FROM FAMILY_USER_INFO WHERE f_email = :email AND password = :password";
            $data = $db->fetch($sql, array('email' => $email, 'password' => $password));
            if ($data) {
                return [
                    'account_type' => 'family',
                    'user_id' => $data['f_user_id'],
                    'email' => $data['f_email'],
                    'target_user_id' => $data['t_user_id']
                ];
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