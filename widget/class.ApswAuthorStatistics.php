<?php

if (!defined('ABSPATH')) {
    exit();
}

include_once(APSW_DIR_PATH . '/options/class.ApswOptionsSerialized.php');
include_once(APSW_DIR_PATH . '/includes/class.ApswDbManager.php');
include_once(APSW_DIR_PATH . '/includes/class.ApswHelper.php');

class APSWAuthorStatistics extends WP_Widget {

    public $optionsSerialized;
    public $dbManager;

    public function __construct() {
        $this->optionsSerialized = new APSWOptionsSerialized();
        $this->dbManager = new APSWDBManager();
        $control_ops = array();
        $widget_ops = array(
            'classname' => 'apsw-author-post-statistics',
            'description' => __('This Widget displays author and post statistic information.', 'author-and-post-statistic-widgets')
        );
        parent::__construct('apsw-author-post-statistics', __('APSW - Author &amp; Post Statistics', 'author-and-post-statistic-widgets'), $widget_ops, $control_ops);
    }

    /**
     * Initialize The Widget
     */
    public function widget($args, $instance) {
        global $post;
        if (!is_object($post)) {
            $post = get_post();
        }
        if ($post && is_object($post) && $post->ID) {
            $currentUser = null;
            if ($this->optionsSerialized->isShowLoggedInUserStatistic) {
                $currentUser = wp_get_current_user();
            }

            extract($args);
            if ($currentUser && $currentUser->ID) {
                $userId = $currentUser->ID;
                $user = $currentUser;
                $tabAPTitle = $this->optionsSerialized->apCurrentUserStatTitle;
                $subTitleMostViewed = $this->optionsSerialized->apCurrentUserMostViewedSubtitle;
                $subTitleMostCommented = $this->optionsSerialized->apCurrentUserPPMostCommentedSubtitle;
            } else {
                $userId = $post->post_author;
                $user = get_user_by('id', $userId);
                $tabAPTitle = $this->optionsSerialized->apPostAuthorStatTitle;
                $subTitleMostViewed = $this->optionsSerialized->apPostAuthorMostViewedSubtitle;
                $subTitleMostCommented = $this->optionsSerialized->apPostAuthorPPMostCommentedSubtitle;
            }

            $title = apply_filters('widget_title', $instance['title']);
            $last = $instance['dateInterval'];
            $last = apply_filters('apsw_author_and_post_last', $last);
            $interval = APSWHelper::getDateIntervals($last);
            $from = isset($interval['from']) && $interval['from'] ? $interval['from'] : date('Y-m-d', 1);
            $to = isset($interval['to']) && $interval['to'] ? $interval['to'] : APSWHelper::getMysqlDate();
            $isShowAvatar = $this->optionsSerialized->apIsShowAvatar ? $this->optionsSerialized->apIsShowAvatar : 0;
            $avatarSize = $this->optionsSerialized->apAvatarSize ? $this->optionsSerialized->apAvatarSize : 48;
            $isShowThumbnail = $this->optionsSerialized->apIsShowThumbnail ? $this->optionsSerialized->apIsShowThumbnail : 0;
            $thumbnailSize = $this->optionsSerialized->apThumbnailSize ? $this->optionsSerialized->apThumbnailSize : 32;
            $limit = $this->optionsSerialized->apPostsLimit && ($l = absint($this->optionsSerialized->apPostsLimit)) ? $l : 10;
            $postTypes = $this->optionsSerialized->postTypes && is_array($this->optionsSerialized->postTypes) ? APSWHelper::getPostTypes($this->optionsSerialized->postTypes) : APSWHelper::getPostTypes();
            $excludePostIds = $this->optionsSerialized->apExcludeByPostIds ? trim($this->optionsSerialized->apExcludeByPostIds, ',') : 0;
            $excludeArgs = array('postIds' => $excludePostIds);

            $before_widget = $args['before_widget'];
            $after_widget = $args['after_widget'];
            $before_title = $args['before_title'];
            $after_title = $args['after_title'];
            $before_body = '';
            $after_body = '';

            if ($this->optionsSerialized->isDisplayCustomHtmlForWidgets) {

                if ($instance['widgetCustomArgs'] == 1) {
                    $before_widget = ($before = trim($instance['before_widget'])) ? $before : '';
                    $after_widget = ($after = trim($instance['after_widget'])) ? $after : '';
                }

                if (isset($instance['titleCustomArgs']) && $instance['titleCustomArgs'] == 1) {
                    $before_title = ($before = trim($instance['before_title'])) ? $before : '';
                    $after_title = ($after = trim($instance['after_title'])) ? $after : '';
                }

                if (isset($instance['bodyCustomArgs']) && $instance['bodyCustomArgs'] == 1) {
                    $before_body = ($before = trim($instance['before_body'])) ? $before : '';
                    $after_body = ($after = trim($instance['after_body'])) ? $after : '';
                }
            }

            if ($user && $user->ID) {
                // Widget 
                echo $before_widget;

                if (trim($title)) {
                    echo $before_title . strip_tags($title) . $after_title;
                }

                echo $before_body;

                include 'layouts/author-statistics.php';

                echo $after_body;

                echo $after_widget;
                // end Widget
            }
        }
    }

    /**
     * Update the widget options
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['dateInterval'] = $new_instance['dateInterval'];

        if ($this->optionsSerialized->isDisplayCustomHtmlForWidgets) {
            $instance['widgetCustomArgs'] = $new_instance['widgetCustomArgs'];
            $instance['titleCustomArgs'] = $new_instance['titleCustomArgs'];
            $instance['bodyCustomArgs'] = $new_instance['bodyCustomArgs'];
            $instance['before_widget'] = ($before = trim($new_instance['before_widget'])) ? $before : '';
            $instance['after_widget'] = ($after = trim($new_instance['after_widget'])) ? $after : '';
            $instance['before_title'] = ($before = trim($new_instance['before_title'])) ? $before : '';
            $instance['after_title'] = ($after = trim($new_instance['after_title'])) ? $after : '';
            $instance['before_body'] = ($before = trim($new_instance['before_body'])) ? $before : '';
            $instance['after_body'] = ($after = trim($new_instance['after_body'])) ? $after : '';
        }
        return $instance;
    }

    /**
     * Create a form for widget
     */
    function form($instance) {
        //Set up some default widget settings.
        $defaults = array(
            'title' => __('Author &amp; Post Statistics', 'author-and-post-statistic-widgets'),
            'dateInterval' => -1,
            'widgetCustomArgs' => '',
            'titleCustomArgs' => '',
            'bodyCustomArgs' => ''
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        include 'form/form-author-statistics.php';
    }

}
