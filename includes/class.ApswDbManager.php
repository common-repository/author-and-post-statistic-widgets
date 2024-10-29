<?php

if (!defined('ABSPATH')) {
    exit();
}

class APSWDBManager {

    private $db;
    private $ipsTable;
    private $statsTable;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->ipsTable = $wpdb->prefix . 'sw_ips';
        $this->statsTable = $wpdb->prefix . 'sw_statistics';
    }

    /**
     * create table in db on activation if not exists
     */
    public function createTables($networkWide) {
        global $wpdb;
        if (is_multisite() && $networkWide) {
            $blogIds = $this->db->get_col("SELECT `blog_id` FROM {$wpdb->blogs}");
            foreach ($blogIds as $blogId) {
                switch_to_blog($blogId);
                $this->createTable();
                restore_current_blog();
            }
        } else {
            $this->createTable();
        }
    }

    private function createTable() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $ipsTable = $wpdb->prefix . 'sw_ips';
        if (!($wpdb->get_var("SHOW TABLES LIKE '$ipsTable'"))) {
            $sql = "CREATE TABLE `$ipsTable`(`id` INT NOT NULL AUTO_INCREMENT, `s_id` INT NOT NULL, `ip` VARCHAR(32), PRIMARY KEY (`id`));";
            dbDelta($sql);
        }

        $statsTable = $wpdb->prefix . 'sw_statistics';
        if (!($wpdb->get_var("SHOW TABLES LIKE '$statsTable'"))) {
            $sql = "CREATE TABLE `$statsTable`(`s_id` INT NOT NULL AUTO_INCREMENT, `post_id` INT NOT NULL, `view_count` INT NOT NULL DEFAULT '0', `statistic_date` DATE NOT NULL, PRIMARY KEY (`s_id`));";
            dbDelta($sql);
        }
    }

    public function onNewBlog($blogId, $userId, $domain, $path, $siteId, $meta) {
        if (is_plugin_active_for_network(APSW_DIR_NAME . '/core.php')) {
            switch_to_blog($blogId);
            $this->createTable();
            restore_current_blog();
        }
    }

    function onDeleteBlog($tables) {
        global $wpdb;
        $tables[] = $wpdb->prefix . 'sw_ips';
        $tables[] = $wpdb->prefix . 'sw_statistics';
        return $tables;
    }

    /* =============== DAILY VIEWS FUNCTIONS =============== */

    /**
     * add view for posts
     */
    public function addPostView($postId, $date, $postTypes, $ip, $isViewByIP = 1) {
        $sql = "SELECT `s`.`s_id` AS `stats_id` , `s`.`view_count` AS `view_count` FROM `" . $this->statsTable . "` AS `s` INNER JOIN `" . $this->db->prefix . "posts` AS `p` ON `p`.`ID` = `s`.`post_id` WHERE `p`.`post_status` IN ('publish', 'private') AND `p`.`post_type` IN ($postTypes) AND `s`.`post_id` = %d AND `s`.`statistic_date` = %s;";
        $sql = $this->db->prepare($sql, $postId, $date);
        $result = $this->db->get_row($sql, ARRAY_A);
        if ($result) {
            $s_id = $result['stats_id'];
            $view_count = $result['view_count'];
            if ($isViewByIP == 1) {
                $query1 = "SELECT `s`.`s_id` AS `stats_id`, `s`.`view_count` AS `view_count` FROM `" . $this->statsTable . "` AS `s` INNER JOIN `" . $this->db->prefix . "posts` AS `p` ON `p`.`ID` = `s`.`post_id` INNER JOIN `" . $this->ipsTable . "` AS `ips` ON `ips`.`s_id` = `s`.`s_id` WHERE `p`.`post_status` IN ('publish', 'private') AND `p`.`post_type` IN($postTypes) AND `s`.`post_id` = %d AND `s`.`statistic_date` = %s AND `ips`.`ip` = %s;";
                $query1 = $this->db->prepare($query1, $postId, $date, $ip);
                $result2 = $this->db->query($query1);
                if (!$result2) {
                    $query3 = $this->db->prepare('UPDATE `' . $this->statsTable . '` SET `view_count` = %d  WHERE `s_id` = %d;', ++$view_count, $s_id);
                    $this->db->query($query3);
                    $query4 = $this->db->prepare('INSERT INTO `' . $this->ipsTable . '` (`s_id`, `ip`) VALUES (%d, %s)', $s_id, $ip);
                    $this->db->query($query4);
                }
            } else {
                $query2 = $this->db->prepare('UPDATE `' . $this->statsTable . '` SET `view_count` = %d  WHERE `s_id` = %d;', ++$view_count, $s_id);
                $this->db->query($query2);
            }
        } else {
            $query1 = $this->db->prepare('INSERT INTO `' . $this->statsTable . '`(`post_id`,`view_count`,`statistic_date`) VALUES (%d,1,%s)', $postId, $date);
            $this->db->query($query1);
            $query2 = $this->db->prepare('INSERT INTO `' . $this->ipsTable . '` (`s_id`, `ip`) VALUES ((SELECT MAX(`s_id`) FROM `' . $this->statsTable . '`), %s)', $ip);
            $this->db->query($query2);
        }
    }

    /**
     * get post views count by date interval
     */
    public function getPostViews($post_id, $from, $to) {
        $sql = "SELECT SUM(`view_count`) AS `post_views` FROM `" . $this->statsTable . "` WHERE `post_id` = %d AND `statistic_date` >= %s AND `statistic_date` <= %s;";
        $sql = $this->db->prepare($sql, $post_id, $from, $to);
        $result = $this->db->get_var($sql);
        return $result;
    }

    /* =============== POPULAR POSTS FUNCTIONS =============== */

    public function popularPostsByViews($from, $to, $postTypes, $limit = "", $excludeArgs = "") {
        $excludeIds = isset($excludeArgs['postIds']) && $excludeArgs['postIds'] ? " AND `ID` NOT IN (" . $excludeArgs['postIds'] . ")" : "";
        $limit = isset($limit) && ($l = intval($limit)) ? "LIMIT $l" : "";
        $sql = "SELECT `p`.`ID` AS `post_id`, `p`.`post_title` AS `title`, SUM(`s`.`view_count`) AS `view_count` FROM `" . $this->db->prefix . "posts` AS `p` INNER JOIN `" . $this->statsTable . "` AS `s` ON `p`.`ID` = `s`.`post_id` WHERE `p`.`post_status` IN ('publish', 'private') AND `s`.`statistic_date` >= %s AND `s`.`statistic_date` <= %s AND `p`.`post_type` IN ($postTypes) $excludeIds GROUP BY `p`.`ID` ORDER BY `view_count` DESC, `p`.`ID` ASC $limit;";
        $sql = $this->db->prepare($sql, $from, $to);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    public function popularPostsByComments($from, $to, $postTypes, $limit = "", $excludeArgs = "") {
        $excludeIds = isset($excludeArgs['postIds']) && $excludeArgs['postIds'] ? " AND `ID` NOT IN (" . $excludeArgs['postIds'] . ")" : "";
        $limit = isset($limit) && ($l = intval($limit)) ? "LIMIT $l" : "";
        $sql = "SELECT `p`.`ID` AS `post_id`, `p`.`post_title` AS `title`, COUNT(`c`.`comment_ID`) AS `comment_count` FROM `" . $this->db->prefix . "posts` AS `p` INNER JOIN `" . $this->db->prefix . "comments` AS `c` ON `p`.`ID` = `c`.`comment_post_ID` WHERE `p`.`post_status` IN ('publish', 'private') AND DATE(`c`.`comment_date`) >= %s AND DATE(`c`.`comment_date`) <= %s AND `c`.`comment_approved` = 1 AND `p`.`post_type` IN ($postTypes) $excludeIds GROUP BY `p`.`ID` ORDER BY `comment_count` DESC, `p`.`ID` ASC $limit;";
        $sql = $this->db->prepare($sql, $from, $to);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    /* =============== POPULAR AUTHORS FUNCTIONS =============== */

    public function popularUsersByPostsCount($from, $to, $postTypes, $limit = "", $excludeArgs = "") {
        $excludeIds = isset($excludeArgs['userIds']) && $excludeArgs['userIds'] ? " AND `u`.`ID` NOT IN (" . $excludeArgs['userIds'] . ")" : "";
        $limit = isset($limit) && ($l = intval($limit)) ? "LIMIT $l" : "";
        $sql = "SELECT `p`.`post_author` AS `user_id`, COUNT(`p`.`id`) AS `post_count`, `u`.`display_name` AS `name` FROM `" . $this->db->prefix . "posts` AS `p` INNER JOIN `" . $this->db->base_prefix . "users` AS `u` ON `u`.`ID` = `p`.`post_author` WHERE `p`.`post_status` IN ('publish', 'private') AND DATE(`p`.`post_date`) >= %s AND DATE(`p`.`post_date`) <= %s AND `p`.`post_type` IN ($postTypes) $excludeIds GROUP BY `p`.`post_author` ORDER BY `post_count` DESC, `p`.`post_author` ASC $limit;";
        $sql = $this->db->prepare($sql, $from, $to);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    public function popularUsersByPostsViews($from, $to, $postTypes, $limit = "", $excludeArgs = "") {
        $excludeIds = isset($excludeArgs['userIds']) && $excludeArgs['userIds'] ? " AND `u`.`ID` NOT IN (" . $excludeArgs['userIds'] . ")" : "";
        $limit = isset($limit) && ($l = intval($limit)) ? "LIMIT $l" : "";
        $sql = "SELECT `p`.`post_author` AS `user_id`, `u`.`display_name` AS `name`, SUM(`s`.`view_count`) AS `view_count` FROM `" . $this->db->prefix . "posts` AS `p` INNER JOIN `" . $this->statsTable . "` AS `s` ON `p`.`ID` = `s`.`post_id` INNER JOIN `" . $this->db->base_prefix . "users` AS `u` ON `p`.`post_author` = `u`.`ID` WHERE `p`.`post_status` IN ('publish', 'private') AND `s`.`statistic_date` >= %s AND `s`.`statistic_date` <= %s  AND `p`.`post_type` IN ($postTypes) $excludeIds GROUP BY `p`.`post_author` ORDER BY `view_count` DESC, `p`.`post_author` ASC $limit;";
        $sql = $this->db->prepare($sql, $from, $to);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    public function popularUsersByPostsComments($from, $to, $postTypes, $limit, $excludeArgs) {
        $excludeIds = isset($excludeArgs['userIds']) && $excludeArgs['userIds'] ? " AND `u`.`ID` NOT IN (" . $excludeArgs['userIds'] . ")" : "";
        $limit = isset($limit) && ($l = intval($limit)) ? "LIMIT $l" : "";
        $sql = "SELECT `u`.`ID` AS `user_id`, `u`.`display_name` AS `name`, COUNT(`c`.`comment_ID`) AS `comment_count` FROM `" . $this->db->prefix . "posts` AS `p` INNER JOIN `" . $this->db->prefix . "comments` AS `c` ON `p`.`ID` = `c`.`comment_post_ID` INNER JOIN `" . $this->db->base_prefix . "users` AS `u` ON `p`.`post_author` = `u`.`ID` WHERE `p`.`post_status` IN ('publish', 'private') AND `c`.`comment_approved` = 1 AND DATE(`c`.`comment_date`) >= %s AND DATE(`c`.`comment_date`) <= %s AND `p`.`post_type` IN ($postTypes) $excludeIds GROUP BY `u`.`ID` ORDER BY `comment_count` DESC, `u`.`ID` ASC $limit;";
        $sql = $this->db->prepare($sql, $from, $to);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    /* =============== AUTHOR AND POST FUNCTIONS =============== */

    /**
     * return authors posts ids
     */
    public function getAuthorPostIds($userId, $postTypes, $from, $to, $excludeArgs) {
        $excludeIds = isset($excludeArgs['postIds']) && $excludeArgs['postIds'] ? " AND `ID` NOT IN (" . $excludeArgs['postIds'] . ")" : "";
        $sql = "SELECT `ID` AS `id` FROM `" . $this->db->prefix . "posts` WHERE `post_status` IN ('publish', 'private') AND `post_author` = %d AND `post_type` IN($postTypes) AND DATE(`post_date`) >= %s AND DATE(`post_date`) <= %s $excludeIds;";
        $sql = $this->db->prepare($sql, $userId, $from, $to);
        $result = $this->matrixToArray($this->db->get_results($sql, ARRAY_N));
        return $result;
    }

    /**
     * return authors post types and count
     */
    public function getAuthorPostTypes($userId, $postTypes, $from, $to, $excludeArgs) {
        $excludeIds = isset($excludeArgs['postIds']) && $excludeArgs['postIds'] ? " AND `ID` NOT IN (" . $excludeArgs['postIds'] . ")" : "";
        $sql = "SELECT `post_type` AS `type`, COUNT(`post_type`) AS `count` FROM `" . $this->db->prefix . "posts` WHERE `post_status` IN ('publish', 'private') AND `post_author` = %d AND `post_type` IN($postTypes) AND DATE(`post_date`) >= %s AND DATE(`post_date`) <= %s $excludeIds GROUP BY `post_type` ORDER BY `count` DESC, `post_type` ASC;";
        $sql = $this->db->prepare($sql, $userId, $from, $to);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    /**
     * return author posts comments count
     */
    public function getCommentsCount($userId, $postTypes, $from, $to, $excludeArgs) {
        $excludeIds = isset($excludeArgs['postIds']) && $excludeArgs['postIds'] ? " AND `p`.`ID` NOT IN (" . $excludeArgs['postIds'] . ")" : "";
        $sql = "SELECT COUNT(`c`.`comment_ID`) AS `c_id` FROM `" . $this->db->prefix . "comments` AS `c` INNER JOIN `" . $this->db->prefix . "posts` AS `p` ON `c`.`comment_post_ID` = `p`.`ID` WHERE `c`.`user_id` = %d AND `p`.`post_status` IN ('publish', 'private') AND `p`.`post_type` IN ($postTypes) AND DATE(`c`.`comment_date`) >= %s AND DATE(`c`.`comment_date`) <= %s $excludeIds;";
        $sql = $this->db->prepare($sql, $userId, $from, $to);
        $result = $this->db->get_var($sql);
        return $result;
    }

    public function getAuthorPopularPostsByViews($userId, $from, $to, $postTypes, $limit, $excludeArgs) {
        $excludeIds = isset($excludeArgs['postIds']) && $excludeArgs['postIds'] ? " AND `p`.`ID` NOT IN (" . $excludeArgs['postIds'] . ")" : "";
        $sql = "SELECT `p`.`ID` AS `post_id`, `p`.`post_title` AS `title`, SUM(`s`.`view_count`) AS `view_count` FROM `" . $this->db->prefix . "posts` AS `p` INNER JOIN `" . $this->statsTable . "` AS `s` ON `p`.`ID` = `s`.`post_id` WHERE `p`.`post_status` IN ('publish', 'private') AND `p`.`post_author` = %d AND `s`.`statistic_date` >= %s AND `s`.`statistic_date` <= %s AND `p`.`post_type` IN ($postTypes) $excludeIds GROUP BY `p`.`ID` ORDER BY `view_count` DESC, `p`.`ID` ASC LIMIT %d;";
        $sql = $this->db->prepare($sql, $userId, $from, $to, $limit);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    public function getAuthorPopularPostsByComments($userId, $from, $to, $postTypes, $limit, $excludeArgs) {
        $excludeIds = isset($excludeArgs['postIds']) && $excludeArgs['postIds'] ? " AND `p`.`ID` NOT IN (" . $excludeArgs['postIds'] . ")" : "";
        $sql = "SELECT `p`.`ID` AS `post_id`, `p`.`post_title` AS `title`, COUNT(`c`.`comment_ID`) AS `comment_count` FROM `" . $this->db->prefix . "posts` AS `p` INNER JOIN `" . $this->db->prefix . "comments` AS `c` ON `p`.`ID` = `c`.`comment_post_ID` WHERE `p`.`post_status` IN ('publish', 'private') AND `p`.`post_author` = %d AND DATE(`c`.`comment_date`) >= %s AND DATE(`c`.`comment_date`) <= %s AND `c`.`comment_approved` = 1 AND `p`.`post_type` IN ($postTypes) $excludeIds GROUP BY `p`.`ID` ORDER BY `comment_count` DESC, `p`.`ID` ASC LIMIT %d;";
        $sql = $this->db->prepare($sql, $userId, $from, $to, $limit);
        $result = $this->db->get_results($sql, ARRAY_A);
        return $result;
    }

    /* =============== STATISTICS DELETE FUNCTIONS =============== */

    /**
     * delete post statistics if post was deleted
     */
    public function deletePostStatistics($postId) {
        if (intval($postId)) {
            $sql = $this->db->prepare("DELETE FROM `$this->statsTable` WHERE `post_id` = %d;", $postId);
            $this->db->query($sql);
        }
    }

    /**
     * delete all statistics between two dates
     */
    public function deleteStatisticsByInterval($all, $from, $to) {
        if ($all) {
            $delete_query1 = 'TRUNCATE TABLE `' . $this->ipsTable . '`';
            $delete_query2 = 'TRUNCATE TABLE `' . $this->statsTable . '`';
        } else {
            $delete_query1 = 'DELETE FROM `' . $this->ipsTable . '` WHERE `' . $this->ipsTable . '`.`s_id` IN(SELECT `stats`.`s_id` FROM `' . $this->statsTable . '` AS `stats` WHERE `stats`.`statistic_date` >= %s AND `stats`.`statistic_date` <= %s);';
            $delete_query1 = $this->db->prepare($delete_query1, $from, $to);

            $delete_query2 = 'DELETE FROM `' . $this->statsTable . '` WHERE `statistic_date` >= %s AND `statistic_date` <= %s;';
            $delete_query2 = $this->db->prepare($delete_query2, $from, $to);
        }
        return $this->db->query($delete_query1) && $this->db->query($delete_query2);
    }

    private function matrixToArray($data) {
        $ids = array();
        foreach ($data as $d) {
            $ids[] = $d[0];
        }
        return $ids;
    }

}
