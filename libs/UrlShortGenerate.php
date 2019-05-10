<?php
class UrlShortGenerate {
    /**
     * @param $url
     * @return string
     */
    public static function insert($url) : string {
        require_once 'DatabaseManager.php';
        require_once 'functions.php';
        $db = new DatabaseManager();

        $url = get_original_url($url);
        $sql = "SELECT count(*) FROM data_url WHERE url_master = :url_master";
        $count = $db->fetchColumn($sql, array('url_master' => $url));

        $url_short = null;

        if ($count > 0) {
            $sql = "SELECT url_short FROM data_url WHERE url_master = :url_master";
            $url_short = $db->fetchColumn($sql, array('url_master' => $url));
        } else {
            do {
                $url_short = random(6);
                $sql = "SELECT count(*) FROM data_url WHERE url_short = :url_short";
                $count = $db->fetchColumn($sql, array('url_short' => $url_short));
            } while ($count > 0);
            $record_time = time();
            $sql = "INSERT INTO data_url(url_master, url_short, record_time) VALUES (:url_master, :url_short, :record_time)";
            $db->execute($sql, array(
                'url_master' => $url,
                'url_short' => $url_short,
                'record_time' => $record_time
            ));
        }

        return $url_short;
    }

    /**
     * @param $url
     * @return string
     */
    public static function insert_plus($url) : string {
        require_once 'DatabaseManager.php';
        require_once 'functions.php';

        $db = new DatabaseManager();

        do {
            $url_short = random(5);
            $sql = "SELECT count(*) FROM data_url_plus WHERE url_short = :url_short";
            $count = $db->fetchColumn($sql, array('url_short' => $url_short));
        } while ($count > 0);

        $url = get_original_url($url);
        $record_time = time();
        $sql = "INSERT INTO data_url_plus(user_id, url_master, url_short, record_time) VALUES (:user_id, :url_master, :url_short, :record_time)";
        $db->execute($sql, array(
            'user_id' => $_SESSION['user']['id'],
            'url_master' => $url,
            'url_short' => $url_short,
            'record_time' => $record_time
        ));

        return $url_short;
    }

    /**
     * @param $id
     * @param $url
     */
    public static function update($id, $url) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "UPDATE data_url_plus SET url_master = :url_master WHERE url_short = :url_short";
        $db->execute($sql, array('url_master' => $url, 'url_short' => $id));
    }

    /**
     * @param $id
     */
    public static function delete($id) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "UPDATE data_url_plus SET delete_flg = true WHERE url_short = :url_short";
        $db->execute($sql, array('url_short' => $id));
    }

    /** パスワード保護有効化
     * @param $id
     * @param $password
     */
    public static function encrypt_enable($id, $password) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "UPDATE data_url_plus SET password = :password WHERE url_short = :url_short";
        $db->execute($sql, array('password' => password_hash($password, PASSWORD_DEFAULT), 'url_short' => $id));
    }

    /** パスワード保護無効化
     * @param $id
     */
    public static function encrypt_disable($id) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "UPDATE data_url_plus SET password = NULL WHERE url_short = :url_short";
        $db->execute($sql, array('url_short' => $id));
    }

    /**
     * @param $key
     * @return array|null
     */
    public static function get_info($key) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        if (preg_match('/[a-zA-Z0-9]{6}/', $key)) {
            $sql = "SELECT url_master, url_short, record_time, delete_flg FROM data_url WHERE url_short = :url_short";
        } else {
            $sql = "SELECT url_master, url_short, record_time, delete_flg FROM data_url_plus WHERE url_short = :url_short";
        }
        $data = $db->fetch($sql, array('url_short' => $key));
        if (empty($data)) return null;
        return [
            'url_master' => ((boolean)$data['delete_flg']) ? null : $data['url_master'],
            'url_short' => $data['url_short'],
            'record_time' => date('Y/m/d H:i:s', $data['record_time']),
            'status' => (boolean)$data['delete_flg']
        ];
    }

    /**
     * @param $key
     * @return string|null
     */
    public static function get_master($key) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        if (preg_match('/[a-zA-Z0-9]{6}/', $key)) {
            $sql = "SELECT url_master, delete_flg FROM data_url WHERE url_short = :url_short";
        } else {
            $sql = "SELECT url_master, delete_flg FROM data_url_plus WHERE url_short = :url_short";
        }
        $data = $db->fetch($sql, array('url_short' => $key));
        if (empty($data)) return null;
        return ((boolean)$data['delete_flg']) ? null : $data['url_master'];
    }

    /** url_shortが存在するか返す
     * @param $key
     * @return bool
     */
    public static function have_code($key) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        if (preg_match('/[a-zA-Z0-9]{6}/', $key)) {
            $sql = "SELECT count(*) FROM data_url WHERE url_short = :url_short";
        } else {
            $sql = "SELECT count(*) FROM data_url_plus WHERE url_short = :url_short";
        }
        $count = $db->fetchColumn($sql, array('url_short' => $key));
        return ($count > 0) ? true : false;
    }

    /** url_shortを保有しているユーザーIDを返す
     * @param $key
     * @return int
     */
    public static function have_user($key) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "SELECT user_id FROM data_url_plus WHERE url_short = :url_short";
        $user_id = $db->fetchColumn($sql, array('url_short' => $key));
        return $user_id;
    }
}