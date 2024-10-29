<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins apsw-widget-tabs" style="margin-top:10px; border:none;" width="75">
        <tbody>
            <tr valign="top">
                <th><label for="isShowLoggedInUserStatistic"><?php _e('Show logged in user statistic', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="checkbox" <?php checked($this->optionsSerialized->isShowLoggedInUserStatistic == 1) ?> value="1" name="isShowLoggedInUserStatistic" id="isShowLoggedInUserStatistic" /></td>
            </tr>
            <tr valign="top">
                <th><label for="apIsShowAvatar"><?php _e('Show author avatar and username', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="checkbox" <?php checked($this->optionsSerialized->apIsShowAvatar == 1) ?> value="1" name="apIsShowAvatar" id="apIsShowAvatar" /></td>
            </tr>
            <tr valign="top">
                <th><label for="apAvatarSize"><?php _e('Author avatar size', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->apAvatarSize; ?>" name="apAvatarSize" id="apAvatarSize" /></td>
            </tr>            
            <tr valign="top">
                <th><label for="apIsShowThumbnail"><?php _e('Show author popular posts thumbnail', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="checkbox" <?php checked($this->optionsSerialized->apIsShowThumbnail == 1) ?> value="1" name="apIsShowThumbnail" id="apIsShowThumbnail" /></td>
            </tr>
            <tr valign="top">
                <th><label for="apThumbnailSize"><?php _e('Author popular posts thumbnail size', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->apThumbnailSize; ?>" name="apThumbnailSize" id="apThumbnailSize" /></td>
            </tr>
            <tr valign="top">
                <th><label for="apPostsLimit"><?php _e('Author popular posts limit', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->apPostsLimit; ?>" name="apPostsLimit" id="apPostsLimit" /></td>
            </tr>
            <tr valign="top">
                <th><label for="apExcludeByPostIds"><?php _e('Exclude posts by ID (comma separated)', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->apExcludeByPostIds; ?>" name="apExcludeByPostIds" id="apExcludeByPostIds" placeholder="1,2,3, etc..."/></td>
            </tr>
            <tr valign="top">
                <th><label for="apCurrentUserStatTitle"><?php _e('Current user statistic tab title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->apCurrentUserStatTitle; ?>" name="apCurrentUserStatTitle" id="apCurrentUserStatTitle" size="50"/></td>
            </tr>            
            <tr valign="top">
                <th><label for="apCurrentUserMostViewedSubtitle"><?php _e('Current user most viewed posts sub-title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->apCurrentUserMostViewedSubtitle; ?>" name="apCurrentUserMostViewedSubtitle" id="apCurrentUserMostViewedSubtitle" size="50"/></td>
            </tr>
            <tr valign="top">
                <th><label for="apCurrentUserPPMostCommentedSubtitle"><?php _e('Current user most commented posts sub-title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->apCurrentUserPPMostCommentedSubtitle; ?>" name="apCurrentUserPPMostCommentedSubtitle" id="apCurrentUserPPMostCommentedSubtitle" size="50"/></td>
            </tr>
            <tr valign="top">
                <th><label for="apPostAuthorStatTitle"><?php _e('Post author statistic tab title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->apPostAuthorStatTitle; ?>" name="apPostAuthorStatTitle" id="apPostAuthorStatTitle" size="50"/></td>
            </tr>            
            <tr valign="top">
                <th><label for="apPostAuthorMostViewedSubtitle"><?php _e('Post author most viewed posts sub-title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->apPostAuthorMostViewedSubtitle; ?>" name="apPostAuthorMostViewedSubtitle" id="apPostAuthorMostViewedSubtitle" size="50"/></td>
            </tr>
            <tr valign="top">
                <th><label for="apPostAuthorPPMostCommentedSubtitle"><?php _e('Post author most commented posts sub-title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->apPostAuthorPPMostCommentedSubtitle; ?>" name="apPostAuthorPPMostCommentedSubtitle" id="apPostAuthorPPMostCommentedSubtitle" size="50"/></td>
            </tr>
        </tbody>
    </table>
</div>