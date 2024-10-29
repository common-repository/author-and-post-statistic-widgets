<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div class="apsw-tab">
    <h3 class="apsw-tab-title"><?php echo $tabAPTitle; ?></h3>
    <div class="apsw-block apsw-author-and-post">
        <ul class="apsw-author-and-post-list">
            <?php
            $posts = $this->dbManager->getAuthorPostIds($userId, $postTypes, $from, $to, $excludeArgs);
            $userPostTypes = $this->dbManager->getAuthorPostTypes($userId, $postTypes, $from, $to, $excludeArgs);
            $commentsCount = $this->dbManager->getCommentsCount($userId, $postTypes, $from, $to, $excludeArgs);
            $taxonomies = wp_get_object_terms($posts, APSWHelper::$taxonomyTypes);
            $categoryList = array();
            $tagList = array();
            $taxonomyList = array();
            if ($taxonomies) {
                foreach ($taxonomies as $t) {
                    if ($t->taxonomy == 'category') {
                        $categoryList[] = $t;
                    } else if ($t->taxonomy == 'post_tag') {
                        $tagList[] = $t;
                    } else {
                        $taxonomyList[] = $t;
                    }
                }
            }
            $categoryList = apply_filters('apsw_taxonomy_category', $categoryList);
            $tagList = apply_filters('apsw_taxonomy_post_tag', $tagList);
            $taxonomyList = apply_filters('apsw_taxonomy_custom', $taxonomyList);
            if ($isShowAvatar) {
                $profileUrl = get_author_posts_url($userId);
                ?>
                <li class="apsw-list-item">
                    <div class="apsw-author-info">
                        <a class="apsw-profile-url" href="<?php echo $profileUrl; ?>" target="_blank">
                            <div><?php echo get_avatar($userId, $avatarSize); ?></div>
                            <span><?php echo $user->display_name; ?></span>
                        </a>
                    </div>
                </li>
            <?php } ?>
            <?php
            if ($userPostTypes) {
                global $wp_post_types;
                foreach ($userPostTypes as $userPostType) {
                    $postTypeObj = $wp_post_types[$userPostType['type']];
                    $postTypeCount = $userPostType['count'];
                    $postTypeLink = get_post_type_archive_link($userPostType['type']);
                    $postTypeLabel = sprintf(__('%s', 'author-and-post-statistic-widgets'), $postTypeObj->labels->name);
                    ?>
                    <li class="apsw-list-item">
                        <span class="apsw-item-label">
                            <?php if ($postTypeLink) { ?>
                                <a href="<?php echo $postTypeLink; ?>">
                                    <?php echo $postTypeLabel; ?>
                                </a>
                                <?php
                            } else {
                                echo $postTypeLabel;
                            }
                            ?>
                        </span>
                        <div class="apsw-info">
                            <span class="apsw-info-value"><?php echo $postTypeCount; ?></span>
                        </div>
                    </li>
                    <?php
                }
            } else {
                ?>
                <li class="apsw-list-item">
                    <span class="apsw-item-label"><?php _e('Posts', 'author-and-post-statistic-widgets'); ?></span>
                    <div class="apsw-info">
                        <span class="apsw-info-value">0</span>
                    </div>
                </li>
                <?php
            }
            ?>
            <li class="apsw-list-item">
                <span class="apsw-item-label"><?php _e('Comments', 'author-and-post-statistic-widgets'); ?></span>
                <div class="apsw-info">
                    <span class="apsw-info-value"><?php echo $commentsCount; ?></span>
                </div>
            </li>
            <?php if ($categoryList) { ?>
                <li class="apsw-list-item">
                    <span class="apsw-item-label"><?php _e('Categories', 'author-and-post-statistic-widgets'); ?></span>
                    <div class="apsw-info">
                        <span class="apsw-info-value"><?php echo count($categoryList); ?></span>
                    </div>
                </li>
                <li class="apsw-list-item">
                    <ul class="apsw-sub-list apsw-category-list">
                        <?php foreach ($categoryList as $category) { ?>                            
                            <li class="apsw-list-item">
                                <a href="<?php echo get_category_link($category); ?>" title="<?php echo __('View all posts in ', 'author-and-post-statistic-widgets') . ' ' . $category->name; ?>">
                                    <span class="apsw-item-label"><?php echo $category->name; ?></span>
                                </a>,
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($tagList) { ?>
                <li class="apsw-list-item">
                    <span class="apsw-item-label"><?php _e('Tags', 'author-and-post-statistic-widgets'); ?></span>
                    <div class="apsw-info">
                        <span class="apsw-info-value"><?php echo count($tagList); ?></span>
                    </div>
                </li>
                <li class="apsw-list-item">
                    <ul class="apsw-sub-list apsw-tag-list">
                        <?php foreach ($tagList as $tag) { ?>                            
                            <li class="apsw-list-item">
                                <a href="<?php echo get_tag_link($tag); ?>" title="<?php echo __('View all posts in ', 'author-and-post-statistic-widgets') . $tag->name; ?>">
                                    <span class="apsw-item-label"><?php echo $tag->name; ?></span>
                                </a>,
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($taxonomyList) { ?>
                <li class="apsw-list-item">
                    <span class="apsw-item-label"><?php _e('Taxonomies', 'author-and-post-statistic-widgets'); ?></span>
                    <div class="apsw-info">
                        <span class="apsw-info-value"><?php echo count($taxonomyList); ?></span>
                    </div>
                </li>
                <li class="apsw-list-item">
                    <ul class="apsw-sub-list apsw-taxonomy-list">
                        <?php foreach ($taxonomyList as $taxonomy) { ?>                            
                            <li class="apsw-list-item">
                                <a href="<?php echo get_term_link($taxonomy); ?>" title="<?php echo __('View all posts in ', 'author-and-post-statistic-widgets') . ' ' . $taxonomy->name; ?>">
                                    <span class="apsw-item-label"><?php echo $taxonomy->name; ?></span>
                                </a>,
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>