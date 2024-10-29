<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th><?php _e('Show widgets in:', 'author-and-post-statistic-widgets'); ?></th>
                <td>                                
                    <label>
                        <input type="radio" <?php checked($this->optionsSerialized->isStatsTogether == 1) ?> value="1" name="is_stats_together" id="is_stats_tabbed" />
                        <span><?php _e('Tabs', 'author-and-post-statistic-widgets'); ?></span>
                    </label><br/>
                    <label>
                        <input type="radio" <?php checked($this->optionsSerialized->isStatsTogether == 2) ?> value="2" name="is_stats_together" id="is_stats_separate" />
                        <span><?php _e('Separate blocks', 'author-and-post-statistic-widgets'); ?></span>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Count post view by', 'author-and-post-statistic-widgets'); ?></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="radio" <?php checked($this->optionsSerialized->isPostViewByIp == 1); ?> value="1" name="is_post_view_by_ip" id="is_post_view_by_ip" class=""/> 
                            <span><?php _e('IP (for each day)', 'author-and-post-statistic-widgets'); ?></span>
                        </label><br>
                        <label>
                            <input type="radio" <?php checked($this->optionsSerialized->isPostViewByIp == 2); ?> value="2" name="is_post_view_by_ip" id="is_post_view_by_page_reload" class="" /> 
                            <span><?php _e('Page Reload', 'author-and-post-statistic-widgets'); ?></span>
                        </label><br>                                    
                    </fieldset>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="is_display_daily_views"><?php _e("Display posts' daily views count", 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="checkbox" <?php checked($this->optionsSerialized->isDisplayDailyViews == 1) ?> value="1" name="is_display_daily_views" id="is_display_daily_views" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="excludeViewByIds"><?php _e("Exclude posts daily view statistics by ID (comma separated)", 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="text" value="<?php echo $this->optionsSerialized->excludeViewByIds; ?>" name="excludeViewByIds" id="excludeViewByIds" /></td>
            </tr>
            <tr valign="top">
                <th><?php _e('Create statistic for these post types', 'author-and-post-statistic-widgets'); ?></th>
                <td>                                
                    <?php
                    $postTypes = get_post_types(array('public' => true));
                    foreach ($postTypes as $type) {
                        $post_type_checked = in_array($type, $this->optionsSerialized->postTypes);
                        ?>
                        <label for="apsw_<?php echo $type; ?>" class="apsw-post-type">
                            <input type="checkbox" <?php checked($post_type_checked); ?> value="<?php echo $type; ?>" name="post_types[]" id="apsw_<?php echo $type; ?>" />
                            <span><?php echo $type; ?></span>
                        </label>
                        <?php
                    }
                    ?>
                </td>
            </tr>            
        </tbody>
    </table>
</div>