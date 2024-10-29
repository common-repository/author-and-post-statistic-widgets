<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins apsw-widget-tabs" style="margin-top:10px; border:none;" width="75">
        <tbody>
            <tr valign="top">
                <th><label for="ppIsShowThumbnail"><?php _e('Show post thumbnail', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="checkbox" <?php checked($this->optionsSerialized->ppIsShowThumbnail == 1) ?> value="1" name="ppIsShowThumbnail" id="ppIsShowThumbnail" /></td>
            </tr>
            <tr valign="top">
                <th><label for="ppThumbnailSize"><?php _e('Thumbnail size', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->ppThumbnailSize; ?>" name="ppThumbnailSize" id="ppThumbnailSize" /></td>
            </tr>
            <tr valign="top">
                <th><label for="ppPostsLimit"><?php _e('Popular posts limit', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->ppPostsLimit; ?>" name="ppPostsLimit" id="ppPostsLimit" /></td>
            </tr>
            <tr valign="top">
                <th><label for="ppExcludeByPostIds"><?php _e('Exclude posts by ID (comma separated)', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->ppExcludeByPostIds; ?>" name="ppExcludeByPostIds" id="ppExcludeByPostIds" placeholder="1,2,3, etc..."/></td>
            </tr>
            <tr valign="top">
                <th><label for="ppMostViewedPostsTitle"><?php _e('Most viewed posts title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->ppMostViewedPostsTitle; ?>" name="ppMostViewedPostsTitle" id="ppMostViewedPostsTitle" size="50"/></td>
            </tr>
            <tr valign="top">
                <th><label for="ppMostCommentedPostsTitle"><?php _e('Most commented posts title', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->ppMostCommentedPostsTitle; ?>" name="ppMostCommentedPostsTitle" id="ppMostCommentedPostsTitle" size="50"/></td>
            </tr>            
        </tbody>
    </table>
</div>