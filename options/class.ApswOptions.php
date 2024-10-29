<?php

if (!defined('ABSPATH')) {
    exit();
}

class APSWOptions {

    private $optionsSerialized;

    public function __construct($optionsSerialized) {
        $this->optionsSerialized = $optionsSerialized;
    }

    /**
     * Builds options page
     */
    public function optionsForm() {
        if (isset($_POST['apsw_update_options'])) {
            if (!current_user_can('manage_options')) {
                die(_e('Hacker?', 'author-and-post-statistic-widgets'));
            }
            check_admin_referer(APSWCore::APSW_OPTIONS_FORM_NONCE);

            $this->optionsSerialized->isStatsTogether = isset($_POST['is_stats_together']) ? $_POST['is_stats_together'] : 1;
            $this->optionsSerialized->isPostViewByIp = isset($_POST['is_post_view_by_ip']) ? $_POST['is_post_view_by_ip'] : 1;
            $this->optionsSerialized->isDisplayDailyViews = isset($_POST['is_display_daily_views']) ? $_POST['is_display_daily_views'] : 0;
            $this->optionsSerialized->excludeViewByIds = isset($_POST['excludeViewByIds']) && ($ids = trim($_POST['excludeViewByIds'])) ? $ids : '';
            $this->optionsSerialized->postTypes = isset($_POST['post_types']) ? $_POST['post_types'] : APSWHelper::$postTypes;
            // author and post
            $this->optionsSerialized->isShowLoggedInUserStatistic = isset($_POST['isShowLoggedInUserStatistic']) ? $_POST['isShowLoggedInUserStatistic'] : 0;
            $this->optionsSerialized->apIsShowAvatar = isset($_POST['apIsShowAvatar']) ? $_POST['apIsShowAvatar'] : 0;
            $this->optionsSerialized->apAvatarSize = isset($_POST['apAvatarSize']) && ($size = absint($_POST['apAvatarSize'])) ? $size : 32;
            $this->optionsSerialized->apIsShowThumbnail = isset($_POST['apIsShowThumbnail']) ? $_POST['apIsShowThumbnail'] : 0;
            $this->optionsSerialized->apThumbnailSize = isset($_POST['apThumbnailSize']) && ($size = absint($_POST['apThumbnailSize'])) ? $size : 32;
            $this->optionsSerialized->apPostsLimit = isset($_POST['apPostsLimit']) && ($limit = absint($_POST['apPostsLimit'])) ? $limit : 10;
            $this->optionsSerialized->apExcludeByPostIds = isset($_POST['apExcludeByPostIds']) && ($postIds = trim($_POST['apExcludeByPostIds'])) ? $postIds : '';
            $this->optionsSerialized->apCurrentUserStatTitle = isset($_POST['apCurrentUserStatTitle']) && ($title = trim($_POST['apCurrentUserStatTitle'])) ? $title : '';
            $this->optionsSerialized->apCurrentUserMostViewedSubtitle = isset($_POST['apCurrentUserMostViewedSubtitle']) && ($title = trim($_POST['apCurrentUserMostViewedSubtitle'])) ? $title : '';
            $this->optionsSerialized->apCurrentUserPPMostCommentedSubtitle = isset($_POST['apCurrentUserPPMostCommentedSubtitle']) && ($title = trim($_POST['apCurrentUserPPMostCommentedSubtitle'])) ? $title : '';
            $this->optionsSerialized->apPostAuthorStatTitle = isset($_POST['apPostAuthorStatTitle']) && ($title = trim($_POST['apPostAuthorStatTitle'])) ? $title : '';
            $this->optionsSerialized->apPostAuthorMostViewedSubtitle = isset($_POST['apPostAuthorMostViewedSubtitle']) && ($title = trim($_POST['apPostAuthorMostViewedSubtitle'])) ? $title : '';
            $this->optionsSerialized->apPostAuthorPPMostCommentedSubtitle = isset($_POST['apPostAuthorPPMostCommentedSubtitle']) && ($title = trim($_POST['apPostAuthorPPMostCommentedSubtitle'])) ? $title : '';
            // popular posts
            $this->optionsSerialized->ppIsShowThumbnail = isset($_POST['ppIsShowThumbnail']) ? $_POST['ppIsShowThumbnail'] : 0;
            $this->optionsSerialized->ppThumbnailSize = isset($_POST['ppThumbnailSize']) && ($size = absint($_POST['ppThumbnailSize'])) ? $size : 32;
            $this->optionsSerialized->ppPostsLimit = isset($_POST['ppPostsLimit']) && ($limit = absint($_POST['ppPostsLimit'])) ? $limit : 10;
            $this->optionsSerialized->ppExcludeByPostIds = isset($_POST['ppExcludeByPostIds']) && ($postIds = trim($_POST['ppExcludeByPostIds'])) ? $postIds : '';
            $this->optionsSerialized->ppMostViewedPostsTitle = isset($_POST['ppMostViewedPostsTitle']) && ($title = trim($_POST['ppMostViewedPostsTitle'])) ? $title : '';
            $this->optionsSerialized->ppMostCommentedPostsTitle = isset($_POST['ppMostCommentedPostsTitle']) && ($title = trim($_POST['ppMostCommentedPostsTitle'])) ? $title : '';
            // popular users
            $this->optionsSerialized->puIsShowAvatar = isset($_POST['puIsShowAvatar']) ? $_POST['puIsShowAvatar'] : 0;
            $this->optionsSerialized->puAvatarSize = isset($_POST['puAvatarSize']) && ($size = absint($_POST['puAvatarSize'])) ? $size : 32;
            $this->optionsSerialized->puUsersLimit = isset($_POST['puUsersLimit']) && ($limit = absint($_POST['puUsersLimit'])) ? $limit : 10;
            $this->optionsSerialized->puExcludeByUserIds = isset($_POST['puExcludeByUserIds']) && ($userIds = trim($_POST['puExcludeByUserIds'])) ? $userIds : '';
            $this->optionsSerialized->puPostsCountTitle = isset($_POST['puPostsCountTitle']) && ($title = trim($_POST['puPostsCountTitle'])) ? $title : '';
            $this->optionsSerialized->puPostsViewsCountTitle = isset($_POST['puPostsViewsCountTitle']) && ($title = trim($_POST['puPostsViewsCountTitle'])) ? $title : '';
            $this->optionsSerialized->puPostsCommentsCountTitle = isset($_POST['puPostsCommentsCountTitle']) && ($title = trim($_POST['puPostsCommentsCountTitle'])) ? $title : '';
            // common
            $this->optionsSerialized->isDisplayCustomHtmlForWidgets = isset($_POST['is_display_custom_html_for_widgets']) ? $_POST['is_display_custom_html_for_widgets'] : 0;
            $this->optionsSerialized->postTitleLength = isset($_POST['postTitleLength']) && ($postTitleLength = absint($_POST['postTitleLength'])) ? $postTitleLength : 15;
            $this->optionsSerialized->postDefaultThumbnail = isset($_POST['postDefaultThumbnail']) && ($postThumbnail = trim($_POST['postDefaultThumbnail'])) ? $postThumbnail : '';
            $this->optionsSerialized->customCss = isset($_POST['custom_css']) ? $_POST['custom_css'] : '';
            $this->optionsSerialized->updateOptions();
        }
        include_once 'html-options.php';
    }

}
