<?php

if (!defined('ABSPATH')) {
    exit();
}

include_once(APSW_DIR_PATH . '/options/class.ApswOptionsSerialized.php');
include_once(APSW_DIR_PATH . '/includes/class.ApswDbManager.php');
include_once(APSW_DIR_PATH . '/includes/class.ApswHelper.php');

class APSWPopularPosts extends WP_Widget {

    public $optionsSerialized;
    public $dbManager;

    public function __construct() {
        $this->optionsSerialized = new APSWOptionsSerialized();
        $this->dbManager = new APSWDBManager();
        $control_ops = array();
        $widget_ops = array(
            'classname' => 'apsw-popular-posts',
            'description' => __('This Widget displays popular posts list on all pages', 'author-and-post-statistic-widgets')
        );
        parent::__construct('apsw-popular-posts', __('APSW - Popular Posts', 'author-and-post-statistic-widgets'), $widget_ops, $control_ops);
    }

    public function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', $instance['title']);
        $last = $instance['dateInterval'];
        $last = apply_filters('apsw_popular_posts_last', $last);
        $interval = APSWHelper::getDateIntervals($last);
        $from = isset($interval['from']) && $interval['from'] ? $interval['from'] : date('Y-m-d', 1);
        $to = isset($interval['to']) && $interval['to'] ? $interval['to'] : APSWHelper::getMysqlDate();
        $isShowThumbnail = $this->optionsSerialized->ppIsShowThumbnail ? $this->optionsSerialized->ppIsShowThumbnail : 0;
        $thumbnailSize = $this->optionsSerialized->ppThumbnailSize && ($size = absint($this->optionsSerialized->ppThumbnailSize)) ? $size : 32;
        $limit = $this->optionsSerialized->ppPostsLimit && ($l = absint($this->optionsSerialized->ppPostsLimit)) ? $l : 10;
        $postTypes = $this->optionsSerialized->postTypes && is_array($this->optionsSerialized->postTypes) ? APSWHelper::getPostTypes($this->optionsSerialized->postTypes) : APSWHelper::getPostTypes();
        $excludePostIds = $this->optionsSerialized->ppExcludeByPostIds ? trim($this->optionsSerialized->ppExcludeByPostIds, ',') : 0;
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

        // Widget 
        echo $before_widget;

        if ($title) {
            echo $before_title . strip_tags($title) . $after_title;
        }

        echo $before_body;

        include 'layouts/popular-posts.php';

        echo $after_body;

        echo $after_widget;
        // end Widget
    }

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

    function form($instance) {
        $defaults = array(
            'title' => __('Popular Posts', 'author-and-post-statistic-widgets'),
            'dateInterval' => -1,
            'widgetCustomArgs' => '',
            'titleCustomArgs' => '',
            'bodyCustomArgs' => ''
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        include 'form/form-popular-posts.php';
    }

}
