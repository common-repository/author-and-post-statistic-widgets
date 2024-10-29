<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div id="apswPopularUsers" class="apsw-widget apswPopularUsers">
    <?php if ($this->optionsSerialized->isStatsTogether == 1) { ?>
        <ul class="resp-tabs-list apsw-tab-popular-users">
            <li><i class="fa fa-star fa-lg">&nbsp;</i></li>
            <li><i class="fa fa-eye fa-lg">&nbsp;</i></li>
            <li><i class="fa fa-commenting-o fa-lg">&nbsp;</i></li>
        </ul>
    <?php } ?>
    <div class="resp-tabs-container apsw-tab-popular-users">
        <?php
        include 'popular-users/by-posts-count.php';
        include 'popular-users/by-posts-views.php';
        include 'popular-users/by-posts-comments.php';
        ?>
    </div>
</div>