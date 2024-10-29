jQuery(document).ready(function ($) {
    var widgetsStyle = apswJs.widgetsStyle;
    if (widgetsStyle == 1) {
        $(function ($) {
            $('.apswAuthorAndPost').easyResponsiveTabs({tabidentify: 'apsw-tab-author-and-post'});
        });

        $(function ($) {
            $('.apswPopularPosts').easyResponsiveTabs({tabidentify: 'apsw-tab-popular-posts'});
        });

        $(function ($) {
            $('.apswPopularUsers').easyResponsiveTabs({tabidentify: 'apsw-tab-popular-users'});
        });
    }
});