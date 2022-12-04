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

/**
 * class for get overallRating and visitorVotes values
 *
 * Class YasrDatabaseRatings
 */
class YasrGetRatings {

    /**
     * Returns overall rating for single post or page
     *
     * @param $post_id void|bool|int
     *
     * @return mixed|null
     */
    public static function overallRating ($post_id=false) {
        //if values it's not passed get the post id, since version 1.6.9 this is just for yasr_add_schema function
        //and for a further check
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        $overall_rating = get_post_meta($post_id, 'yasr_overall_rating', true);

        if (!$overall_rating || $overall_rating < 0) {
            $overall_rating = 0;
        }
        if($overall_rating > 5) {
            $overall_rating = 5;
        }
        return $overall_rating;
    }

    /**
     * Returns *ALL* Overall Ratings*
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     * @return array|object|\stdClass[]|null
     */
    public static function allOverallRatings() {
        global $wpdb;

        $query = "SELECT pm.post_id,      
                         pm.meta_value   as vote, 
                         pm.meta_id      as id,
                         p.post_author   as user_id,
                         p.post_modified as date 
                  FROM $wpdb->postmeta   as pm,
                       $wpdb->posts      as p 
                  WHERE pm.meta_key = 'yasr_overall_rating'
                      AND p.ID = pm.post_id
                      AND pm.meta_value > 0";

        return $wpdb->get_results($query, ARRAY_A);

    }

    /**
     * Return Visitor Votes for a single post or page
     *
     * @param bool|integer $post_id
     *
     * @return array|null
     *    array(
     *        'number_of_votes'  = (int)$user_votes->number_of_votes;
     *        'sum_votes'        = (int)$user_votes->sum_votes;
     *        'average'          = (int)average result
     *    )
     */
    public static function visitorVotes ($post_id = false) {
        global $wpdb;

        //if values it's not passed get the post id, most of the cases and default one
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT SUM(vote) as sum_votes, 
                            COUNT(vote)  as number_of_votes 
                        FROM " . YASR_LOG_TABLE .
                        "  WHERE post_id=%d
                           AND   vote > 0
                           AND   vote <= 5
                        ",
                $post_id
            )
        );

        $array_to_return = array(
            'number_of_votes' => 0,
            'sum_votes'       => 0,
            'average'         => 0
        );

        foreach ($result as $user_votes) {
            $array_to_return['number_of_votes'] = (int)$user_votes->number_of_votes;
            $array_to_return['sum_votes']       = (int)$user_votes->sum_votes;

            if ($array_to_return['number_of_votes'] > 0) {
                $array_to_return['average']  = ($array_to_return['sum_votes'] / $array_to_return['number_of_votes']);
                $array_to_return['average']  = round($array_to_return['average'], 1);
            }
        }

        return $array_to_return;
    }

    /**
     * Returns *ALL* visitor votes in YASR_LOG_TABLE
     * used in stats page
     *
     * @author Dario Curvino <@dudo>
     * @since 2.5.2
     *
     * @return array|object|null
     */
    public static function allVisitorVotes() {
        global $wpdb;

        $query = 'SELECT * FROM ' .YASR_LOG_TABLE.  ' ORDER BY date';

        return $wpdb->get_results($query, ARRAY_A);
    }

    /**
     * Check if current user has already rated, and if so, return the rating, or false otherwise
     *
     * @param int | bool $post_id
     *
     * @return bool|int
     */
    public static function vvCurrentUserRating($post_id = false) {
        global $wpdb;

        $user_id      = get_current_user_id();

        //just to be safe
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        $rating = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT vote FROM "
                . YASR_LOG_TABLE .
                " WHERE post_id=%d 
                    AND user_id=%d 
                    LIMIT 1 ",
                $post_id, $user_id
            )
        );

        if ($rating === null) {
            return false;
        }

        return (int)$rating;
    }

    /**
     * Run $wpdb->get_results for overall Rating
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param bool|array $atts
     *
     * @return array|false|object|void
     */
    public static function rankingOverall($atts) {
        global $wpdb;

        //return custom query_result here
        //must returns rating and post_id
        $query = apply_filters('yasr_rankings_query_ov', $atts);

        //if query_results === $sql_params means that filters doesn't exists
        if ($query === $atts) {
            //default query_results
            $query = "SELECT pm.meta_value AS rating, 
                         pm.post_id AS post_id
                  FROM $wpdb->postmeta AS pm, 
                       $wpdb->posts AS p
                  WHERE  pm.post_id = p.ID
                      AND p.post_status = 'publish'
                      AND pm.meta_key = 'yasr_overall_rating'
                      AND pm.meta_value > 0
                  ORDER BY pm.meta_value DESC,
                           pm.post_id 
                  LIMIT 10";

            $query_results = $wpdb->get_results($query);
        }
        else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

    /**
     * Returns yasr_most_or_highest_rated_posts with no params and 10 rows.
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param array      $atts
     * @param            $ranking
     *
     * @return array|false|object|void
     */
    public static function rankingVV($atts, $ranking) {
        global $wpdb;

        //hooks here to return a query
        $query = apply_filters('yasr_rankings_query_vv', $atts, $ranking);

        //if no custom query is hooked
        if ($query === $atts) {
            $common_query = "SELECT post_id, 
                COUNT(post_id) AS number_of_votes,
                ROUND(SUM(vote) / COUNT(post_id),1) AS rating
            FROM " . YASR_LOG_TABLE . ",
                $wpdb->posts AS p
            WHERE post_id = p.ID
                AND p.post_status = 'publish'
            GROUP BY post_id
                HAVING number_of_votes > 1
            ";

            if ($ranking === 'highest') {
                $order_by = ' ORDER BY rating DESC, number_of_votes DESC';
            }
            else {
                $order_by = ' ORDER BY number_of_votes DESC, rating DESC, post_id DESC';
            }

            $limit = ' LIMIT 10';
            $query = $common_query . $order_by . $limit;

            $query_results = $wpdb->get_results($query);
        }
        else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

    /***
     * @author Dario Curvino <@dudo>
     * @since 2.6.3
     *
     * @param $atts
     *
     * @return array|false|object|void
     */
    public static function rankingTopUsers($atts) {
        global $wpdb;

        //return custom query here
        //must returns rating and post_id
        $query = apply_filters('yasr_rankings_query_tu', $atts);

        //if query === $sql_params (both are falses) means that filters doesn't exists
        if ($query === $atts) {
            $query = 'SELECT COUNT(user_id) as total_count, 
                        user_id as user
                    FROM ' . YASR_LOG_TABLE . ", 
                        $wpdb->posts AS p
                    WHERE  post_id = p.ID
                        AND p.post_status = 'publish'
                    GROUP BY user_id
                    ORDER BY ( total_count ) DESC
                    LIMIT 10";

            $query_results = $wpdb->get_results($query);

        }
        else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }/**
     * @author Dario Curvino <@dudo>
     * @since  2.7.2
     *
     * @param        $set_id
     * @param  false $sql_atts
     *
     * @return bool|array
 */
    public static function rankingMulti($set_id, $sql_atts = false) {
        global $wpdb;
        if ($set_id === null) {
            $set_id = YASR_FIRST_SETID;
        }

        $set_id = (int) $set_id;

        //hooks here to return a query
        $query_result = apply_filters('yasr_rankings_multi_query', $sql_atts, $set_id);

        //if hook has run
        if ($query_result !== $sql_atts) {
            //but don't return an array or array is empty, return false
            if (!is_array($query_result) || empty($query_result)) {
                return false;
            }
            //else return hooks result
            return $query_result;
        }

        //if no hook is found in $query_result, return default ranking
        if ($query_result === $sql_atts) {
            //Create an array of post_id that has meta key = yasr_multiset_author_votes
            $array_post_id
                = $wpdb->get_results(
                "SELECT pm.post_id AS id
                FROM $wpdb->postmeta AS pm, 
                     $wpdb->posts    AS p
                WHERE  pm.post_id = p.ID
                    AND p.post_status = 'publish'
                    AND pm.meta_key = 'yasr_multiset_author_votes'
                ORDER BY pm.post_id"
            );

            if (!is_array($array_post_id) || empty($array_post_id)) {
                return false;
            }

            //set fields name and ids
            $average_array = YasrMultiSetData::returnMultiAuthorAverageArray($array_post_id, $set_id);

            //Limit the array to N results
            return array_slice($average_array, 0, 10);
        }

        //should never happens
        return false;
    }/***
     * @author Dario Curvino <@dudo>
     * @since 2.6.3
     *
     * @param $atts
     *
     * @return array|false|object|void
 */
    public static function rankingTopReviewers($atts) {
        global $wpdb;

        //return custom query here
        //must returns rating and post_id
        $query = apply_filters('yasr_rankings_query_tr', $atts);

        //if query === $sql_params (both are falses) means that filters doesn't exists
        if ($query === $atts) {
            $query = "SELECT COUNT( pm.post_id ) AS total_count,
                          p.post_author    AS user,
                          u.user_login     AS name
                      FROM $wpdb->posts    AS p, 
                           $wpdb->postmeta AS pm,
                           $wpdb->users    AS u
                      WHERE pm.post_id = p.ID
                          AND pm.meta_key = 'yasr_overall_rating'
                          AND p.post_status = 'publish'
                          AND p.post_author = u.ID
                      GROUP BY user
                      ORDER BY total_count DESC
                    LIMIT 5";

            $query_results = $wpdb->get_results($query);

        }
        else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }/**
     * @author Dario Curvino <@dudo>
     * @since  2.7.2
     *
     * @param int|bool|string $set_id
     * @param string          $ranking
     * @param false           $sql_atts
     *
     * @return array|false|object
 */
    public static function rankingMultiVV($set_id, $ranking = 'most', $sql_atts = false) {
        global $wpdb;
        //if set_id is not set (e.g. in rest parameter setid is not set)
        if ($set_id === null) {
            $set_id = YASR_FIRST_SETID;
        }

        $set_id = (int) $set_id;

        //hooks here to return a query
        $query = apply_filters('yasr_rankings_multivv_query', $sql_atts, $ranking, $set_id);

        //if no custom query is hooked
        if ($query === $sql_atts) {
            $query = $wpdb->prepare(
                "SELECT CAST((SUM(l.vote)/COUNT(l.vote)) AS DECIMAL(2,1)) AS rating, 
                           COUNT(l.vote) AS number_of_votes, 
                           l.post_id
                       FROM " . YASR_LOG_MULTI_SET . " AS l,
                           $wpdb->posts AS p
                       WHERE l.set_type = %d
                           AND p.ID = l.post_id
                           AND p.post_status = 'publish'
                       GROUP BY l.post_id", '%d'
            );
            if ($ranking === 'highest') {
                $query .= ' ORDER BY rating DESC';
            }
            else {
                $query .= ' ORDER BY number_of_votes DESC';
            }
            $query .= ' LIMIT 10';

            $query_results = $wpdb->get_results($query);

        }
        else {
            $query_results = $query;
        }

        if ($query_results) {
            return $query_results;
        }
        return false;
    }

}
