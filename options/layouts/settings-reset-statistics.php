<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <td>
                    <fieldset>
                        <label for="from"><?php _e('From Date:', 'author-and-post-statistic-widgets'); ?></label>
                        <input type="text" class="fromdate" id="from" />
                        <label for="to"><?php _e('To Date:', 'author-and-post-statistic-widgets'); ?></label>
                        <input type="text" class="todate" id="to" />
                        <input type="checkbox" id="apsw-delete-all" value="1"/>
                        <label for="apsw-delete-all"><?php _e('Delete All Statistics', 'author-and-post-statistic-widgets'); ?></label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <input class="button button-secondary" type="button" value="Delete" id="apsw-delete-stats" />
                    <div id="apswDeleteResponse" style="display:inline;line-height:26px;padding:4px;">
                        <img style="display:none;" id="apsw-loader" width="24" height="24" src="<?php echo plugins_url(APSW_DIR_NAME . '/assets/img/ajax-loader-42x42.gif'); ?>" />
                        <span class="apsw-response-msg" style="display:none;"></span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>                        
</div>