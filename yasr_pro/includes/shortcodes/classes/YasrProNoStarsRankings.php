<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProNoStarsRankings extends YasrProRankings {

    public function returnTopReviewersPro () {

        $rows    = $this->rows;
        $display = $this->display;

        global $wpdb;

        $query_result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT COUNT( pm.post_id ) AS total_count,
                        p.post_author AS reviewer
                    FROM $wpdb->posts AS p, 
                         $wpdb->postmeta AS pm
                    WHERE pm.post_id = p.ID
                        AND pm.meta_key = 'yasr_overall_rating'
                        AND p.post_status = 'publish'
                    GROUP BY reviewer
                    ORDER BY total_count DESC
                    LIMIT %d",
            $rows )
        );

        if ($query_result) {
            $shortcode_html = '<table class="yasr-rankings">
                                        <tr>
                                         <th>' . __('Author', 'yet-another-stars-rating') .'</th>
                                         <th>'. __('Reviews', 'yet-another-stars-rating') .'</th>
                                      </tr>';

            foreach ( $query_result as $result ) {
                $user_data = get_userdata($result->reviewer);

                if ( $user_data ) {
                    $user_profile = get_author_posts_url($result->reviewer);

                    if ( $display === 'displayname' ) {
                        $user = $user_data->display_name;
                    } else {
                        $user = $user_data->user_login;
                    }
                } else {
                    $user_profile = '#';
                    $user         = 'Anonymous';
                }

                $shortcode_html .= "<tr>
                                        <td><a href='$user_profile'>$user</a></td>
                                        <td>$result->total_count</td>
                                    </tr>";

            }

            $shortcode_html .= '</table>';

            return $shortcode_html;

        }

        return ( __( "Problem while retriving the most active reviewers chart. Did you published any review?" ) );

    }

    public function returnTopUsersPro () {

        $rows    = $this->rows;
        $display = $this->display;

        //Rows must be at least 2
        if ($rows <= 1) {
            $rows = 2;
        } //And no more than 30
        elseif ($rows > 30) {
            $rows = 30;
        }

        if ( $display !== 'login' && $display !== 'displayname') {
            $display = 'login';
        }

        global $wpdb;

        $query_result = $wpdb->get_results($wpdb->prepare(
            'SELECT COUNT(user_id) as total_count, 
                        user_id as user
                    FROM ' . YASR_LOG_TABLE . ", 
                        $wpdb->posts AS p
                    WHERE  post_id = p.ID
                        AND p.post_status = 'publish'
                    GROUP BY user_id
                    ORDER BY ( total_count ) DESC
                    LIMIT %d",
            $rows)
        );

        if ($query_result) {
            $shortcode_html = '<table class="yasr-rankings">
                                    <tr>
                                     <th>' . __('UserName', 'yet-another-stars-rating') . '</th>
                                     <th>' . __('Number of votes', 'yet-another-stars-rating') . '</th>
                                    </tr>';

            foreach ($query_result as $result) {
                $user_data = get_userdata($result->user);

                if ($user_data) {
                    $user_profile = get_author_posts_url($result->user);

                    if ($display === 'displayname') {
                        $user = $user_data->display_name;
                    } else {
                        $user = $user_data->user_login;
                    }

                } else {

                    $user_profile = '#';
                    $user         = 'Anonymous';

                }

                $shortcode_html .= "<tr>
                                    <td><a href='$user_profile'>$user</a></td>
                                    <td>$result->total_count</td>
                                </tr>";

            }

            $shortcode_html .= '</table>';

            return $shortcode_html;

        }

        esc_html_e("Problem while retrieving the top 10 active users chart. Are you sure you have votes to show?");

        return false;
    }
}