<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div id="apswPopularPosts" class="apsw-widget apswPopularPosts">
    <?php if ($this->optionsSerialized->isStatsTogether == 1) { ?>
        <ul class="resp-tabs-list apsw-tab-popular-posts">
            <li><i class="fa fa-eye fa-lg">&nbsp;</i></li>
            <li><i class="fa fa-commenting-o fa-lg">&nbsp;</i></li>
        </ul>   
    <?php } ?>
    <div class="resp-tabs-container apsw-tab-popular-posts">
        <?php
        include 'popular-posts/by-posts-views.php';
        include 'popular-posts/by-posts-comments.php';
        ?>
    </div>
</div>