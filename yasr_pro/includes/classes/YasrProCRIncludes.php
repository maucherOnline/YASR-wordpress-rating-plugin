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

class YasrProCRIncludes {

    public function init() {
        $this->addShortcodes();
        $this->addFilters();
    }

    /** Init filters
     * @author Dario Curvino <@dudo>
     * @since  2.7.1
     */
    public function addFilters() {
        //filters for yasr_or_ranking
        add_filter('yasr_ov_rankings_atts',               array($this, 'filterOVRankingAtts'), 10, 2);
        add_filter('yasr_rankings_query_ov',              array($this, 'queryOVRanking'));

        //filters for yasr_most_or_highest
        add_filter('yasr_vv_rankings_atts',               array($this, 'filterVVRankingAtts'), 10, 2);
        add_filter('yasr_rankings_query_vv',              array($this, 'queryVVRanking'), 10, 3);

        //Users rankings filters hooks to the same function
        add_filter('yasr_tr_rankings_atts',               array($this, 'filterRURankingAtts'), 10, 2);
        add_filter('yasr_rankings_query_tr',              array($this, 'queryTRRanking'), 10, 3);


        add_filter('yasr_tu_rankings_atts',               array($this, 'filterRURankingAtts'), 10, 2);
        add_filter('yasr_rankings_query_tu',              array($this, 'queryTURanking'), 10, 3);

        //filter the multi set atts, using the same method of yasr_rankings_query_ov
        add_filter('yasr_multi_set_ranking_atts',         array($this, 'filterOVRankingAtts'), 10, 2);
        add_filter('yasr_rankings_multi_query',           array($this, 'queryMARanking'), 10, 3);

        //filter the visitor multi set atts, using the same method of yasr_rankings_query_vv
        add_filter('yasr_visitor_multi_set_ranking_atts', array($this, 'filterVVRankingAtts'), 10, 2);
        add_filter('yasr_rankings_multivv_query',         array($this, 'queryMVRanking'), 10, 3);

        //Filter for add more params to rest and ajax request
        add_filter('yasr_filter_ranking_request',         array($this, 'filterRankingsRequest'), 10, 2);
        //add comment ranking
        add_filter('yasr_add_sources_ranking_request',    array($this, 'commentRanking'), 10, 4);
    }

    public function addShortcodes () {
        //This shortcode shows rankings of most/highest reviewed post
        add_shortcode('yasr_pro_ur_ranking', array($this, 'urRankingReview'));

        //most active reviewers
        //@todo deprecate this
        add_shortcode('yasr_pro_most_active_users', array($this, 'crMostActiveUsers'));

        //most active reviewers
        //@todo deprecate this
        add_shortcode('yasr_pro_most_active_reviewers', array($this, 'crMostActiveReviewers'));

        /**
         * @depreacted deprecated since 2.6.2
         * @todo remove for DEC 2021
         */
        add_shortcode('yasr_pro_overall_rating_chart', array($this, 'crShortcodeOverallRatingChart'));

        /**
         * @depreacted deprecated since 2.6.2
         * @todo remove for DEC 2021
         */
        add_shortcode('yasr_pro_visitor_votes_chart', array($this, 'crShortcodeVVChart'));

        /***
         * @depreacted deprecated since 2.7.1
         * @todo remove for DEC 2021
         */
        add_shortcode('yasr_pro_rankings_from_comments_reviews', array($this, 'urRankingReview'));
    }

    /**
     * This function hooks into yasr_ov_rankings_atts
     * and returns an array of shortcode pro atts
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.2
     *
     * @param $shortcode_name
     * @param $atts
     *
     * @return array
     */
    public function filterOVRankingAtts($shortcode_name, $atts) {
        $shortcode_atts = new YasrProRankings($atts, $shortcode_name);

        add_filter('yasr_size_ranking', function() use ($shortcode_atts) {
            //size is already sanitized in the YasrShortcode costructor
            return  $shortcode_atts->size;
        });

        $cleaned_atts = array(
            'order_by'      => 'DESC',  //@todo add this parameter
            'limit'         => $shortcode_atts->rows,
            'custom_txt'    => $shortcode_atts->text,
            'text_position' => $shortcode_atts->text_position,
            'start_date'    => $shortcode_atts->start_date,
            'end_date'      => $shortcode_atts->end_date,
            'ctg'           => null,
            'cpt'           => null,
        );

        if ($shortcode_atts->category) {
            $cleaned_atts['ctg'] = $shortcode_atts->category;
        } elseif ($shortcode_atts->custom_post) {
            $cleaned_atts['cpt'] = $shortcode_atts->custom_post;
        }

        return $cleaned_atts;
    }

    /**
     * Hooks to yasr_rankings_query_ov in YasrRankingData::rankingOverallGetResults
     * Query for pro version of Yasr Overall Rating charts
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param $atts
     *
     * @return array|object|null
     */
    public function queryOVRanking ($atts) {
        //convert shortcode attribute into sql string values
        $sql_params = YasrProRankings::setQueryAttributes($atts);

        global $wpdb;

        $query = "SELECT pm.meta_value AS rating,
                     pm.post_id
               FROM $wpdb->postmeta AS pm,
                    $wpdb->posts    AS p $sql_params[from_clause]
               WHERE pm.post_id     = p.ID
                  AND p.post_status = 'publish'
                  AND pm.meta_key   = 'yasr_overall_rating'
                  AND pm.meta_value > 0 $sql_params[and_clause]
                  $sql_params[date]
               ORDER BY pm.meta_value $sql_params[order_by],
                        pm.post_id
               LIMIT $sql_params[limit]";

        //Here I already know that values are safe, no need to use wpdb->prepare
        return $wpdb->get_results($query);
    }

    /**
     * Callback function to add params to Rankings by Visitor Votes
     *
     * used by:
     *     yasr_vv_rankings_atts for add params to yasr_most_or_highest_rated_posts
     *
     * returns an array of shortcode pro atts
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.2
     *
     * @param $shortcode_name
     * @param $atts
     *
     * @return array
     */
    public function filterVVRankingAtts($shortcode_name, $atts){
        $shortcode_atts = new YasrProRankings($atts, $shortcode_name);

        add_filter('yasr_size_ranking', function() use ($shortcode_atts) {
            //size is already sanitized in the YasrShortcode costructor
            return  $shortcode_atts->size;
        });

        $cleaned_atts = array(
            'order_by'                => 'DESC',  //@todo add this parameter
            'limit'                   => $shortcode_atts->rows,
            'view'                    => $shortcode_atts->view,
            'start_date'              => $shortcode_atts->start_date,
            'end_date'                => $shortcode_atts->end_date,
            'ctg'                     => null,
            'cpt'                     => null,
            'required_votes' => array(
                'most'    => $shortcode_atts->min_votes_most_rated,
                'highest' => $shortcode_atts->min_votes_highest_rated
            ),
        );

        if ($shortcode_atts->category) {
            $cleaned_atts['ctg'] = $shortcode_atts->category;
        } elseif ($shortcode_atts->custom_post) {
            $cleaned_atts['cpt'] = $shortcode_atts->custom_post;
        }

        if($shortcode_name === 'yasr_visitor_multi_set_ranking') {
            $cleaned_atts['setid'] = $shortcode_atts->set_id;
        }

        return $cleaned_atts;
    }

    /**
     * Hooks to yasr_rankings_query_vv in YasrRankingData::rankingVVGetResults
     * Query for pro version of Yasr Visitor Votes charts
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param $atts
     * @param $ranking
     *
     * @return array|object|null
     */
    public function queryVVRanking ($atts, $ranking) {
        global $wpdb;

        //convert shortcode attribute into sql string values
        $sql_params = YasrProRankings::setQueryAttributes($atts, $ranking);

        $common_query = "SELECT post_id,
                            COUNT(post_id) AS number_of_votes,
                            ROUND(SUM(vote) / COUNT(post_id), 1) AS rating
                         FROM " . YASR_LOG_TABLE . ",
                            $wpdb->posts AS p $sql_params[from_clause]
                         WHERE post_id = p.ID
                            AND p.post_status = 'publish' $sql_params[and_clause] $sql_params[date]
                         GROUP BY post_id";

        //Here I already know that values are safe, no need to use wpdb->prepare
        $query = $common_query . $sql_params['having_clause'] . $sql_params['orderby_clause'] . $sql_params['limit_clause'];
        return $wpdb->get_results($query);

    }

    /**
     * Hooks to yasr_rankings_multi_query in YasrRankingData::rankingMulti
     * Returns an array with the value of the ranking using yasr_multi_set data
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param $atts       //shortcode atts
     * @param $set_id     //the set id
     *
     * @return array|object|null|bool
     */
    public function queryMARanking ($atts, $set_id) {
        //convert shortcode attribute into sql string values
        $sql_params = YasrProRankings::setQueryAttributes($atts);

        global $wpdb;

        $query = "SELECT pm.post_id AS id
                FROM $wpdb->postmeta AS pm,
                     $wpdb->posts AS p $sql_params[from_clause]
                WHERE pm.post_id = p.ID
                    AND p.post_status = 'publish'
                    AND pm.meta_key = 'yasr_multiset_author_votes' $sql_params[and_clause]
                    $sql_params[date]
                ORDER BY pm.post_id";

        $array_post_id = $wpdb->get_results($query);

        if (!is_array($array_post_id) || empty($array_post_id)) {
            return false;
        }

        //set fields name and ids
        $average_array = YasrMultiSetData::returnMultiAuthorAverageArray($array_post_id, $set_id);

        //Limit the array to N results
        return array_slice($average_array, 0, $sql_params['limit']);

    }

    /**
     * Hooks to yasr_rankings_multivv_query in YasrRankingData::rankingMultiVV
     * Query for pro version of Yasr Visitor Votes charts
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param $atts
     * @param $ranking
     * @param $set_id
     *
     * @return array|object|null
     */
    public function queryMVRanking ($atts, $ranking, $set_id) {
        global $wpdb;

        //convert shortcode attribute into sql string values
        $sql_params = YasrProRankings::setQueryAttributes($atts, $ranking);

        $common_query = "SELECT CAST((SUM(l.vote)/COUNT(l.vote)) AS DECIMAL(2,1)) AS rating,
                           COUNT(l.vote) AS number_of_votes,
                           l.post_id
                         FROM " . YASR_LOG_MULTI_SET . " AS l,
                             $wpdb->posts AS p $sql_params[from_clause]
                         WHERE l.set_type = $set_id
                             AND p.ID = l.post_id
                             AND p.post_status = 'publish' $sql_params[and_clause]
                             $sql_params[date]
                         GROUP BY l.post_id";

        //Here I already know that values are safe, no need to use wpdb->prepare
        $query = $common_query . $sql_params['having_clause'] . $sql_params['orderby_clause'] . $sql_params['limit_clause'];
        return $wpdb->get_results($query);

    }


    /**
     * This function hooks into yasr_tr_rankings_atts and yasr_tu_rankings_atts
     * and returns an array of shortcode pro atts
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.3
     *
     * @param $default_value //this is always false
     * @param $atts
     *
     * @return array
     */
    public function filterRURankingAtts($default_value, $atts) {
        $shortcode_atts = new YasrProRankings($atts, 'yasr_top_reviewers');

        add_filter('yasr_tu_rankings_display', function($login, $user_data)  use ($shortcode_atts) {
            if($shortcode_atts->display === 'displayname') {
                return $user_data->display_name;
            }
            return $login;
        },10, 2);

        return array(
            'order_by'                => 'DESC',  //@todo add this parameter
            'limit'                   => $shortcode_atts->rows,
            'display'                 => $shortcode_atts->display
        );
    }


    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.2
     *
     * @param $atts
     *
     * @return string
     */
    public function queryTRRanking ($atts) {

        //convert shortcode attribute into sql string values
        $sql_params = YasrProRankings::setQueryAttributes($atts);

        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT COUNT( pm.post_id ) AS total_count,
                        p.post_author AS user
                    FROM $wpdb->posts AS p,
                         $wpdb->postmeta AS pm
                    WHERE pm.post_id = p.ID
                        AND pm.meta_key = 'yasr_overall_rating'
                        AND p.post_status = 'publish'
                    GROUP BY user
                    ORDER BY total_count DESC
                    LIMIT %d",
            $sql_params['limit'] );

        return $wpdb->get_results($query);
    }


    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.2
     *
     * @param $atts
     *
     * @return string
     */
    public function queryTURanking ($atts) {

        //convert shortcode attribute into sql string values
        $sql_params = YasrProRankings::setQueryAttributes($atts);

        global $wpdb;

        $query = $wpdb->prepare(
            'SELECT COUNT(user_id) as total_count,
                        user_id as user
                    FROM ' . YASR_LOG_TABLE . ",
                        $wpdb->posts AS p
                    WHERE  post_id = p.ID
                        AND p.post_status = 'publish'
                    GROUP BY user_id
                    ORDER BY ( total_count ) DESC
                    LIMIT %d",
            $sql_params['limit']);

        return $wpdb->get_results($query);
    }


    /**
     * @depreacted deprecated since 2.6.2
     * remove for DEC 2021
     * @author     Dario Curvino <@dudo>
     *
     * @param $atts
     * @param $content
     * @param $shortcode_tag
     *
     * @return string
     */
    public function crShortcodeOverallRatingChart($atts, $content, $shortcode_tag) {
        $cr_overall_ranking_obj = new YasrProRankings($atts, $shortcode_tag);

        return $cr_overall_ranking_obj->returnHighestRatedOverall($atts);
    }

    /**
     * @depreacted deprecated since 2.6.2
     * remove for DEC 2021
     * @author     Dario Curvino <@dudo>
     *
     * @param $atts
     * @param $content
     * @param $shortcode_tag
     *
     * @return string
     */
    public function crShortcodeVVChart($atts, $content, $shortcode_tag) {
        $cr_ranking_obj = new YasrProRankings($atts, $shortcode_tag);
        return $cr_ranking_obj->vvReturnMostHighestRated($atts);
    }


    /**
     * @author Dario Curvino <@dudo>
     *
     * @param $atts
     *
     * @return string|void
     */
    public function crMostActiveReviewers($atts) {
        $pro_most_active_reviewers = new YasrProNoStarsRankings($atts,'yasr_pro_most_active_reviewers');
        return $pro_most_active_reviewers->returnTopReviewersPro();
    }

    /**
     * @author Dario Curvino <@dudo>
     * @param $atts
     *
     * @return false|string
     */
    public function crMostActiveUsers($atts) {
        $pro_most_active_users_obj = new YasrProNoStarsRankings($atts,'yasr_pro_most_active_users');
        return $pro_most_active_users_obj->returnTopUsersPro();
    } //End function


    /**
     * Callback to print the ranking from comments
     *
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @param       $atts
     * @param       $content
     * @param false $shortcode_tag
     *
     * @return string
     */
    public function urRankingReview ($atts, $content, $shortcode_tag) {
        $ranking_comment = new YasrRankings(false, $shortcode_tag);
        //convert shortcode attribute into sql string values
        $sql_params = $this->filterVVRankingAtts($shortcode_tag, $atts);

        //set the ranking source
        $source = 'comments';

        $ranking_comment->shortcode_html = '<!-- Yasr Most Or Highest Rated Shortcode -->';

        $ranking_comment->query_result_most_rated_visitor    = self::rankingsByCommentsData($sql_params, 'most');
        $ranking_comment->query_result_highest_rated_visitor = self::rankingsByCommentsData($sql_params, 'highest');

        $ranking_comment->returnDoubleTableRanking($ranking_comment->urlencodeAtts($sql_params), $source);
        $ranking_comment->shortcode_html .= '<!--End Yasr TMost Or Highest Rated Shortcode -->';

        YasrScriptsLoader::loadRankingsJs();

        return $ranking_comment->shortcode_html;

    }

    /***
     * Return the query
     *
     * @param $atts
     * @param $ranking
     *
     * @return array|object|null
     */
    public static function rankingsByCommentsData ($atts, $ranking) {
        global $wpdb;

        //convert shortcode attribute into sql string values
        $sql_params = YasrProRankings::setQueryAttributes($atts, $ranking);

        $common_query =
            "SELECT c.comment_post_ID AS post_id,
                        COUNT(m.meta_value) AS number_of_votes,
                        (SUM(m.meta_value) / COUNT(m.meta_value)) AS rating
                    FROM $wpdb->commentmeta AS m,
                         $wpdb->comments AS c,
                         $wpdb->posts AS p $sql_params[from_clause]
                    WHERE m.comment_id = c.comment_ID
                        AND m.meta_key = 'yasr_pro_visitor_review_rating'
                        AND c.comment_approved = 1
                        AND p.ID = c.comment_post_ID
                        AND p.post_status = 'publish' $sql_params[and_clause]
                    GROUP BY post_id ";

        $sql_query = $common_query . $sql_params['having_clause'] . $sql_params['orderby_clause'] . $sql_params['limit_clause'];

        return $wpdb->get_results($sql_query);
    }

    /**
     * Add params to ajax/rest request, and return them.
     *
     * If used with rest, params must exists in register_rest_route to work
     *
     * e.g. yasr-rankings/overall_rating?limit=2&order_by=desc (for rest)
     * e.g. admin-ajax.php?action=yasr_load_rankings&source=visitor_votes&limit=2&order_by=desc
     *
     * @author Dario Curvino <@dudo>
     * @since 2.5.2
     *
     * @param $sql_params
     * @param $request
     *
     * @return mixed
     */
    public function filterRankingsRequest ($sql_params, $request) {
        $new_sql_params = false;

        if(isset($request['order_by'])) {
            $new_sql_params['order_by']       = $request['order_by'];
        }
        if(isset($request['limit'])) {
            $new_sql_params['limit']          = $request['limit'];
        }

        //check if isset and is not 0 or '0' or false or empty
        if(isset($request['start_date']) && $request['start_date']) {
            $new_sql_params['start_date']     = $request['start_date'];
        }

        //check if isset and is not 0 or '0' or false or empty
        if(isset($request['end_date'])  && $request['end_date']) {
            $new_sql_params['end_date']       = $request['end_date'];
        }
        if(isset($request['ctg'])) {
            $new_sql_params['ctg']            = $request['ctg'];
        }
        if(isset($request['cpt'])) {
            $new_sql_params['cpt']            = $request['cpt'];
        }
        if(isset($request['required_votes'])) {
            $new_sql_params['required_votes'] = $request['required_votes'];
        }

        if($new_sql_params !== false) {
            return $new_sql_params;
        }

        return $sql_params;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.2
     *
     * @param $data_to_return
     * @param $source
     * @param $request
     * @param $sql_params
     *
     * @return false|mixed
     */
    public function commentRanking($data_to_return, $source, $request, $sql_params) {
        if($source === 'comments') {
            //outside 'most', only 'highest' is allowed
            $ranking                = ($request['show'] === 'highest') ? $request['show'] : 'most';
            $data_to_return['show'] = $ranking;

            $vv_data = self::rankingsByCommentsData($sql_params, $ranking);
            if($vv_data === false){
                $data_to_return = false;
            }
            else {
                $data_to_return['data_vv'] = YasrRankings::rankingData($vv_data);
            }
            return $data_to_return;
        }

        return $data_to_return;
    }

}
