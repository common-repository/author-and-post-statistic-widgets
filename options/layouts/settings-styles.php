<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="custom_css"><?php _e('Custom CSS to include in header:', 'author-and-post-statistic-widgets'); ?></label></th>
                <td><textarea cols="50" rows="10" placeholder=".apsw-widget { font-size : 14px; }" id="custom_css" class="custom_css_area" name="custom_css"><?php echo $this->optionsSerialized->customCss; ?></textarea></td>
            </tr>           
        </tbody>
    </table>
</div>