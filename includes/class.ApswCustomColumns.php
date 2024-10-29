<?php

if (!defined('ABSPATH')) {
    exit();
}

class APSWCustomColumns {

    private $dbManager;
    private $optionsSerialized;
    private $restrictedPostIds = array();
    private $restrictedPopularPosts = array();
    private $restrictedUserIds = array();
    private $restrictedPopularUsers = array();

    public function __construct($dbManager, $optionsSerialized) {
        $this->dbManager = $dbManager;
        $this->optionsSerialized = $optionsSerialized;

        add_action('restrict_manage_posts', array(&$this, 'restrictPosts'));
        add_filter('pre_get_posts', array(&$this, 'preGetPosts'));
        add_filter('manage_posts_columns', array(&$this, 'popularPostsColumn'), 1000);
        add_action('manage_posts_custom_column', array(&$this, 'popularPostsCustomColumn'), 1000, 2);
        add_filter('manage_pages_columns', array(&$this, 'popularPostsColumn'), 1000);
        add_action('manage_pages_custom_column', array(&$this, 'popularPostsCustomColumn'), 1000, 2);

        add_action('restrict_manage_users', array(&$this, 'restrictUsers'));
        add_filter('pre_get_users', array(&$this, 'preGetUsers'));
        add_filter('manage_users_columns', array(&$this, 'popularUsersColumn'), 1000);
        add_filter('manage_users_custom_column', array(&$this, 'popularUsersCustomColumn'), 1000, 3);
    }

    public function restrictPosts() {
        global $pagenow, $typenow;
        if (is_admin() && $pagenow == 'edit.php' && in_array($typenow, $this->optionsSerialized->postTypes)) {
            $popularBy = isset($_GET['apsw_popularBy']) && ($p = trim($_GET['apsw_popularBy'])) ? $p : '';
            $popularByHtml = "<select name='apsw_popularBy' id='apsw_popularBy' class='postform'>";
            $popularByHtml .= "<option value='' " . selected($popularBy == '', true, false) . ">" . __("Filter by", "author-and-post-statistic-widgets") . "</option>";
            $popularByHtml .= "<option value='mostViewed' " . selected($popularBy == 'mostViewed', true, false) . ">" . __("Most viewed ", "author-and-post-statistic-widgets") . $typenow . "</option>";
            if (post_type_supports($typenow, 'comments')) {
                $popularByHtml .= "<option value='mostCommented'" . selected($popularBy == 'mostCommented', true, false) . ">" . __("Most commented ", "author-and-post-statistic-widgets") . $typenow . "</option>";
            }
            $popularByHtml .= "</select>";
            echo $popularByHtml;

            $lastValues = array(
                '-1' => __('All time', 'author-and-post-statistic-widgets'),
                '0' => __('Today', 'author-and-post-statistic-widgets'),
                '1' => __('Yesterday', 'author-and-post-statistic-widgets'),
                '7' => __('Last 7 Days', 'author-and-post-statistic-widgets'),
                '30' => __('Last 30 Days', 'author-and-post-statistic-widgets'),
                '90' => __('Last 90 Days', 'author-and-post-statistic-widgets'),
                '180' => __('Last 180 Days', 'author-and-post-statistic-widgets'),
                '365' => __('Last year', 'author-and-post-statistic-widgets'),
            );

            $lastValues = apply_filters('apsw_popular_posts_last_dash', $lastValues);
            ksort($lastValues, SORT_NUMERIC);
            $apswLast = isset($_GET['apsw_last']) && (is_numeric($_GET['apsw_last'])) ? $_GET['apsw_last'] : '';
            if ($lastValues && is_array($lastValues)) {
                $lastHtml = "<select name='apsw_last' id='apsw_last' class='postform'>";
                $lastHtml .= "<option value='' " . selected($apswLast === '', true, false) . " >" . __('Select date', 'author-and-post-statistic-widgets') . "</option>";
                foreach ($lastValues as $key => $value) {
                    $lastHtml .= "<option value='$key' " . selected($apswLast, $key, false) . "> $value </option>";
                }
                $lastHtml .= "</select>";
                echo $lastHtml;
            }
        }
    }

    public function preGetPosts($query) {
        global $pagenow, $typenow;
        if (is_admin() && $pagenow == 'edit.php' && in_array($typenow, $this->optionsSerialized->postTypes)) {
            $popularBy = isset($_GET['apsw_popularBy']) && ($p = trim($_GET['apsw_popularBy'])) ? $p : '';
            $params = array('mostViewed', 'mostCommented');
            if (in_array($popularBy, $params)) {
                $apswLast = isset($_GET['apsw_last']) && (is_numeric($_GET['apsw_last'])) ? $_GET['apsw_last'] : '';
                $interval = APSWHelper::getDateIntervals($apswLast);
                $from = $interval['from'];
                $to = $interval['to'];
                $postTypes = APSWHelper::getPostTypes(array($typenow));
                if ($popularBy == 'mostCommented') {
                    $posts = $this->dbManager->popularPostsByComments($from, $to, $postTypes, "", "");
                } else if ($popularBy == 'mostViewed') {
                    $posts = $this->dbManager->popularPostsByViews($from, $to, $postTypes, "", "");
                }
                if ($posts && is_array($posts)) {
                    foreach ($posts as $p) {
                        $pId = $p['post_id'];
                        $postViews = isset($p['view_count']) ? $p['view_count'] : '';
                        $postComments = isset($p['comment_count']) ? $p['comment_count'] : '';
                        $this->restrictedPostIds[] = $p['post_id'];
                        $this->restrictedPopularPosts[$pId] = array('views' => $postViews, 'comments' => $postComments);
                    }
                    if ($this->restrictedPostIds && is_array($this->restrictedPostIds)) {
                        $query->set('post__in', $this->restrictedPostIds);
                        $query->set('orderby', 'post__in');
                    }
                } else {
                    $query->set('post__in', array(0));
                }
            }
        }
    }

    public function popularPostsColumn($columns) {
        $popularBy = isset($_GET['apsw_popularBy']) && ($p = trim($_GET['apsw_popularBy'])) ? $p : '';
        if ($popularBy && $this->restrictedPostIds) {
            if ($popularBy == 'mostViewed') {
                $columns['popular_by_views'] = '<span><span class="dashicons dashicons-visibility" title="' . __("Views", 'author-and-post-statistic-widgets') . '"><span class="screen-reader-text">' . __("Views", 'author-and-post-statistic-widgets') . '</span></span></span>';
            } else if ($popularBy == 'mostCommented') {
                if (isset($columns['comments'])) {
                    unset($columns['comments']);
                }
                $columns['popular_by_comments'] = '<span><span class="vers comment-grey-bubble" title="' . __('Comments') . '"><span class="screen-reader-text">' . __('Comments') . '</span></span></span>';
            }
        }
        return $columns;
    }

    public function popularPostsCustomColumn($column, $post_id) {
        $colHtml = '';
        switch ($column) {
            case 'popular_by_views':
                $viewCount = $this->restrictedPopularPosts[$post_id]['views'];
                $colHtml .= intval($viewCount);
                echo $colHtml;
                break;
            case 'popular_by_comments':
                $commentsCount = $this->restrictedPopularPosts[$post_id]['comments'];
                $colHtml .= '<div class="post-com-count-wrapper">';
                $colHtml .= '<a class="post-com-count post-com-count-approved" href="' . admin_url("edit-comments.php?p=$post_id&comment_status=approved") . '">';
                $colHtml .= '<span class="comment-count-approved" aria-hidden="true">' . $commentsCount . '</span>';
                $colHtml .= '<span class="screen-reader-text">' . $commentsCount . ' comment</span>';
                $colHtml .= '</div>';
                echo $colHtml;
                break;
        }
    }

    public function restrictUsers() {
        global $pagenow, $wp_query;
        if (is_admin() && $pagenow == 'users.php') {
            $popularByHtml = "<select name='apsw_popularBy[]' id='apsw_popularBy' style='float:none;margin-left:5px;'>";
            $popularByHtml .= "<option value=''>" . __("Filter by", "author-and-post-statistic-widgets") . "</option>";
            $popularByHtml .= "<option value='postsCount'>{$this->optionsSerialized->puPostsCountTitle}</option>";
            $popularByHtml .= "<option value='postsViewsCount'>{$this->optionsSerialized->puPostsViewsCountTitle}</option>";
            $popularByHtml .= "<option value='postsCommentsCount'>{$this->optionsSerialized->puPostsCommentsCountTitle}</option>";
            $popularByHtml .= "</select>";
            echo $popularByHtml;

            $lastValues = array(
                '-1' => __('All time', 'author-and-post-statistic-widgets'),
                '0' => __('Today', 'author-and-post-statistic-widgets'),
                '1' => __('Yesterday', 'author-and-post-statistic-widgets'),
                '7' => __('Last 7 Days', 'author-and-post-statistic-widgets'),
                '30' => __('Last 30 Days', 'author-and-post-statistic-widgets'),
                '90' => __('Last 90 Days', 'author-and-post-statistic-widgets'),
                '180' => __('Last 180 Days', 'author-and-post-statistic-widgets'),
                '365' => __('Last year', 'author-and-post-statistic-widgets'),
            );

            $lastValues = apply_filters('apsw_popular_users_last_dash', $lastValues);
            ksort($lastValues, SORT_NUMERIC);
            if ($lastValues && is_array($lastValues)) {
                $lastHtml = "<select name='apsw_last[]' id='apsw_last' style='float:none;margin-left:5px;'>";
                $lastHtml .= "<option value=''>" . __('Select date', 'author-and-post-statistic-widgets') . "</option>";
                foreach ($lastValues as $key => $value) {
                    $lastHtml .= "<option value='$key'> $value </option>";
                }
                $lastHtml .= "</select>";
                echo $lastHtml;
            }
            echo '<input type="submit" class="button" value="' . __('Filter') . '">';
        }
    }

    public function preGetUsers($query) {
        global $pagenow;
        if (isset($_GET['apsw_popularBy'])) {
            $popularBy = isset($_GET['apsw_popularBy'][0]) ? $_GET['apsw_popularBy'][0] : $_GET['apsw_popularBy'][1];
        } else {
            $popularBy = '';
        }
        $params = array('postsCount', 'postsViewsCount', 'postsCommentsCount');
        if (is_admin() && $pagenow == 'users.php' && in_array($popularBy, $params)) {
            if (isset($_GET['apsw_last'])) {
                $apswLast = isset($_GET['apsw_last'][0]) ? $_GET['apsw_last'][0] : $_GET['apsw_last'][1];
            } else {
                $apswLast = '';
            }

            $interval = APSWHelper::getDateIntervals($apswLast);
            $from = $interval['from'];
            $to = $interval['to'];
            $postTypes = $this->optionsSerialized->postTypes && is_array($this->optionsSerialized->postTypes) ? APSWHelper::getPostTypes($this->optionsSerialized->postTypes) : APSWHelper::getPostTypes();
            if ($popularBy == 'postsCount') {
                $users = $this->dbManager->popularUsersByPostsCount($from, $to, $postTypes, "", "");
            } else if ($popularBy == 'postsViewsCount') {
                $users = $this->dbManager->popularUsersByPostsViews($from, $to, $postTypes, "", "");
            } else if ($popularBy == 'postsCommentsCount') {
                $users = $this->dbManager->popularUsersByPostsComments($from, $to, $postTypes, "", "");
            }
            if ($users && is_array($users)) {
                foreach ($users as $u) {
                    $uId = $u['user_id'];
                    $posts = isset($u['post_count']) ? $u['post_count'] : '';
                    $views = isset($u['view_count']) ? $u['view_count'] : '';
                    $comments = isset($u['comment_count']) ? $u['comment_count'] : '';
                    $this->restrictedUserIds[] = $uId;
                    $this->restrictedPopularUsers[$uId] = array('posts' => $posts, 'views' => $views, 'comments' => $comments);
                }
                if ($this->restrictedUserIds && is_array($this->restrictedUserIds)) {
                    $query->set('include', $this->restrictedUserIds);
                    $query->set('orderby', 'include');
                }
            } else {
                $query->set('include', array(0));
            }
        }
    }

    public function popularUsersColumn($columns) {
        if (isset($_GET['apsw_popularBy'])) {
            $popularBy = isset($_GET['apsw_popularBy'][0]) ? $_GET['apsw_popularBy'][0] : $_GET['apsw_popularBy'][1];
        } else {
            $popularBy = '';
        }
        if (($popularBy = trim($popularBy)) && $this->restrictedUserIds) {
            if ($popularBy == 'postsCount') {
                if (isset($columns['posts'])) {
                    unset($columns['posts']);
                }
                $columns['popular_by_postscount'] = '<span><span class="dashicons dashicons-admin-post" title="' . __("Posts") . '"><span class="screen-reader-text">' . __("Posts") . '</span></span></span>';
            } else if ($popularBy == 'postsViewsCount') {
                $columns['popular_by_postsviews'] = '<span><span class="dashicons dashicons-visibility" title="' . __("Posts' views", 'author-and-post-statistic-widgets') . '"><span class="screen-reader-text">' . __("Posts' views", 'author-and-post-statistic-widgets') . '</span></span></span>';
            } else if ($popularBy == 'postsCommentsCount') {
                $columns['popular_by_postscomments'] = '<span><span class="dashicons dashicons-admin-comments" title="' . __("Posts' comments", 'author-and-post-statistic-widgets') . '"><span class="screen-reader-text">' . __("Posts' comments", 'author-and-post-statistic-widgets') . '</span></span></span>';
            }
        }
        return $columns;
    }

    public function popularUsersCustomColumn($output, $column, $user_id) {
        switch ($column) {
            case 'popular_by_postscount':
                $output .= $this->restrictedPopularUsers[$user_id]['posts'];
                break;
            case 'popular_by_postsviews':
                $output .= $this->restrictedPopularUsers[$user_id]['views'];
                break;
            case 'popular_by_postscomments':
                $output .= $this->restrictedPopularUsers[$user_id]['comments'];
                break;
        }
        return $output;
    }

}
