<?php

if (!defined('ABSPATH')) {
    exit();
}

class APSWHelper {

    private static $optionsSerialized;
    public static $postTypes = array('post', 'page');
    public static $taxonomyTypes;
    
    public function __construct($optionsSerialized) {
        self::$optionsSerialized = $optionsSerialized;
    }

    /**
     * return words substringed by letters count
     */
    public static function getSubstringedString($text) {
        $length = self::$optionsSerialized->postTitleLength ? self::$optionsSerialized->postTitleLength : 15;
        $newText = function_exists('mb_substr') ? mb_substr($text, 0, $length) : substr($text, 0, $length);
        $dots = '';
        if (function_exists('mb_strlen')) {
            $dots = mb_strlen($text) > $length ? ' ...' : '';
        } else {
            $dots = strlen($text) > $length ? ' ...' : '';
        }
        $newText .= $dots;
        return $newText;
    }

    public static function getPostTypes($array = array('post', 'page')) {
        $result = '';
        $default = '"post","page"';
        if ($array && is_array($array)) {
            for ($i = 0; $i < count($array); $i++) {
                if (post_type_exists($array[$i])) {
                    $result .= '"' . $array[$i] . '",';
                }
            }
        } else {
            $result = $default;
        }
        return trim($result, ',');
    }

    public static function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function getTimeStamp() {
        return current_time('timestamp');
    }

    public static function getMysqlDate() {
        return current_time('mysql');
    }

    public static function getDateIntervals($last, $dateFormat = 'Y-m-d') {
        $interval = array();
        $mysqlDate = self::getMysqlDate();
        $timestamp = self::getTimeStamp();
        if ($last !== '' && intval($last) === 0) { // today
            $interval['from'] = date($dateFormat, $timestamp);
            $datetime = new DateTime($interval['from']);
            $datetime->modify('+1 day');
            $interval['to'] = $datetime->format($dateFormat);
        } else if ($last !== '' && intval($last) === 1) { // yesterday
            $modify = '-1 day';
            $datetime = new DateTime($mysqlDate);
            $datetime->modify($modify);
            $datetime2 = new DateTime($mysqlDate);
            $datetime2->modify($modify);
            $interval['from'] = $datetime->format($dateFormat);
            $interval['to'] = $datetime2->format($dateFormat);
        } else if ($last !== '' && intval($last) > 0) { // last X days 
            $datetime = new DateTime($mysqlDate);
            $modify = '-' . $last . ' day';
            $datetime->modify($modify);
            $interval['from'] = $datetime->format($dateFormat);
            $interval['to'] = date($dateFormat, $timestamp);
        } else { // all time
            $interval['from'] = date($dateFormat, 1);
            $interval['to'] = date($dateFormat, $timestamp);
        }
        return $interval;
    }

    public function initTaxonomies() {
        self::$taxonomyTypes = get_taxonomies(array('public' => true));
    }

}
