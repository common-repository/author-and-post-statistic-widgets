<?php

if (!defined('ABSPATH')) {
    exit();
}

class APSWOptionsSerialized {
    /* General Options */

    public $isStatsTogether;
    public $isPostViewByIp;
    public $isDisplayDailyViews;
    public $excludeViewByIds;
    public $postTypes;
    /* Author and post widget */
    public $isShowLoggedInUserStatistic;
    public $apIsShowAvatar;
    public $apAvatarSize;
    public $apIsShowThumbnail;
    public $apThumbnailSize;
    public $apPostsLimit;
    public $apExcludeByPostIds;
    // if user logged in (current user titles)
    public $apCurrentUserStatTitle;
    public $apCurrentUserMostViewedSubtitle;
    public $apCurrentUserPPMostCommentedSubtitle;
    // if user not logged in (post author titles)
    public $apPostAuthorStatTitle;
    public $apPostAuthorMostViewedSubtitle;
    public $apPostAuthorPPMostCommentedSubtitle;

    /* Popular posts widget */
    public $ppIsShowThumbnail;
    public $ppThumbnailSize;
    public $ppPostsLimit;
    public $ppExcludeByPostIds;
    public $ppMostViewedPostsTitle;
    public $ppMostCommentedPostsTitle;
    /* Popular users widget */
    public $puIsShowAvatar;
    public $puAvatarSize;
    public $puUsersLimit;
    public $puExcludeByUserIds;
    public $puPostsCountTitle;
    public $puPostsViewsCountTitle;
    public $puPostsCommentsCountTitle;
    /* Common settings */
    public $isDisplayCustomHtmlForWidgets;
    public $postTitleLength;
    public $postDefaultThumbnail;
    /* Styles */
    public $customCss;

    function __construct() {
        $this->addOptions();
        $this->initOptions(get_option(APSWCore::APSW_OPTIONS));
    }

    public function addOptions() {
        $options = array(
            'is_stats_together' => 1,
            'is_post_view_by_ip' => 1,
            'is_display_daily_views' => 1,
            'excludeViewByIds' => '',
            'post_types' => array('post', 'page'),
            'isShowLoggedInUserStatistic' => 0,
            'apIsShowAvatar' => 1,
            'apAvatarSize' => 48,
            'apIsShowThumbnail' => 1,
            'apThumbnailSize' => 32,
            'apPostsLimit' => 10,
            'apExcludeByPostIds' => '',
            // if user logged in (current user titles)
            'apCurrentUserStatTitle' => __('My statistics', 'author-and-post-statistic-widgets'),
            'apCurrentUserMostViewedSubtitle' => __('My most viewed posts', 'author-and-post-statistic-widgets'),
            'apCurrentUserPPMostCommentedSubtitle' => __('My most commented posts', 'author-and-post-statistic-widgets'),
            // if user not logged in (post author titles)
            'apPostAuthorStatTitle' => __('Post author statistics', 'author-and-post-statistic-widgets'),
            'apPostAuthorMostViewedSubtitle' => __('Post author most viewed posts', 'author-and-post-statistic-widgets'),
            'apPostAuthorPPMostCommentedSubtitle' => __('Post author most commented posts', 'author-and-post-statistic-widgets'),
            'ppIsShowThumbnail' => 1,
            'ppThumbnailSize' => 32,
            'ppPostsLimit' => 10,
            'ppExcludeByPostIds' => '',            
            'ppMostViewedPostsTitle' => __('Most viewed posts', 'author-and-post-statistic-widgets'),
            'ppMostCommentedPostsTitle' => __('Most commented posts', 'author-and-post-statistic-widgets'),
            'puIsShowAvatar' => 1,
            'puAvatarSize' => 32,
            'puUsersLimit' => 10,
            'puExcludeByUserIds' => '',
            'puPostsCountTitle' => __('Popular users (posts count)', 'author-and-post-statistic-widgets'),
            'puPostsViewsCountTitle' => __('Popular users (posts views count)', 'author-and-post-statistic-widgets'),
            'puPostsCommentsCountTitle' => __('Popular users (posts comments count)', 'author-and-post-statistic-widgets'),
            // commmon
            'is_display_custom_html_for_widgets' => 1,
            'postTitleLength' => 15,
            'postDefaultThumbnail' => '',
            'custom_css' => '',
        );
        add_option(APSWCore::APSW_OPTIONS, $options);
    }

    public function initOptions($options) {
        $options = maybe_unserialize($options);
        // general
        $this->isStatsTogether = isset($options['is_stats_together']) ? $options['is_stats_together'] : 1;
        $this->isPostViewByIp = isset($options['is_post_view_by_ip']) ? $options['is_post_view_by_ip'] : 1;
        $this->isDisplayDailyViews = isset($options['is_display_daily_views']) ? $options['is_display_daily_views'] : 0;
        $this->excludeViewByIds = isset($options['excludeViewByIds']) && ($ids = trim($options['excludeViewByIds'])) ? $ids : '';
        $this->postTypes = isset($options['post_types']) ? $options['post_types'] : array('post', 'page');
        // author and post
        $this->isShowLoggedInUserStatistic = isset($options['isShowLoggedInUserStatistic']) ? $options['isShowLoggedInUserStatistic'] : 0;
        $this->apIsShowAvatar = isset($options['apIsShowAvatar']) ? $options['apIsShowAvatar'] : 0;
        $this->apAvatarSize = isset($options['apAvatarSize']) && ($size = absint($options['apAvatarSize'])) ? $size : 48;
        $this->apIsShowThumbnail = isset($options['apIsShowThumbnail']) ? $options['apIsShowThumbnail'] : 0;
        $this->apThumbnailSize = isset($options['apThumbnailSize']) && ($size = absint($options['apThumbnailSize'])) ? $size : 32;
        $this->apPostsLimit = isset($options['apPostsLimit']) && ($limit = absint($options['apPostsLimit'])) ? $limit : 10;
        $this->apExcludeByPostIds = isset($options['apExcludeByPostIds']) && ($postIds = trim($options['apExcludeByPostIds'])) ? $postIds : '';
        $this->apCurrentUserStatTitle = isset($options['apCurrentUserStatTitle']) && ($title = trim($options['apCurrentUserStatTitle'])) ? $title : '';
        $this->apCurrentUserMostViewedSubtitle = isset($options['apCurrentUserMostViewedSubtitle']) && ($title = trim($options['apCurrentUserMostViewedSubtitle'])) ? $title : '';
        $this->apCurrentUserPPMostCommentedSubtitle = isset($options['apCurrentUserPPMostCommentedSubtitle']) && ($title = trim($options['apCurrentUserPPMostCommentedSubtitle'])) ? $title : '';
        $this->apPostAuthorStatTitle = isset($options['apPostAuthorStatTitle']) && ($title = trim($options['apPostAuthorStatTitle'])) ? $title : '';
        $this->apPostAuthorMostViewedSubtitle = isset($options['apPostAuthorMostViewedSubtitle']) && ($title = trim($options['apPostAuthorMostViewedSubtitle'])) ? $title : '';
        $this->apPostAuthorPPMostCommentedSubtitle = isset($options['apPostAuthorPPMostCommentedSubtitle']) && ($title = trim($options['apPostAuthorPPMostCommentedSubtitle'])) ? $title : '';
        // popular posts
        $this->ppIsShowThumbnail = isset($options['ppIsShowThumbnail']) ? $options['ppIsShowThumbnail'] : 0;
        $this->ppThumbnailSize = isset($options['ppThumbnailSize']) && ($size = absint($options['ppThumbnailSize'])) ? $size : 32;
        $this->ppPostsLimit = isset($options['ppPostsLimit']) && ($limit = absint($options['ppPostsLimit'])) ? $limit : 10;
        $this->ppExcludeByPostIds = isset($options['ppExcludeByPostIds']) && ($postIds = trim($options['ppExcludeByPostIds'])) ? $postIds : '';        
        $this->ppMostViewedPostsTitle = isset($options['ppMostViewedPostsTitle']) && ($title = trim($options['ppMostViewedPostsTitle'])) ? $title : '';
        $this->ppMostCommentedPostsTitle = isset($options['ppMostCommentedPostsTitle']) && ($title = trim($options['ppMostCommentedPostsTitle'])) ? $title : '';
        // popular users
        $this->puIsShowAvatar = isset($options['puIsShowAvatar']) ? $options['puIsShowAvatar'] : 0;
        $this->puAvatarSize = isset($options['puAvatarSize']) && ($size = absint($options['puAvatarSize'])) ? $size : 32;
        $this->puUsersLimit = isset($options['puUsersLimit']) && ($limit = absint($options['puUsersLimit'])) ? $limit : 10;
        $this->puExcludeByUserIds = isset($options['puExcludeByUserIds']) && ($userIds = trim($options['puExcludeByUserIds'])) ? $userIds : '';        
        $this->puPostsCountTitle = isset($options['puPostsCountTitle']) && ($title = trim($options['puPostsCountTitle'])) ? $title : '';
        $this->puPostsViewsCountTitle = isset($options['puPostsViewsCountTitle']) && ($title = trim($options['puPostsViewsCountTitle'])) ? $title : '';
        $this->puPostsCommentsCountTitle = isset($options['puPostsCommentsCountTitle']) && ($title = trim($options['puPostsCommentsCountTitle'])) ? $title : '';
        //common
        $this->isDisplayCustomHtmlForWidgets = isset($options['is_display_custom_html_for_widgets']) ? $options['is_display_custom_html_for_widgets'] : 0;
        $this->postTitleLength = isset($options['postTitleLength']) && ($postTitleLength = absint($options['postTitleLength'])) ? $postTitleLength : 15;
        $this->postDefaultThumbnail = isset($options['postDefaultThumbnail']) && ($postThumbnail = trim($options['postDefaultThumbnail'])) ? $postThumbnail : '';
        $this->customCss = $options['custom_css'];
    }

    public function updateOptions() {
        update_option(APSWCore::APSW_OPTIONS, $this->toArray());
    }

    public function toArray() {
        $options = array(
            'is_stats_together' => $this->isStatsTogether,
            'is_post_view_by_ip' => $this->isPostViewByIp,
            'is_display_daily_views' => $this->isDisplayDailyViews,
            'excludeViewByIds' => $this->excludeViewByIds,
            'post_types' => $this->postTypes,
            // author and post
            'isShowLoggedInUserStatistic' => $this->isShowLoggedInUserStatistic,
            'apIsShowAvatar' => $this->apIsShowAvatar,
            'apAvatarSize' => $this->apAvatarSize,
            'apIsShowThumbnail' => $this->apIsShowThumbnail,
            'apThumbnailSize' => $this->apThumbnailSize,
            'apPostsLimit' => $this->apPostsLimit,
            'apExcludeByPostIds' => $this->apExcludeByPostIds,
            'apCurrentUserStatTitle' => $this->apCurrentUserStatTitle,
            'apCurrentUserMostViewedSubtitle' => $this->apCurrentUserMostViewedSubtitle,
            'apCurrentUserPPMostCommentedSubtitle' => $this->apCurrentUserPPMostCommentedSubtitle,
            'apPostAuthorStatTitle' => $this->apPostAuthorStatTitle,
            'apPostAuthorMostViewedSubtitle' => $this->apPostAuthorMostViewedSubtitle,
            'apPostAuthorPPMostCommentedSubtitle' => $this->apPostAuthorPPMostCommentedSubtitle,
            // popular posts
            'ppIsShowThumbnail' => $this->ppIsShowThumbnail,
            'ppThumbnailSize' => $this->ppThumbnailSize,
            'ppPostsLimit' => $this->ppPostsLimit,
            'ppExcludeByPostIds' => $this->ppExcludeByPostIds,
            'ppMostViewedPostsTitle' => $this->ppMostViewedPostsTitle,
            'ppMostCommentedPostsTitle' => $this->ppMostCommentedPostsTitle,
            // popular users
            'puIsShowAvatar' => $this->puIsShowAvatar,
            'puAvatarSize' => $this->puAvatarSize,
            'puUsersLimit' => $this->puUsersLimit,
            'puExcludeByUserIds' => $this->puExcludeByUserIds,            
            'puPostsCountTitle' => $this->puPostsCountTitle,
            'puPostsViewsCountTitle' => $this->puPostsViewsCountTitle,
            'puPostsCommentsCountTitle' => $this->puPostsCommentsCountTitle,
            // common
            'is_display_custom_html_for_widgets' => $this->isDisplayCustomHtmlForWidgets,
            'postTitleLength' => $this->postTitleLength,
            'postDefaultThumbnail' => $this->postDefaultThumbnail,
            'custom_css' => $this->customCss
        );
        return $options;
    }

}
