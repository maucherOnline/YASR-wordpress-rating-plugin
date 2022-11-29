<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/****** Translating YASR ******/
add_action('init', 'yasr_translate');

function yasr_translate() {
    load_plugin_textdomain('yet-another-stars-rating', false, YASR_LANG_DIR);
}

/**
 * Create a select menu to choose the rich snippet itemtype
 *
 * @param bool|string $html_id the id of the select name
 * @param bool|string $name
 * @param bool|string $default_option
 * @param bool|int    $term_id
 * @param bool        $disabled
 */

function yasr_select_itemtype($html_id=false, $name=false, $default_option=false, $term_id=false, $disabled=false) {
    if($html_id === false) {
        $html_id = 'yasr-choose-reviews-types-list';
    }

    if($name === false) {
        $name = 'yasr-review-type';
    }

    $itemtypes_array = YASR_SUPPORTED_SCHEMA_TYPES;
    sort($itemtypes_array);

    if($default_option === false) {
        $review_type_choosen = yasr_get_itemType($term_id);
    } else {
        $review_type_choosen = $default_option;
    }

    $disabled_attribute = '';

    if($disabled === true) {
        $disabled_attribute = 'disabled';
    }
    ?>

    <label for="<?php echo esc_attr($html_id) ?>"></label>
    <select name="<?php echo esc_attr($name) ?>" id="<?php echo esc_attr($html_id) ?>">
        <?php
        foreach ($itemtypes_array as $itemType) {
            $itemType = trim($itemType);
            if ($itemType === $review_type_choosen) {
                echo '<option value="'.esc_attr($itemType).'" selected >
                          '.esc_html($itemType).'
                      </option>';
            } else {
                echo '<option value="'.esc_attr($itemType).'" '.esc_attr($disabled_attribute).'>
                        '.esc_html($itemType).'
                      </option>';
            }
        }
        ?>
    </select>

    <?php
} //End function yasr_select_itemtype()

/*** Function to set cookie
 * @since 0.8.3
 *
 * @param $cookiename //can comes from a filter
 * @param $data_to_save
 */
function yasr_setcookie($cookiename, $data_to_save) {

    if (!$data_to_save || !$cookiename || !is_string($cookiename)) {
        exit('Error setting yasr cookie');
    }

    //sanitize the cookie name
    $cookiename = wp_strip_all_tags($cookiename);
    $domain = COOKIE_DOMAIN;

    //this is for multisite support
    if(defined('DOMAIN_CURRENT_SITE')) {
        $domain = DOMAIN_CURRENT_SITE;
    }

    $existing_data = array(); //avoid undefined index

    if (isset($_COOKIE[$cookiename])) {
        //setcookie add \ , so I need to stripslahes
        $existing_data = stripslashes($_COOKIE[$cookiename]);

        //By default, json_decode return an object, TRUE to return an array
        $existing_data = json_decode($existing_data, true);
    }

    //whetever exists or not, push into at the end of array
    $existing_data[] = $data_to_save;

    $encoded_data = json_encode($existing_data);
    $expire = time() + 31536000;

    if (PHP_VERSION_ID < 70300) {
        setcookie($cookiename, $encoded_data, $expire, COOKIEPATH . '; samesite=' . 'Lax', $domain, false);
        return;
    }
    setcookie($cookiename, $encoded_data, [
        'expires'  => $expire,
        'path'     => COOKIEPATH,
        'domain'   => $domain,
        'samesite' => 'Lax',
        'secure'   => false,
        'httponly' => false,
    ]);

}

/*** Function to get ip, since version 0.8.8
 * This code can be found on http://codex.wordpress.org/Plugin_API/Filter_Reference/pre_comment_user_ip
 ***/

function yasr_get_ip() {

    if (YASR_ENABLE_IP === 'yes') {
        $ip = null;
        $ip = apply_filters('yasr_filter_ip', $ip);

        if (isset($ip)) {
            return $ip;
        }
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['X_FORWARDED_FOR'])) {
            $X_FORWARDED_FOR = explode(',', $_SERVER['X_FORWARDED_FOR']);
            if (!empty($X_FORWARDED_FOR)) {
                $REMOTE_ADDR = trim($X_FORWARDED_FOR[0]);
            }
        }
        /*
        * Some php environments will use the $_SERVER['HTTP_X_FORWARDED_FOR']
        * variable to capture visitor address information.
        */

        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if (!empty($HTTP_X_FORWARDED_FOR)) {
                $REMOTE_ADDR = trim($HTTP_X_FORWARDED_FOR[0]);
            }
        }
        return preg_replace('/[^0-9a-f:., ]/si', '', $REMOTE_ADDR);
    }
    return ('X.X.X.X');
}


/*function to remove duplicate in an array for a specific key
Taken value: array to search, key
*/
function yasr_unique_multidim_array($array, $key) {
    $temp_array = array();
    $i          = 0;

    //this array will contain only indexes
    $key_array = array();

    foreach ($array as $val) {
        $result_search_array = array_search($val[$key], $key_array);

        $key_array[$i]  = $val[$key];
        $temp_array[$i] = $val;

        //if result is found
        if ($result_search_array !== false) {
            unset($key_array[$result_search_array], $temp_array[$result_search_array]);
        }
        $i ++;
    }
    sort($temp_array);
    return $temp_array;
}

/**
 * Return true if the requested url return 200
 *
 * @author Dario Curvino <@dudo>
 * @since refactor in 3.0.8
 * @param $url
 *
 * @return bool
 */
function yasr_check_valid_url($url) {
    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        return false;
    }

    $response = wp_remote_get($url);

    if(is_wp_error($response)) {
        return false;
    }

    if(wp_remote_retrieve_response_code($response) === 200) {
        return true;
    }

    return false;
}


/**
 * Check if the given string is a supported itemType
 *
 * @param string $item_type
 * @return bool
 *
 * @since 2.1.5
 */
function yasr_is_supported_schema ($item_type) {
    $supported_schema_array = YASR_SUPPORTED_SCHEMA_TYPES;

    if (in_array($item_type, $supported_schema_array)) {
        return true;
    }

    return false;
}

/**
 * @author Dario Curvino <@dudo>
 * @since 2.9.3
 * @return bool
 */
function yasr_is_catch_infinite_sroll_installed () {
    if (is_plugin_active('catch-infinite-scroll/catch-infinite-scroll.php')) {
        return true;
    }
    return false;
}

/**
 * Wrapper function for wp_kses that adds allowed HTML
 *
 * @author Dario Curvino <@dudo>
 * @since  3.0.6
 * @param $string
 *
 * @return string
 */
function yasr_kses($string) {
    $allowed_html = array(
        'div'   => array(
            'class'        => array(),
        ),
        'span' => array(
            'class'        => array(),
        ),
        'label' => array (
            'for'          => array(),
        ),
        'select' => array(
            'name'         => array(),
            'id'           => array(),
            'autocomplete' => array(),
        ),
        'option'       => array(
            'value'    => array(),
            'selected' => array()
        ),
        'textarea' => array(
            'name'         => array(),
            'id'           => array(),
            'placeholder'  => array(),
            'autocomplete' => array(),
        ),
        'input' => array(
            'type'         => array(),
            'name'         => array(),
            'id'           => array(),
            'class'        => array(),
            'value'        => array(),
            'placeholder'  => array(),
            'autocomplete' => array(),
            'checked'      => array(),
            'data-shortcode' => array(),
        ),
        'img' => array (
            'src'   => array(),
            'title' => array(),
            'height' => array(),
            'width' => array(),
            'alt'   => array(),
            'class' => array(),
        ),
        'br'     => array(),
        'strong' =>array()
    );

    return wp_kses($string, $allowed_html);
}

/**
 * Wrapper function for getimagesize.
 * If url is invalid or getimagesize doesn't return an array, return an array(0,0)
 *
 * @author Dario Curvino <@dudo>
 * @since  3.1.5
 * @param $url
 *
 * @return array
 */

function yasr_getimagesize($url) {
    //check if url is valid
    if (yasr_check_valid_url($url) === true) {
        $image_size = @getimagesize($url);

        //be sure that getimagesize has returned an array
        if (!is_array($image_size)) {
            $image_size[0] = 0;
            $image_size[1] = 0;
        }

        return $image_size;
    }

    return array(0,0);
}

/**
 * Check if the given url is a SVG image
 *
 * @author Dario Curvino <@dudo>
 * @since  2.6.8
 *
 * @param $url
 *
 * @return bool
 */
function yasr_check_svg_image($url) {
    if ($url !== '') {

        //check if url is valid
        if (yasr_check_valid_url($url) === true) {

            //if url is valid, check if is a svg image
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $type  = $finfo->buffer(file_get_contents($url));

            if ($type === 'image/svg+xml') {
                return true;
            }
            return false;
        }
        return false;
    }
    return false;

}