<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div class="apsw-tab">
    <h3 class="apsw-tab-title"><?php echo $this->optionsSerialized->puPostsViewsCountTitle; ?></h3>
    <div class="apsw-block apsw-popular-users">
        <ul class="apsw-popular-users-list">
            <?php
            $popularUsers = $this->dbManager->popularUsersByPostsViews($from, $to, $postTypes, $limit, $excludeArgs);
            if ($popularUsers) {
                foreach ($popularUsers as $user) {
                    $id = $user['user_id'];
                    $viewCount = intval($user['view_count']);
                    $name = $user['name'];
                    $profileUrl = get_author_posts_url($id);
                    $lineHeight = $avatarSize . 'px';
                    ?>
                    <li class="apsw-list-item">
                        <div class="apsw-wrap">
                            <a class="apsw-profile-url" href="<?php echo $profileUrl; ?>" title="<?php echo __('View user profile page', 'author-and-post-statistic-widgets'); ?>" target="_blank">
                                <?php
                                if ($isShowAvatar) {
                                    echo '<div>' . get_avatar($id, $avatarSize) . '</div>';
                                }
                                ?>
                                <span><?php echo $name; ?></span>
                            </a>
                        </div>
                        <div class="apsw-info apsw-wrap">
                            <div class="apsw-info-value"><?php echo $viewCount; ?></div>
                            <img src="<?php echo plugins_url(APSW_DIR_NAME . '/assets/img/icon_views.png') ?>" align="absmiddle" class="apsw-info-img" />
                        </div>
                    </li>
                    <?php
                }
            } else {
                ?>
                <li class="apsw-list-item"><?php _e('There are no data for now', 'author-and-post-statistic-widgets'); ?></li>
                    <?php
                }
                ?>
        </ul>
    </div>
</div>