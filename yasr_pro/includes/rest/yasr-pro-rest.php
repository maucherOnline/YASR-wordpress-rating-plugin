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

//AutoLoad Yasr Response, only when a object is created
spl_autoload_register('yasr_pro_autoload_rest_response');

/**
 * Callback function for the spl_autoload_register above.
 *
 * @param $class
 */
function yasr_pro_autoload_rest_response($class) {
    /**
     * If the class being requested does not start with 'Yasr' prefix,
     * it's not in Yasr Project
     */
    if (0 !== strpos($class, 'Yasr')) {
        return;
    }
    $file_name =  __DIR__ . '/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name)) {
        require($file_name);
    }
}

add_action( 'rest_api_init', 'yasr_pro_load_rest_api_init');

/**
 * Load pro rest api after rest_api_init
 */
function yasr_pro_load_rest_api_init () {
    $yasr_pro_custom_fields = new YasrProCustomFields();
    $yasr_pro_custom_fields->returnCommentReviewsEnabledForPost();

    $yasr_pro_post_meta = new YasrProPostMeta();
    $yasr_pro_post_meta->registerPostMeta();
}


add_filter('yasr_rest_rankings_args', 'yasr_pro_add_rankings_args');

/**
 * Adds 5 args to yasr-rankings endpoint (register_rest_route):
 * order_by
 * limit
 * start_date
 * end_date
 * ctg
 * cpt
 * required_votes
 *
 * These values will always be sanitized
 *
 * YOURSITE/wp-json/yet-another-stars-rating/v1/yasr-rankings/<source>?order_by=desc&limit=4
 *
 * @author Dario Curvino <@dudo>
 * @since 2.5.2
 *
 * @param $args
 *
 * @return array
 */
function yasr_pro_add_rankings_args($args){
    $yasr_custom_endpoint_obj = new YasrCustomEndpoint;

    $more_args = array(
        'order_by' => array(
            'required' => false,
            'sanitize_callback' => array($yasr_custom_endpoint_obj, 'sanitizeInput')
        ),
        'limit' => array(
            'required' => false,
            'sanitize_callback' => array($yasr_custom_endpoint_obj, 'sanitizeInput')
        ),
        'start_date' => array(
            'required' => false,
            'sanitize_callback' => array($yasr_custom_endpoint_obj, 'sanitizeInput')
        ),
        'end_date' => array(
            'required' => false,
            'sanitize_callback' => array($yasr_custom_endpoint_obj, 'sanitizeInput')
        ),
        'ctg' => array(
            'required' => false,
            'sanitize_callback' => array($yasr_custom_endpoint_obj, 'sanitizeInput')
        ),
        'cpt' => array(
            'required' => false,
            'sanitize_callback' => array($yasr_custom_endpoint_obj, 'sanitizeInput')
        ),
        'required_votes' => array(
            'required' => false,
            'sanitize_callback' => array($yasr_custom_endpoint_obj, 'sanitizeInput')
        ),
    );

    $args = array_merge($args, $more_args);

    return $args;
}

add_filter('yasr_rest_sanitize', 'yasr_pro_rest_sanitize', 10, 2);

/**
 * Sanitize the new input fields added through yasr_pro_rest_filter_ranking_request
 *
 * @author Dario Curvino <@dudo>
 * @since 2.5.2
 *
 * @param $key
 * @param $param
 *
 * @return int|string|\WP_Error
 */
function yasr_pro_rest_sanitize($key, $param) {
    $yasr_pro_rankings = new YasrProRankings(false, false);

    if($key === 'order_by') {
        if($param !== 'asc' && $param !== 'ASC') {
            $param = 'DESC';
        } else {
            $param = 'ASC';
        }
        return $param;
    }

    if($key === 'limit') {
        return $yasr_pro_rankings->setLimit($param);
    }

    if($key === 'ctg') {
        return $yasr_pro_rankings->cleanCategory(json_encode($param));
    }

    if($key === 'start_date') {
        return $yasr_pro_rankings->checkDate($param);
    }

    if($key === 'end_date') {
        return $yasr_pro_rankings->checkDate($param);
    }

    if($key === 'cpt') {
        if($yasr_pro_rankings->cleanCpt($param)){
            return $param;
        }
        return new WP_Error(
            'no_overall_data',
            __('No posts with this cpt found.', 'yasr-pro'),
            400
        );
    }

    if($key === 'required_votes') {
        return (int)$param;
    }

    return $param;

}
