<?php
class AccountManager {
    /** ログイン
     * @param string $email
     * @param string $password
     * @return int|null
     */
    public static function sign_in(string $email, string $password) {
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

//    /** ワンタイムパスワードを生成する
//     * @param $user_id
//     */
//    public static function onetime_generate($user_id) {
//        require_once 'DatabaseManager.php';
//        require_once 'functions.php';
//        $db = new DatabaseManager();
//        $access_key = random(10);
//        $password = null;
//        for ($i = 0; $i < 6; $i++) {
//            try {
//                $password .= random_int(0, 9);
//            } catch (Exception $e) {
//                $password .= 0;
//            }
//        }
//        $expiration_date = strtotime('+30 minute');
//        $sql = "INSERT INTO auth_onetime(user_id, access_key, password, expiration_date) VALUES (:user_id, :access_key, :password, :expiration_date)";
//        $db->execute($sql, array(
//            'user_id' => $user_id,
//            'access_key' => $access_key,
//            'password' => password_hash($password, PASSWORD_DEFAULT),
//            'expiration_date' => $expiration_date
//        ));
//        self::onetime_send($password, $access_key);
//        $_SESSION['temp']['auth_access_key'] = $access_key;
//    }
//
//    /** 登録しているメールアドレスにワンタイムパスワードを送信
//     * @param $password
//     * @param $access_key
//     */
//    public static function onetime_send($password, $access_key) {
//        require_once 'Mailer.php';
//        require_once 'DatabaseManager.php';
//
//        $to = AccountManager::onetime_get_email($access_key);
//        $subject = '【notice.nova-systems.jp | ワンタイムパスワード】';
//        $body = <<<EOM
//登録されているアカウントにログインリクエストが行われました
//2段階認証画面に、下記のワンタイムパスワードを入力してください
//
//本メールのワンタイムパスワードは30分間有効です
//30分経過後にログインされる場合は新しいワンタイムパスワードを発行しますので、恐れ入りますので再度ログインを行ってください
//
//ワンタイムパスワード：$password
//
//※当メールに心当たりの無い場合は、誠に恐れ入りますが、破棄して頂けますよう、よろしくお願い致します。
//EOM;
//
//        Mailer::send_mail($to, $subject, $body);
//    }
//
//    /** ワンタイムパスワードをチェックする
//     * @param $password
//     * @return bool
//     */
//    public static function onetime_validation($password) {
//        require_once 'DatabaseManager.php';
//        $db = new DatabaseManager();
////        $sql = "SELECT user_id, password, expiration_date FROM auth_onetime WHERE access_key = :access_key";
//        $sql = "SELECT account.id, account.user_id, account.user_name, account.email, auth_onetime.password, auth_onetime.expiration_date
//                FROM auth_onetime
//                LEFT JOIN account ON auth_onetime.user_id = account.id
//                WHERE access_key = :access_key";
//        $data = $db->fetch($sql, array('access_key' => $_SESSION['temp']['auth_access_key']));
//        var_dump($data);
//        if (count($data) > 0) {
//            if ($data['expiration_date'] > time()) {
//                if (password_verify($password, $data['password'])) {
//                    self::onetime_delete($_SESSION['temp']['auth_access_key']);
//                    unset($_SESSION['temp']['auth_access_key']);
//                    $_SESSION['user']['id'] = $data['id'];
//                    $_SESSION['user']['user_id'] = $data['user_id'];
//                    $_SESSION['user']['user_name'] = $data['user_name'];
//                    $_SESSION['user']['email'] = $data['email'];
//                    return true;
//                } else {
//                    $_SESSION['temp']['onetime_error'] = 'ワンタイムパスワードが間違っています';
//                    return false;
//                }
//            } else {
//                self::onetime_delete($_SESSION['temp']['auth_access_key']);
//                unset($_SESSION['temp']['auth_access_key']);
//                $_SESSION['temp']['signin_error'] = 'ワンタイムパスワードの有効期限が切れています';
//                header('Location: /signin');
//                exit();
////                return false;
//            }
//        } else {
//            self::onetime_delete($_SESSION['temp']['auth_access_key']);
//            unset($_SESSION['temp']['auth_access_key']);
//            $_SESSION['temp']['signin_error'] = 'ワンタイムパスワードが見つかりませんでした';
//            header('Location: /signin');
//            exit();
////            return false;
//        }
//    }
//
//    /** ワンタイムパスワードを削除する
//     * @param $access_key
//     */
//    public static function onetime_delete($access_key) {
//        require_once 'DatabaseManager.php';
//        $db = new DatabaseManager();
//        $sql = "DELETE FROM auth_onetime WHERE access_key = :access_key";
//        $db->execute($sql, array('access_key' => $access_key));
//    }
//
//    /** アクセスキーからメールアドレスを得る
//     * @param $access_key
//     * @return int|null
//     */
//    public static function onetime_get_email($access_key) {
//        require_once 'DatabaseManager.php';
//        $db = new DatabaseManager();
//        $sql = "SELECT email FROM auth_onetime LEFT JOIN account ON auth_onetime.user_id = account.id WHERE access_key = :access_key";
//        $email = $db->fetchColumn($sql, array('access_key' => $access_key));
//        if (empty($email)) {
//            return null;
//        }
//        return $email;
//    }
//
//    /** パスワード失敗回数を+1
//     * @param $user_id
//     * @param $count
//     */
//    public static function password_fail_count_up($user_id, $count) {
//        require_once 'DatabaseManager.php';
//        $db = new DatabaseManager();
//        $count = $count + 1;
//        $sql = "UPDATE account SET password_fail_count = :count WHERE id = :user_id";
//        $db->execute($sql, array('count' => $count, 'user_id' => $user_id));
//    }
//
//    /** パスワード失敗回数をリセット
//     * @param $user_id
//     */
//    public static function password_fail_count_reset($user_id) {
//        require_once 'DatabaseManager.php';
//        $db = new DatabaseManager();
//        $sql = "UPDATE account SET password_fail_count = :count WHERE id = :user_id";
//        $db->execute($sql, array('count' => 0, 'user_id' => $user_id));
//    }
//
//    /** ログインログを記録する
//     * @param $user_id
//     * @param $user_agent
//     * @param $ip_address
//     * @param bool $result
//     */
//    public static function signin_log_insert($user_id, $user_agent, $ip_address, bool $result) {
//        require_once 'DatabaseManager.php';
//        $db = new DatabaseManager();
//        $sql = "INSERT INTO account_log_signin(user_id, user_agent, ip_address, date, result) VALUES (:user_id, :user_agent, :ip_address, :date, :result)";
//        $db->execute($sql, array(
//            'user_id' => $user_id,
//            'user_agent' => $user_agent,
//            'ip_address' => $ip_address,
//            'date' => time(),
//            'result' => $result
//        ));
//    }
}