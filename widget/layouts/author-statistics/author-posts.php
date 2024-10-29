<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div class="apsw-tab">
    <div class="apsw-block apsw-author-and-post">
        <h3 class="apsw-tab-title"><?php echo $subTitleMostViewed; ?></h3>
        <ul class="apsw-author-and-post-list">
            <?php
            $ppByViews = $this->dbManager->getAuthorPopularPostsByViews($userId, $from, $to, $postTypes, $limit, $excludeArgs);
            if ($ppByViews) {
                foreach ($ppByViews as $post) {
                    $id = $post['post_id'];
                    $viewCount = intval($post['view_count']);
                    $title = APSWHelper::getSubstringedString($post['title']);
                    $postUrl = get_permalink($id);
                    ?>
                    <li class="apsw-list-item">
                        <div class="apsw-url apsw-wrap">
                            <a class="apsw-post-url" href="<?php echo $postUrl; ?>" title="<?php echo __('Permalink to', 'author-and-post-statistic-widgets') . ' ' . $post['title']; ?>" target="_blank">
                                <?php
                                if ($isShowThumbnail) {
                                    $maxHeight = $thumbnailSize . 'px';
                                    $style = "min-height:$maxHeight;max-height:$maxHeight;max-width:$maxHeight;";
                                    $thumb = '';
                                    if (has_post_thumbnail($id)) {
                                        $attachs = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'thumbnail');
                                        if ($attachs) {
                                            $thumb = $attachs[0];
                                        }
                                    }
                                    if (!$thumb) {
                                        if ($this->optionsSerialized->postDefaultThumbnail) {
                                            $thumb = $this->optionsSerialized->postDefaultThumbnail;
                                        } else {
                                            $thumb = apply_filters('apsw_post_thumbnail', plugins_url(APSW_DIR_NAME . '/assets/img/icon_thumb_default.png'), $id);
                                        }
                                    }
                                    ?>
                                    <div><img src="<?php echo $thumb; ?>" style="<?php echo $style ?>" height="<?php echo $thumbnailSize; ?>" width="<?php echo $thumbnailSize; ?>" class='attachment-post-thumbnail size-post-thumbnail wp-post-image apsw-post-thumb' /></div>
                                    <?php
                                }
                                ?>                        
                                <span><?php echo $title; ?></span>
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
    <div class="apsw-block apsw-author-and-post">
        <h3 class="apsw-tab-title"><?php echo $subTitleMostCommented; ?></h3>
        <ul class="apsw-author-and-post-list">
            <?php
            $ppByComments = $this->dbManager->getAuthorPopularPostsByComments($userId, $from, $to, $postTypes, $limit, $excludeArgs);
            if ($ppByComments) {
                foreach ($ppByComments as $post) {
                    $id = $post['post_id'];
                    $viewCount = intval($post['comment_count']);
                    $title = APSWHelper::getSubstringedString($post['title']);
                    $postUrl = get_permalink($id);
                    ?>
                    <li class="apsw-list-item">
                        <div class="apsw-url apsw-wrap">
                            <a class="apsw-post-url" href="<?php echo $postUrl; ?>" title="<?php echo __('Permalink to', 'author-and-post-statistic-widgets') . ' ' . $post['title']; ?>" target="_blank">
                                <?php
                                if ($isShowThumbnail) {
                                    $maxHeight = $thumbnailSize . 'px';
                                    $style = "min-height:$maxHeight;max-height:$maxHeight;max-width:$maxHeight;";
                                    $thumb = '';
                                    if (has_post_thumbnail($id)) {
                                        $attachs = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'thumbnail');
                                        if ($attachs) {
                                            $thumb = $attachs[0];
                                        }
                                    }
                                    if (!$thumb) {
                                        if ($this->optionsSerialized->postDefaultThumbnail) {
                                            $thumb = $this->optionsSerialized->postDefaultThumbnail;
                                        } else {
                                            $thumb = apply_filters('apsw_post_thumbnail', plugins_url(APSW_DIR_NAME . '/assets/img/icon_thumb_default.png'), $id);
                                        }
                                    }
                                    ?>
                                    <div><img src="<?php echo $thumb; ?>" style="<?php echo $style ?>" height="<?php echo $thumbnailSize; ?>" width="<?php echo $thumbnailSize; ?>" class='attachment-post-thumbnail size-post-thumbnail wp-post-image apsw-post-thumb' /></div>
                                    <?php
                                }
                                ?>
                                <span><?php echo $title; ?></span>
                            </a>
                        </div>                        
                        <div class="apsw-info apsw-wrap">
                            <div class="apsw-info-value"><?php echo $viewCount; ?></div>
                            <img src="<?php echo plugins_url(APSW_DIR_NAME . '/assets/img/icon_comments.png') ?>" align="absmiddle" class="apsw-info-img" />
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