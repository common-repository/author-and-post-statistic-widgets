<?php
if (!defined('ABSPATH')) {
    exit();
}

$instance_before_widget = isset($instance['before_widget']) && ($before = trim($instance['before_widget'])) ? $before : '';
$instance_after_widget = isset($instance['after_widget']) && ($after = trim($instance['after_widget'])) ? $after : '';
$instance_before_title = isset($instance['before_title']) && ($before = trim($instance['before_title'])) ? $before : '';
$instance_after_title = isset($instance['after_title']) && ($after = trim($instance['after_title'])) ? $after : '';
$instance_before_body = isset($instance['before_body']) && ($before = trim($instance['before_body'])) ? $before : '';
$instance_after_body = isset($instance['after_body']) && ($after = trim($instance['after_body'])) ? $after : '';
?>
<p>
    <label class="apsw-widget-label" for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title', 'author-and-post-statistic-widgets'); ?></label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
</p>

<p>
    <label class="apsw-widget-label" for="<?php echo $this->get_field_name('interval'); ?>"><?php _e('Select date interval', 'author-and-post-statistic-widgets'); ?></label>
    <select class="widefat" name="<?php echo $this->get_field_name('dateInterval'); ?>">
        <option value="0" <?php selected($instance['dateInterval'], '0'); ?>><?php _e('Today', 'author-and-post-statistic-widgets'); ?></option>
        <option value="1" <?php selected($instance['dateInterval'], '1'); ?>><?php _e('Yesterday', 'author-and-post-statistic-widgets'); ?></option>
        <option value="7" <?php selected($instance['dateInterval'], '7'); ?>><?php _e('Last 7 Days', 'author-and-post-statistic-widgets'); ?></option>
        <option value="30" <?php selected($instance['dateInterval'], '30'); ?>><?php _e('Last 30 Days', 'author-and-post-statistic-widgets'); ?></option>
        <option value="90" <?php selected($instance['dateInterval'], '90'); ?>><?php _e('Last 90 Days', 'author-and-post-statistic-widgets'); ?></option>
        <option value="180" <?php selected($instance['dateInterval'], '180'); ?>><?php _e('Last 180 Days', 'author-and-post-statistic-widgets'); ?></option>
        <option value="365" <?php selected($instance['dateInterval'], '365'); ?>><?php _e('Last Year', 'author-and-post-statistic-widgets'); ?></option>
        <option value="-1" <?php selected($instance['dateInterval'], '-1'); ?>><?php _e('All Time', 'author-and-post-statistic-widgets'); ?></option>
    </select>
</p>

<?php if ($this->optionsSerialized->isDisplayCustomHtmlForWidgets) { ?>
    <div class="widget-custom-wrapper">
        <input type="checkbox" class="checkbox chk-custom-html" id="<?php echo $this->get_field_id('widgetCustomArgs'); ?>" name="<?php echo $this->get_field_name('widgetCustomArgs'); ?>" <?php checked($instance['widgetCustomArgs'] == 1); ?> value="1"/>    
        <label class="apsw-widget-label" for="<?php echo $this->get_field_id('widgetCustomArgs'); ?>"><?php _e('Use widget custom before/after html', 'author-and-post-statistic-widgets'); ?></label>
        <div style="display: <?php echo checked($instance['widgetCustomArgs'] == 1) ? 'block' : 'none'; ?>;" class="widgetCustomArgs">
            <p>
                <label class="" for="<?php echo $this->get_field_id('before_widget'); ?>"><?php _e('Before Widget:', 'author-and-post-statistic-widgets'); ?></label><br>
                <textarea placeholder="<?php _e('HTML before the widget', 'author-and-post-statistic-widgets'); ?>" id="<?php echo $this->get_field_id('before_widget'); ?>" name="<?php echo $this->get_field_name('before_widget'); ?>" class="widefat" rows="1" cols="20"><?php echo $instance_before_widget; ?></textarea>
            </p>
            <p>
                <label class="" for="<?php echo $this->get_field_id('after_widget'); ?>"><?php _e('After Widget:', 'author-and-post-statistic-widgets'); ?></label><br>
                <textarea placeholder="<?php _e('HTML after the widget', 'author-and-post-statistic-widgets'); ?>" id="<?php echo $this->get_field_id('after_widget'); ?>" name="<?php echo $this->get_field_name('after_widget'); ?>" class="widefat" rows="1" cols="20"><?php echo $instance_after_widget; ?></textarea>
            </p>
        </div>
    </div>

    <div class="title-custom-wrapper">
        <input type="checkbox" class="checkbox chk-custom-html" id="<?php echo $this->get_field_id('titleCustomArgs'); ?>" name="<?php echo $this->get_field_name('titleCustomArgs'); ?>" <?php checked($instance['titleCustomArgs'] == 1); ?> value="1"/>
        <label class="apsw-widget-label" for="<?php echo $this->get_field_id('titleCustomArgs'); ?>"><?php _e('Use title custom before/after html', 'author-and-post-statistic-widgets'); ?></label>
        <div style="display: <?php echo checked($instance['titleCustomArgs'] == 1) ? 'block' : 'none'; ?>" class="titleCustomArgs">
            <p>
                <label class="" for="<?php echo $this->get_field_id('before_title'); ?>"><?php _e('Before Title:', 'author-and-post-statistic-widgets'); ?></label><br>
                <textarea placeholder="<?php _e('HTML before the title', 'author-and-post-statistic-widgets'); ?>" id="<?php echo $this->get_field_id('before_title'); ?>" name="<?php echo $this->get_field_name('before_title'); ?>" class="widefat" rows="1" cols="20"><?php echo $instance_before_title; ?></textarea>
            </p>
            <p>
                <label class="" for="<?php echo $this->get_field_id('after_title'); ?>"><?php _e('After Title:', 'author-and-post-statistic-widgets'); ?></label><br>
                <textarea placeholder="<?php _e('HTML after the title', 'author-and-post-statistic-widgets'); ?>" id="<?php echo $this->get_field_id('after_title'); ?>" name="<?php echo $this->get_field_name('after_title'); ?>" class="widefat" rows="1" cols="20"><?php echo $instance_after_title; ?></textarea>
            </p>
        </div>
    </div>

    <div class="body-custom-wrapper"> 
        <input type="checkbox" class="checkbox chk-custom-html" id="<?php echo $this->get_field_id('bodyCustomArgs'); ?>" name="<?php echo $this->get_field_name('bodyCustomArgs'); ?>" <?php checked($instance['bodyCustomArgs'] == 1); ?> value="1"/>
        <label class="apsw-widget-label" for="<?php echo $this->get_field_id('bodyCustomArgs'); ?>"><?php _e('Use body custom before/after html', 'author-and-post-statistic-widgets'); ?></label>
        <div style="display: <?php echo checked($instance['bodyCustomArgs'] == 1) ? 'block' : 'none'; ?>" class="bodyCustomArgs">
            <p>
                <label class="" for="<?php echo $this->get_field_id('before_body'); ?>"><?php _e('Before Body:', 'author-and-post-statistic-widgets'); ?></label><br>
                <textarea placeholder="<?php _e('HTML before the body', 'author-and-post-statistic-widgets'); ?>" id="<?php echo $this->get_field_id('before_body'); ?>" name="<?php echo $this->get_field_name('before_body'); ?>" class="widefat" rows="1" cols="20"><?php echo $instance_before_body; ?></textarea>
            </p>
            <p>
                <label class="" for="<?php echo $this->get_field_id('after_body'); ?>"><?php _e('After Body:', 'author-and-post-statistic-widgets'); ?></label><br>
                <textarea placeholder="<?php _e('HTML after the body', 'author-and-post-statistic-widgets'); ?>" id="<?php echo $this->get_field_id('after_body'); ?>" name="<?php echo $this->get_field_name('after_body'); ?>" class="widefat" rows="1" cols="20"><?php echo $instance_after_body; ?></textarea>
            </p>
        </div>
    </div>
    <?php
} 