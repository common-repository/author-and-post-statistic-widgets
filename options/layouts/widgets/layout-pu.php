<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins apsw-widget-tabs" style="margin-top:10px; border:none;" width="75">
        <tbody>
            <tr valign="top">
                <th><label for="puIsShowAvatar"><?php _e('Show user avatar', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="checkbox" <?php checked($this->optionsSerialized->puIsShowAvatar == 1) ?> value="1" name="puIsShowAvatar" id="puIsShowAvatar" /></td>
            </tr>
            <tr valign="top">
                <th><label for="puAvatarSize"><?php _e('Avatar size', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->puAvatarSize; ?>" name="puAvatarSize" id="puAvatarSize" /></td>
            </tr>
            <tr valign="top">
                <th><label for="puUsersLimit"><?php _e('Popular users limit', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->puUsersLimit; ?>" name="puUsersLimit" id="puUsersLimit" /></td>
            </tr>
            <tr valign="top">
                <th><label for="puExcludeByUserIds"><?php _e('Exclude users by ID (comma separated)', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->puExcludeByUserIds; ?>" name="puExcludeByUserIds" id="puExcludeByUserIds" placeholder="1,2,3, etc..."/></td>
            </tr>                       
            <tr valign="top">
                <th><label for="puPostsCountTitle"><?php _e('Popular by posts count', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->puPostsCountTitle; ?>" name="puPostsCountTitle" id="puPostsCountTitle" size="50"/></td>
            </tr>
            <tr valign="top">
                <th><label for="puPostsViewsCountTitle"><?php _e('Popular by post views count', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->puPostsViewsCountTitle; ?>" name="puPostsViewsCountTitle" id="puPostsViewsCountTitle" size="50"/></td>
            </tr>
            <tr valign="top">
                <th><label for="puPostsCommentsCountTitle"><?php _e('Popular by post comments count', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->puPostsCommentsCountTitle; ?>" name="puPostsCommentsCountTitle" id="puPostsCommentsCountTitle" size="50"/></td>
            </tr>           
        </tbody>
    </table>
</div>