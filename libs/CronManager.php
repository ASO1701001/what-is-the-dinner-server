<?php
class CronManager {
    /**
     * ターゲットユーザーテーブルのメニューIDをNULLにする
     */
    public static function targetUserMenuClear() {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "UPDATE TUSER_INFO SET menu_log_id = NULL";
        $db->execute($sql, array());
    }

    /**
     * 家族ユーザーテーブルのメニューIDをNULLにする
     */
    public static function familyUserMenuClear() {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "UPDATE FAMILY_USER_INFO SET menu_log_id = NULL";
        $db->execute($sql, array());
    }

    /**
     * 30日以上前の決定献立履歴を削除する
     */
    public static function menuLogClear() {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "DELETE FROM MENU_LOG WHERE log_time < DATE_ADD(CURDATE(), INTERVAL -30 DAY)";
        $db->execute($sql, array());
    }
}