<?php

/**
 * Plugin Name: Statistics Widgets
 * Description: Adds awesome statistic widgets for displaying authors activity and posts popularity. This plugin displays adaptive statistical information depending on current opened category, post and page.
 * Version: 2.1.2
 * Author: gVectors Team (Gagik Zakaryan & Hakob Martirosyan)
 * Author URI: http://gvectors.com
 * Plugin URI: http://gvectors.com/author-and-post-statistic-widgets/
 * Text Domain: author-and-post-statistic-widgets
 * Domain Path: /languages/
 */
if (!defined('ABSPATH')) {
    exit();
}

define('APSW_DS', DIRECTORY_SEPARATOR);
define('APSW_DIR_PATH', dirname(__FILE__));
define('APSW_DIR_NAME', basename(APSW_DIR_PATH));

include_once 'includes/interface.ApswConstants.php';
include_once 'includes/class.ApswDbManager.php';
include_once 'includes/class.ApswHelper.php';
include_once 'includes/class.ApswCss.php';
include_once 'includes/class.ApswCustomColumns.php';
include_once 'options/class.ApswOptions.php';
include_once 'options/class.ApswOptionsSerialized.php';
include_once 'widget/class.ApswAuthorStatistics.php';
include_once 'widget/class.ApswPopularPosts.php';
include_once 'widget/class.ApswPopularUsers.php';

class APSWCore implements APSWConstants {

    private $dbManager;
    private $options;
    private $optionsSerialized;
    private $helper;
    private $css;
    public $version;
    private $customColumns;

    function __construct() {
        $this->version = get_option(self::APSW_VERSION);
        if (!$this->version) {
            $this->version = '1.0.0';
        }
        $this->dbManager = new APSWDBManager();
        $this->optionsSerialized = new APSWOptionsSerialized();
        $this->options = new APSWOptions($this->optionsSerialized);
        $this->helper = new APSWHelper($this->optionsSerialized);
        $this->css = new APSWCSS($this->optionsSerialized);
        $this->customColumns = new APSWCustomColumns($this->dbManager, $this->optionsSerialized);

        load_plugin_textdomain('author-and-post-statistic-widgets', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        register_activation_hook(__FILE__, array($this->dbManager, 'createTables'));
        add_action('wpmu_new_blog', array(&$this->dbManager, 'onNewBlog'), 10, 6);
        add_filter('wpmu_drop_tables', array(&$this->dbManager, 'onDeleteBlog'));
        add_action('admin_init', array(&$this, 'newVersion'), 99);
        add_action('widgets_init', array(&$this, 'initWidgets'));
        add_action('admin_menu', array(&$this, 'optionsPage'));
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        add_action('wp_head', array(&$this, 'addPostView'));

        add_action('admin_enqueue_scripts', array(&$this, 'optionsPageFiles'));
        add_action('wp_enqueue_scripts', array(&$this, 'frontendFiles'), 10);
        add_action('wp_enqueue_scripts', array(&$this->css, 'customCss'), 9);
        add_action('wp_loaded', array(&$this->helper, 'initTaxonomies'));
        add_action('deleted_post', array(&$this->dbManager, 'deletePostStatistics'));
        add_action('wp_ajax_deleteStatistics', array(&$this, 'deleteStatistics'));

        if ($this->optionsSerialized->isDisplayDailyViews) {
            add_filter('the_content', array(&$this, 'dailyViews'));
        }

        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$plugin", array(&$this, 'settingsLink'));
    }

    public function newVersion() {
        $pluginData = get_plugin_data(__FILE__);
        if (version_compare($pluginData['Version'], $this->version, '>')) {
            update_option(self::APSW_VERSION, $pluginData['Version']);
            $this->version = get_option(self::APSW_VERSION);
            $this->newOptions();
        }
    }

    private function newOptions() {
        if (version_compare($this->version, '1.4.2', '<=')) {
            delete_option(self::APSW_OPTIONS);
            $this->optionsSerialized->addOptions();
        } else {
            $this->optionsSerialized->initOptions(get_option(APSWCore::APSW_OPTIONS));
            $newOptions = $this->optionsSerialized->toArray();
            update_option(self::APSW_OPTIONS, $newOptions);
        }
    }

    /**
     * display posts' daily views count
     */
    public function dailyViews($content) {
        global $post;
        if (!is_object($post)) {
            $post = get_post();
        }
        $excludedPosts = $this->optionsSerialized->excludeViewByIds ? explode(',', $this->optionsSerialized->excludeViewByIds) : array();
        if ($post && is_object($post) && is_singular() && in_array($post->post_type, $this->optionsSerialized->postTypes) && !in_array($post->ID, $excludedPosts)) {
            $alltimeInterval = APSWHelper::getDateIntervals(-1);
            $alltimeFrom = $alltimeInterval['from'];
            $alltimeTo = $alltimeInterval['to'];
            $alltimeViews = $this->dbManager->getPostViews($post->ID, $alltimeFrom, $alltimeTo);

            $todayInterval = APSWHelper::getDateIntervals(0);
            $todayFrom = $todayInterval['from'];
            $todayTo = $todayInterval['to'];
            $todayViews = $this->dbManager->getPostViews($post->ID, $todayFrom, $todayTo);

            $msgViewsAllTime = __('Views All Time', 'author-and-post-statistic-widgets');
            $msgViewsToday = __('Views Today', 'author-and-post-statistic-widgets');

            $html = '<div class="apsw-post-views-wrapper">';

            $html .= '<div class="apsw-post-views-alltime">';
            $html .= '<div class="apsw-post-views-title">';
            $html .= '<img src="' . plugins_url(APSW_DIR_NAME . '/assets/img/icon-stat.gif') . '" align="absmiddle" class="apsw-img-alltime-views" alt="' . $msgViewsAllTime . '"/> ';
            $html .= '<div>' . $msgViewsAllTime . '</div>';
            $html .= '</div>';
            $html .= '<div class="apsw-post-views-value">' . $alltimeViews . '</div>';
            $html .= '<div style="clear:both"></div>';
            $html .= '</div>';

            $html .= '<div class="apsw-post-views-today">';
            $html .= '<div class="apsw-post-views-title">';
            $html .= '<img src="' . plugins_url(APSW_DIR_NAME . '/assets/img/icon-stat-today.gif') . '" align="absmiddle" class="apsw-img-views-today" alt="' . $msgViewsToday . '"/> ';
            $html .= '<div>' . $msgViewsToday . '</div>';
            $html .= '</div>';
            $html .= '<div class="apsw-post-views-value">' . $todayViews . '</div>';
            $html .= '<div style="clear:both"></div>';
            $html .= '</div>';

            $html .= '</div>';
            return do_shortcode($content) . $html;
        } else {
            return do_shortcode($content);
        }
    }

    /**
     * Register widgets
     */
    public function initWidgets() {
        register_widget('APSWAuthorStatistics');
        register_widget('APSWPopularPosts');
        register_widget('APSWPopularUsers');
    }

    /**
     * add view count for post/page or custom post types     
     */
    public function addPostView() {
        global $post;
        if (!is_object($post)) {
            $post = get_post();
        }
        $excludedPosts = $this->optionsSerialized->excludeViewByIds ? explode(',', $this->optionsSerialized->excludeViewByIds) : array();
        if ($post && is_object($post) && is_singular() && is_array($this->optionsSerialized->postTypes) && in_array($post->post_type, $this->optionsSerialized->postTypes) && !in_array($post->ID, $excludedPosts)) {
            $date = date('Y-m-d', APSWHelper::getTimeStamp());
            $postTypes = APSWHelper::getPostTypes($this->optionsSerialized->postTypes);
            $ip = APSWHelper::getRealIP();
            $isViewByIP = $this->optionsSerialized->isPostViewByIp;
            $this->dbManager->addPostView($post->ID, $date, $postTypes, $ip, $isViewByIP);
        }
    }

    /**
     * Scripts and styles registration on administration pages
     */
    public function optionsPageFiles() {
        $optionVars = array();
        $optionVars['url'] = admin_url('admin-ajax.php');
        $optionVars['confirmDelete'] = __('Are you sure', 'author-and-post-statistic-widgets');

        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-theme-options', plugins_url(APSW_DIR_NAME . '/assets/third-party/jquery-ui-themes/smoothness/jquery-ui.min.css'), null, $this->version);

        wp_register_script('apsw-cookie-js', plugins_url(APSW_DIR_NAME . '/assets/third-party/jquery-cookie/jquery.cookie.min.js'), array('jquery'), $this->version, false);
        wp_enqueue_script('apsw-cookie-js');
        wp_register_script('apsw-ert-js', plugins_url(APSW_DIR_NAME . '/assets/third-party/easy-responsive-tabs/js/easy-responsive-tabs.min.js'), array('jquery'), $this->version);
        wp_enqueue_script('apsw-ert-js');
        wp_register_style('apsw-ert-css', plugins_url(APSW_DIR_NAME . '/assets/third-party/easy-responsive-tabs/css/easy-responsive-tabs.min.css'), null, $this->version);
        wp_enqueue_style('apsw-ert-css');

        wp_enqueue_style('apsw-options-css', plugins_url(APSW_DIR_NAME . '/assets/css/options.css'), null, $this->version);
        wp_enqueue_script('apsw-options-js', plugins_url(APSW_DIR_NAME . '/assets/js/options.js'), array('jquery'), $this->version, false);
        wp_localize_script('apsw-options-js', 'apswOptionVars', $optionVars);
    }

    /**
     * Styles and scripts registration to use on front page
     */
    public function frontendFiles() {
        $apswJs = array(
            'url' => admin_url('admin-ajax.php'),
            'widgetsStyle' => $this->optionsSerialized->isStatsTogether,
        );

        if ($this->optionsSerialized->isStatsTogether == 1) {
            wp_register_style('font-awesome', plugins_url(APSW_DIR_NAME . '/assets/third-party/font-awesome-4.6.1/css/font-awesome.min.css'), null, $this->version);
            wp_enqueue_style('font-awesome');
            wp_register_script('apsw-ert-js', plugins_url(APSW_DIR_NAME . '/assets/third-party/easy-responsive-tabs/js/easy-responsive-tabs.min.js'), array('jquery'), $this->version);
            wp_enqueue_script('apsw-ert-js');
            wp_register_style('apsw-ert-css', plugins_url(APSW_DIR_NAME . '/assets/third-party/easy-responsive-tabs/css/easy-responsive-tabs.min.css'), null, $this->version);
            wp_enqueue_style('apsw-ert-css');
        }

        wp_register_script('apsw-cookie-js', plugins_url(APSW_DIR_NAME . '/assets/third-party/jquery-cookie/jquery.cookie.min.js'), array('jquery'), $this->version, false);
        wp_enqueue_script('apsw-cookie-js');
        wp_register_style('apsw-frontend-css', plugins_url(APSW_DIR_NAME . '/assets/css/frontend.css'), null, $this->version);
        wp_enqueue_style('apsw-frontend-css');
        wp_register_script('apsw-frontend-js', plugins_url(APSW_DIR_NAME . '/assets/js/frontend.js'), array('jquery'), $this->version);
        wp_localize_script('apsw-frontend-js', 'apswJs', $apswJs);
        wp_enqueue_script('apsw-frontend-js');
    }

    /**
     * register options page for plugin
     */
    public function optionsPage() {
        add_menu_page('Statistics Widgets', 'Statistics Widgets', 'manage_options', self::APSW_PAGE_OPTIONS, array(&$this->options, 'optionsForm'), plugins_url(APSW_DIR_NAME . '/assets/img/plugin-icon/statistics-icon-20.png'), 80);
    }

    /**
     * delete statistics between to date via ajax
     */
    public function deleteStatistics() {
        $msg = array('code' => 0);
        $all = isset($_POST['deleteAll']) ? $_POST['deleteAll'] : 0;
        $deleteAll = $all == 'true';
        $interval = APSWHelper::getDateIntervals(-1);
        if ($deleteAll) {
            $from = $interval['from'];
            $to = $interval['to'];
        } else {
            $from = isset($_POST['from']) ? $_POST['from'] : '';
            $to = isset($_POST['to']) ? $_POST['to'] : '';
        }

        if ($deleteAll || ($from && $to)) {
            if ($this->dbManager->deleteStatisticsByInterval($deleteAll, $from, $to)) {
                $msg['code'] = 1;
                $msg['msg'] = __('Deleted successfully', 'author-and-post-statistic-widgets');
            } else {
                $msg['msg'] = __('Failed to delete', 'author-and-post-statistic-widgets');
            }
        } else {
            $msg['msg'] = __('You must select date interval', 'author-and-post-statistic-widgets');
        }
        wp_die(json_encode($msg));
    }

    // Add settings link on plugin page
    public function settingsLink($links) {
        $settings_link = '<a href="' . admin_url() . 'admin.php?page=' . self::APSW_PAGE_OPTIONS . '">' . __('Settings', 'default') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * author and post statistic widget
     */
    public function postAndUserStatisticWidget($last = -1, $post) {
        $currentUser = null;
        if ($this->optionsSerialized->isShowLoggedInUserStatistic) {
            $currentUser = wp_get_current_user();
        }

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
        include 'widget/layouts/author-statistics.php';
    }

    /**
     * popular posts widget
     */
    public function ppWidget($last) {
        $interval = APSWHelper::getDateIntervals($last);
        $from = isset($interval['from']) && $interval['from'] ? $interval['from'] : date('Y-m-d', 1);
        $to = isset($interval['to']) && $interval['to'] ? $interval['to'] : APSWHelper::getMysqlDate();
        $isShowThumbnail = $this->optionsSerialized->ppIsShowThumbnail ? $this->optionsSerialized->ppIsShowThumbnail : 1;
        $thumbnailSize = $this->optionsSerialized->ppThumbnailSize ? $this->optionsSerialized->ppThumbnailSize : 32;
        $limit = $this->optionsSerialized->ppPostsLimit && ($l = absint($this->optionsSerialized->ppPostsLimit)) ? $l : 10;
        $postTypes = $this->optionsSerialized->postTypes && is_array($this->optionsSerialized->postTypes) ? APSWHelper::getPostTypes($this->optionsSerialized->postTypes) : APSWHelper::getPostTypes();
        $excludePostIds = $this->optionsSerialized->ppExcludeByPostIds ? trim($this->optionsSerialized->ppExcludeByPostIds, ',') : 0;
        $excludeArgs = array('postIds' => $excludePostIds);
        include 'widget/layouts/popular-posts.php';
    }

    /**
     * popular users widget
     */
    public function puWidget($last) {
        $interval = APSWHelper::getDateIntervals($last);
        $from = isset($interval['from']) && $interval['from'] ? $interval['from'] : date('Y-m-d', 1);
        $to = isset($interval['to']) && $interval['to'] ? $interval['to'] : APSWHelper::getMysqlDate();
        $isShowAvatar = $this->optionsSerialized->puIsShowAvatar ? $this->optionsSerialized->puIsShowAvatar : 0;
        $avatarSize = $this->optionsSerialized->puAvatarSize ? $this->optionsSerialized->puAvatarSize : 48;
        $limit = $this->optionsSerialized->puUsersLimit && ($l = absint($this->optionsSerialized->puUsersLimit)) ? $l : 10;
        $postTypes = $this->optionsSerialized->postTypes && is_array($this->optionsSerialized->postTypes) ? APSWHelper::getPostTypes($this->optionsSerialized->postTypes) : APSWHelper::getPostTypes();
        $excludeUserIds = $this->optionsSerialized->puExcludeByUserIds ? trim($this->optionsSerialized->puExcludeByUserIds, ',') : 0;
        $excludeArgs = array('userIds' => $excludeUserIds);
        include 'widget/layouts/popular-users.php';
    }

}

$apswCore = new APSWCore();

/**
 * display posts and users widget
 */
function apsw_pu_widget($last = -1) {
    global $apswCore, $post;
    $apswCore->postAndUserStatisticWidget($last, $post);
}

/**
 * display popular posts list for last X days
 * examples 
 *     set last = -1 to display statistics for all time
 *     set last = 0 to display statistics for current day
 *     set last = 7  to display statistics for a week (30 for a month, etc...)
 */
function apsw_pp_dynamic_date_widget($last = -1) {
    global $apswCore;
    $apswCore->ppWidget($last);
}

/**
 * display popular authors list for last X days
 * examples 
 *     set last = -1 to display statistics for all time
 *     set last = 0 to display statistics for current day
 *     set last = 7  to display statistics for a week (30 for a month, etc...)
 */
function apsw_pa_dynamic_date_widget($last = -1) {
    global $apswCore;
    $apswCore->puWidget($last);
}

/**
 * This function was removed since 2.0.0 version
 */
function apsw_pp_static_date_widget($from = '', $to = '') {
    echo '<h2>Deprecated</h2>';
}

/**
 * This function was removed since 2.0.0 version
 */
function apsw_au_static_date_widget($from = '', $to = '') {
    echo '<h2>Deprecated</h2>';
}
