<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="form-table">
        <tbody>
            <tr>
                <td align="center" colspan="2"  style="text-align:left">

                    <h3 style="padding-top:0px; margin-top:1px;">APSW Template tags ( Free &amp; <a href="http://www.gvectors.com/author-and-post-statistic-widgets/" style="text-decoration:none;">Pro</a> Versions )</h3>
                    <p style="font-size:15px;">You can use these template tags add statistic information directly in template files.</p>

                    <br/>
                    <strong>
                        The APSW PRO version is more customizable. <br/>
                        You can pass array of arguments to functions below to make it more flexible!
                    </strong>

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;&lt;?php if (function_exists('apsw_pu_widget')) { apsw_pu_widget($last = -1) } ?&gt;&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Displays Post author statistic and Popular posts widget</h4>
                    Options:
                    <ul>
                        <li>set $last = 1   to display popular posts for yesterday</li>
                        <li>set $last = 7   to display popular posts for past week</li> 
                        <li>set $last = 30  to display popular posts for past month</li>
                        <li>set $last = 0   to display popular posts for current day</li>
                        <li>set $last = -1  or empty to display popular posts for all time</li>
                    </ul>                   

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;&lt;?php if (function_exists('apsw_pp_dynamic_date_widget')) { apsw_pp_dynamic_date_widget($last = -1) } ?&gt;&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Display popular posts list for last X days</h4>
                    Options:
                    <ul>
                        <li>set $last = 1   to display popular posts for yesterday</li>
                        <li>set $last = 7   to display popular posts for past week</li> 
                        <li>set $last = 30  to display popular posts for past month</li>
                        <li>set $last = 0   to display popular posts for current day</li>
                        <li>set $last = -1  or empty to display popular posts for all time</li>
                    </ul>

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;&lt;?php if (function_exists('apsw_pa_dynamic_date_widget')) { apsw_pa_dynamic_date_widget($last = -1) } ?&gt;&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Displays popular authors list for last X days</h4>
                    Options:
                    <ul>
                        <li>set $last = 1  to display popular authors for yesterday</li>
                        <li>set $last = 7  to display popular authors for past week </li>
                        <li>set $last = 30 to display popular authors for past month</li>
                        <li>set $last = 0  to display popular authors for current day</li>
                        <li>set $last = -1 or empty to display popular authors for all time</li>
                    </ul>

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;
    add_filter('apsw_post_thumbnail', 'change_thumb_for_post_type', 2, 2); 

    function change_thumb_for_post_type($t, $id) {
        $p = get_post($id);
        if ($p && $p->post_type == 'post') {
            $t = '...image url here...';
        } else if ($p && $p->post_type == 'page') {
            $t = '...image url here...';
        } else {
            $t = '...image url here...';
        }
        return $t;
    }&nbsp;
                    </pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Filter for posts default thumbnail by post types ( works only if the post default thumbnail not set in options page! )</h4>
                    <p>Put this code in your active theme functions.php file</p>
                    
                    
                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;
        //Example - Author and Post Widget (set for last 45 days)
        add_filter('apsw_author_and_post_last', 'custom_author_and_post_last');
        function custom_author_and_post_last($last) {
            $last = 45;
            return $last;
        }

        //Example - Popular Posts Widget (set for last 20 days)
        add_filter('apsw_popular_posts_last', 'custom_popular_posts_last');
        function custom_popular_posts_last($last) {
            $last = 20;
            return $last;
        }
        
        //Example - Popular Users Widget (set for last 730 days)
        add_filter('apsw_popular_users_last', 'custom_popular_users_last');
        function custom_popular_users_last($last) {
            $last = 730;
            return $last;
        }
                    </pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Filters for widgets custom last X days</h4>
                    <p>Now you can set custom time interval for stat widgets. Put according code in current active theme's functions.php file.</p>
                    

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;            
    // Hide category taxonomy from author and post widget
    add_filter('apsw_taxonomy_category', 'apsw_taxonomy_category');
    if (!function_exists('apsw_taxonomy_category')) {
        function apsw_taxonomy_category($categoryList) {
            return null;
        }
    }

    // Hide post_tag taxonomy from author and post widget
    add_filter('apsw_taxonomy_post_tag', 'apsw_taxonomy_post_tag');
    if (!function_exists('apsw_taxonomy_post_tag')) {
        function apsw_taxonomy_post_tag($tagList) {
            return null;
        }
    }

    // Hide custom taxonomies from author and post widget
    add_filter('apsw_taxonomy_custom', 'apsw_taxonomy_custom');
    if (!function_exists('apsw_taxonomy_custom')) {
        function apsw_taxonomy_custom($taxonomyList) {
            return null;
        }
    }
                    </pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Hide taxonomies</h4>
                    <p>You can hide taxonomies from author and post statistic widget using code above. Put according code in current active theme's functions.php file.</p>

                    <br />

                    <h3>APSW Shortcodes ( <a href="http://www.gvectors.com/author-and-post-statistic-widgets/" style="text-decoration:none;">Pro</a> Version )</h3>
                    <p style="font-size:15px;">You can use these shortcodes to display different statistic information directly on posts and pages.</p>

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;[apsw_postviews last="7" user="21" post_types="post,page" width="400" height="300"]&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Displays post views of certain user for certain date period.</h4>
                    <strong>"last"</strong> - the number of past days<br />
                    If this attribute is not set, it displays post views for all time.<br />
                    If this attribute is set "0", it displays post views for current day.<br />
                    <strong>"user"</strong> - user id<br />
                    If this attribute is not set this shortcode displays current logged in users' post views statistic<br />
                    <strong>"post_types"</strong> - comma separated post types(post,page, etc...)<br />
                    If this attribute is not set, then default post types will be applied (post, page)<br />
                    <strong>"width"</strong> - the width of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (400px)<br />
                    <strong>"height"</strong> - the height of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (300px)

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;[apsw_postcount last="7" user="21" post_types="post,page" width="400" height="300"]&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Displays posts count of certain user for certain date period.</h4>
                    <strong>"last"</strong> - the number of past days<br />
                    If this attribute is not set, it displays posts count for all time.<br />
                    If this attribute is set "0", it displays posts count for current day.<br />
                    <strong>"user"</strong> - user id<br />
                    If this attribute is not set this shortcode displays current logged in users' posts count statistic<br />
                    <strong>"post_types"</strong> - comma separated post types(post,page, etc...)<br />
                    If this attribute is not set, then default post types will be applied (post, page)<br />
                    <strong>"width"</strong> - the width of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (400px)<br />
                    <strong>"height"</strong> - the height of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (300px)<br />

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;[apsw_popularposts last="7" user="21" by="comments" post_types="post,page" width="400", "300"]&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Displays popular posts of certain user for certain date period based on post comments or views.</h4>
                    <strong>"last"</strong> - the number of past days<br />
                    If this attribute is not set, it displays popular posts for all time.<br />
                    If this attribute is set "0", it displays popular posts for current day.<br />
                    <strong>"user"</strong> - user id<br />
                    If this attribute is not set, it displays current logged in users' popular posts statistic<br />
                    <strong>"by"</strong> - the base for counting and choosing popular posts ( values: "comments" or "views" )<br />
                    If this attribute is not set, it takes "comments" as a base.<br />
                    <strong>"post_types"</strong> - comma separated post types(post,page, etc...)<br />
                    If this attribute is not set, then default post types will be applied (post, page)<br />
                    <strong>"width"</strong> - the width of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (400px)<br />
                    <strong>"height"</strong> - the height of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (300px)<br />

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;[apsw_activeusers last="7" user="21" by="posts" post_types="post,page" width="400" height="300"]&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Displays most popular users statistic for certain date period based on users' posts, comments, or posts' views.</h4>
                    <strong>"last"</strong> - the number of past days<br />
                    If this attribute is not set, it displays popular users for all time.<br />
                    If this attribute is set "0", it displays popular users for current day.<br />
                    <strong>"by"</strong> - the base for counting and choosing popular users ( values: "comments", "post counts" or "views" )<br />
                    If this attribute is not set, it takes "comments" as a base.<br />
                    <strong>"post_types"</strong> - comma separated post types(post,page, etc...)<br />
                    If this attribute is not set, then default post types will be applied (post, page)<br />
                    <strong>"width"</strong> - the width of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (400px)<br />
                    <strong>"height"</strong> - the height of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (300px)<br />

                    <pre style="border:1px dotted #009900; margin:20px 0px 5px 0px; padding:5px 10px; display:table; line-height:25px; color:#009900;">&nbsp;[apsw_visitors last="7" user="21" post_types="post,page" width="400" height="300"]&nbsp;</pre>
                    <h4 style="padding-top:1px; margin-top:1px; margin-bottom:5px;">Displays number of posts visitors (with countries) for certain users posts.</h4>
                    <strong>"last"</strong> - the number of past days<br />
                    If this attribute is not set, it displays visitors for all time.<br />
                    If this attribute is set "0", it displays visitors for current day.<br />
                    <strong>"user"</strong> - user id <br />
                    If this attribute is not set, it displays current logged in users' posts visitors statistic<br />
                    <strong>"post_types"</strong> - comma separated post types(post,page, etc...)<br />
                    If this attribute is not set, then default post types will be applied (post, page)<br />
                    <strong>"width"</strong> - the width of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (400px)<br />
                    <strong>"height"</strong> - the height of chart in pixels<br />
                    If this attribute is not set, then default value will be applied (300px)<br />

                    <br />

                    <h3>Need Help?</h3>
                    <p>
                        If you need help with this plugin or if you want to make a suggestion, then please visit to our support Q&amp;A forum.
                        <a href="http://gvectors.com/forum/author-post-statistic-widgets/" class="button button-primary" target="_blank">gVectors Support Forum</a>
                    </p>
            </tr>
        </tbody>
    </table>    
</div>