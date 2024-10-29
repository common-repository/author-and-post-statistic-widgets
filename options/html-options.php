<div class="wrap">
    <input type="hidden" id="statsCurrentTab" name="statsCurrentTab" value="0"/>
    <div style="float:left; width:40px; height:40px; margin:10px 10px 20px 0px;"><img src="<?php echo plugins_url(APSW_DIR_NAME . '/assets/img/plugin-icon/apsw-settings-icon.png'); ?>" style="width:40px;"/></div> <h1><?php _e('Statistics Widgets Settings', 'author-and-post-statistic-widgets'); ?></h1>
    <br style="clear:both" />

    <?php
    include_once 'layouts/go-to-pro.php';

    if (isset($_GET['reset_options']) && $_GET['reset_options'] == 1 && current_user_can('manage_options')) {
        delete_option(APSWCore::APSW_OPTIONS);
        $this->optionsSerialized->addOptions();
        $this->optionsSerialized->initOptions(get_option(APSWCore::APSW_OPTIONS));
    }
    ?>

    <form action="<?php echo admin_url(); ?>admin.php?page=<?php echo APSWCore::APSW_PAGE_OPTIONS; ?>" method="post" name="<?php echo APSWCore::APSW_PAGE_OPTIONS; ?>">
        <?php wp_nonce_field(APSWCore::APSW_OPTIONS_FORM_NONCE); ?>
        <div id="apswOptionsTabs">
            <ul class="resp-tabs-list apsw-tabidentify">
                <li><?php _e('General', 'author-and-post-statistic-widgets'); ?></li>
                <li><?php _e('Widgets', 'author-and-post-statistic-widgets'); ?></li>
                <li><?php _e('Styles', 'author-and-post-statistic-widgets'); ?></li>
                <li><?php _e('Reset Statistics', 'author-and-post-statistic-widgets'); ?></li>
                <li><?php _e('Functions and Shortcodes', 'author-and-post-statistic-widgets'); ?></li>
            </ul>                   
            <div class="resp-tabs-container apsw-tabidentify">
                <?php
                include 'layouts/settings-general.php';
                include 'layouts/settings-widgets.php';
                include 'layouts/settings-styles.php';
                include 'layouts/settings-reset-statistics.php';
                include 'layouts/support.php';
                ?>
            </div>           
        </div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <td>
                        <p class="submit">
                            <a class="button button-secondary" href="<?php echo admin_url(); ?>admin.php?page=<?php echo APSWCore::APSW_PAGE_OPTIONS; ?>&reset_options=1"><?php _e('Reset Options', 'author-and-post-statistic-widgets'); ?></a>
                            <input style="float: right;" type="submit" id="stats_save_options" class="button button-primary" name="apsw_update_options" value="<?php _e('Save Changes'); ?>" />
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="update" />                
    </form>
</div>
