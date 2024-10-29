<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div id="apswAuthorAndPost" class="apsw-widget apswAuthorAndPost">
    <?php if ($this->optionsSerialized->isStatsTogether == 1) { ?>
        <ul class="resp-tabs-list apsw-tab-author-and-post">
            <li><i class="fa fa-user fa-lg">&nbsp;</i></li>
            <li><i class="fa fa-star fa-lg">&nbsp;</i></li>
        </ul>   
    <?php } ?>
    <div class="resp-tabs-container apsw-tab-author-and-post">
        <?php
        include 'author-statistics/author-statistics.php';
        include 'author-statistics/author-posts.php';
        ?>
    </div>
</div>