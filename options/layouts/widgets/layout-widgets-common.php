<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins apsw-widget-tabs" style="margin-top:10px; border:none;" width="75">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="is_display_custom_html_for_widgets"><?php _e("Display widget custom html areas", 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="checkbox" <?php checked($this->optionsSerialized->isDisplayCustomHtmlForWidgets == 1) ?> value="1" name="is_display_custom_html_for_widgets" id="is_display_custom_html_for_widgets" /></td>
            </tr>
            <tr valign="top">
                <th><label for="postTitleLength"><?php _e('Posts title length in characters', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><input type="number" value="<?php echo $this->optionsSerialized->postTitleLength; ?>" name="postTitleLength" id="postTitleLength" />&nbsp;</td>
            </tr>
            <tr valign="top">
                <th><label for="postDefaultThumbnail"><?php _e('Posts default image if thumbnail doesn\'t exist', 'author-and-post-statistic-widgets'); ?></label></th>
                <td>                    
                    <input type="text" value="<?php echo $this->optionsSerialized->postDefaultThumbnail; ?>" name="postDefaultThumbnail" id="postDefaultThumbnail" size="50"/>&nbsp;
                    <button id="addPostDefaultThumbnail" class="button button-primary"><?php _e('Add image', 'author-and-post-statistic-widgets'); ?></button>
                </td>
            </tr>           
        </tbody>
    </table>
</div>