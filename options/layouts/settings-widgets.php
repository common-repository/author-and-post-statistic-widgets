<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row">
                    <div id="apsw-nestedtabs-widgets">
                        <ul class="resp-tabs-list apsw-nestedtabs-widgets">
                            <li><?php _e('Author & Post', 'author-and-post-statistic-widgets'); ?></li>
                            <li><?php _e('Popular Posts', 'author-and-post-statistic-widgets'); ?></li>
                            <li><?php _e('Popular Users', 'author-and-post-statistic-widgets'); ?></li>
                            <li><?php _e('Common', 'author-and-post-statistic-widgets'); ?></li>
                        </ul>
                        <div class="resp-tabs-container apsw-nestedtabs-widgets">
                            <?php
                            include_once 'widgets/layout-ap.php';
                            include_once 'widgets/layout-pp.php';
                            include_once 'widgets/layout-pu.php';
                            include_once 'widgets/layout-widgets-common.php';
                            ?>
                        </div>
                    </div>
                </th>
            </tr>
        </tbody>
    </table>
</div>