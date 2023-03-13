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
 * Class YasrLastRatingsWidget
 *
 * This class is used to show:
 *  - "Recent Ratings" widget in dashboard
 *  - "Your Ratings"   widget in dashboard
 *  - [yasr_user_rate_history] shortcode
 *
 */
class YasrLastRatingsWidget {

    private $log_query;

    private $main_container_id;

    private $user_widget = false;


    /**
     * This function will set the values for print the admin widget logs
     *
     * $this->user_widget
     * $this->log_query
     * $this->container_id
     *
     */
    public function adminWidget() {
        if (!current_user_can('manage_options')) {
            return;
        }
        global $wpdb;

        //query for admin widget
        $number_of_rows = $wpdb->get_var(
            "SELECT COUNT(*) FROM "
            . YASR_LOG_TABLE
        );

        $this->log_query = "SELECT * FROM "
                           . YASR_LOG_TABLE .
                           " ORDER BY date DESC LIMIT %d, %d ";

        $this->main_container_id = 'yasr-log-container';

        echo wp_kses_post($this->returnWidget($number_of_rows));
    }

    /**
     * This function will set the values for print the user widget logs
     * $this->user_widget
     * $this->log_query
     * $this->container_id
     * $this->button_class
     *
     * @return string
     */
    public function userWidget() {
        $user_id = get_current_user_id();

        if($user_id === 0) {
            return '<p>'.__('You must login to see this widget.', 'yet-another-stars-rating').'</p>';
        }

        //set true to user widget
        $this->user_widget = true;

        global $wpdb;

        $number_of_rows = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) 
                          FROM $wpdb->posts AS p, " . YASR_LOG_TABLE . " AS l  
                          WHERE user_id = %d
                              AND p.ID = l.post_id ",
                $user_id)
        );

        $this->log_query = "SELECT * 
                                FROM $wpdb->posts AS p, " . YASR_LOG_TABLE . " AS l 
                            WHERE user_id = $user_id 
                               AND p.ID = l.post_id  
                            ORDER BY date 
                            DESC LIMIT %d, %d ";

        $this->main_container_id = 'yasr-user-log-container';

        return $this->returnWidget($number_of_rows);
    }

    /**
     * Return the widget
     *
     * @return string
     */
    private function returnWidget($number_of_rows) {
        global $wpdb;

        $limit = 8;

        if($number_of_rows > 0) {
            $n_of_pages = ceil($number_of_rows / $limit);
        } else {
            $n_of_pages = 1;
        }

        //do the query
        $log_result = $wpdb->get_results(
            $wpdb->prepare(
                $this->log_query,
                0, $limit)
        );

        if (!$log_result) {
            return __('No Recent votes yet', 'yet-another-stars-rating');
        }

        $html_to_return  = "<div class='yasr-log-container' id='$this->main_container_id'>";

        $html_to_return .= $this->loopResults($log_result);

        $html_to_return .= $this->pagination($n_of_pages);

        $html_to_return .= '</div>'; //End Yasr Log Container

        return $html_to_return;
    }

    /**
     * Loop the query results and return the html with content
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.4
     *
     * @param $query_results
     *
     * @return string|void
     */
    public function loopResults ($query_results) {
        $i = 0;

        if(!is_array($query_results)) {
            return;
        }

        $rows = '';

        foreach ($query_results as $result) {
            $user = get_user_by('id', $result->user_id); //Get info user from user id

            //If user === false means that the vote are anonymous
            if ($user === false) {
                $user             = new stdClass;
                $user->user_login = __('anonymous', 'yet-another-stars-rating');
            }

            $avatar     = get_avatar($result->user_id, '32'); //Get avatar from user id
            $post_title = get_post_field( 'post_title', $result->post_id, 'raw' ); //Get post title from post id
            $link       = get_permalink($result->post_id); //Get post link from post id

            //Default values (for admin widget)
            $ip_span = ''; //default value

            //Set value depending if we're on user or admin widget
            if ($this->user_widget !== true) {
                if (YASR_ENABLE_IP === 'yes') {
                    $ip_span = '<span class="yasr-log-ip">' . __('Ip address', 'yet-another-stars-rating') . ': 
                                    <span style="color:blue">' . $result->ip . '</span>
                                </span>';
                }
            }

            $rows .= $this->rowContent($avatar, $i, $user, $link, $post_title, $ip_span, $result);

            $i = $i +1;
        } //End foreach

        return $rows;
    }

    /**
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.4
     *
     * @param $avatar
     * @param $i
     * @param $user
     * @param $link
     * @param $post_title
     * @param $ip_span
     * @param $column
     *
     * @return string
     */
    private function rowContent ($avatar, $i, $user, $link, $post_title, $ip_span, $column) {
        $vote = (int)$column->vote;

        if ($this->user_widget !== true) {
            $yasr_log_vote_text = ' ' . sprintf(
                    __('Vote %d from %s on', 'yet-another-stars-rating'),
                    $vote,
                    '<strong style="color: blue">' . $user->user_login . '</strong>'
                );
        } else {
            $yasr_log_vote_text = ' ' . sprintf(
                    __('You rated %s on', 'yet-another-stars-rating'),
                    '<span id="yasr-user-log-vote-'.$i.'" style="color: blue;">' . $vote . '</span>'
                );
        }
        if($this->user_widget === true) {
            $container_id = "yasr-user-log-div-child-$i";
            $text_id      = "yasr-user-log-text-$i";
            $title_id     = "yasr-user-log-post-$i";
            $date_id      = "yasr-user-log-date-$i";
        } else {
            $container_id = "yasr-log-div-child-$i";
            $text_id      = "yasr-log-text-$i";
            $title_id     = "yasr-log-post-$i";
            $date_id      = "yasr-log-date-$i";
        }

        return "<div class='yasr-log-div-child' id='".esc_attr($container_id)."'>
                    <div class='yasr-log-image'>
                        $avatar
                    </div>
                    <div class='yasr-log-child-head'>
                        <span class='yasr-log-vote' id='".esc_html($text_id)."'>
                            $yasr_log_vote_text
                        </span>
                        <span class='yasr-log-post' id='".esc_html($title_id)."'>
                            <a href='$link'>".esc_html($post_title)."</a>
                        </span>
                    </div>
                    <div class='yasr-log-ip-date'>
                        $ip_span
                        <span class='yasr-log-date' id='".esc_html($date_id)."'>
                            $column->date
                        </span>
                    </div>
              </div>";
    }

    /**
     * This function will print the row with pagination
     */
    private function pagination($n_of_pages) {
        if($this->user_widget === true) {
            $container_id     = 'yasr-user-log-page-navigation-buttons';
            $span_loader_id   = 'yasr-loader-user-log-metabox';
            $span_total_pages = 'yasr-user-log-total-pages';
            $button_class     = 'yasr-user-log-page-num';

        } else {
            $container_id     = 'yasr-log-page-navigation-buttons';
            $span_loader_id   = 'yasr-loader-log-metabox';
            $span_total_pages = 'yasr-log-total-pages';
            $button_class     = 'yasr-log-page-num';
        }

        $html_pagination = "<div id='yasr-log-page-navigation'>";

        $html_pagination .= "<div id='".esc_attr($span_total_pages)."' 
                                 data-yasr-log-total-pages='$n_of_pages' 
                                 style='display: inline'>";
        $html_pagination .= __('Pages', 'yet-another-stars-rating') . ": ($n_of_pages) &nbsp;&nbsp;&nbsp;";
        $html_pagination .= '</div>';


        $html_pagination .= '<div id="'.esc_html($container_id).'" style="display: inline">';

        //current page (always the first) plus one
        $end_for = 2;

        if ($end_for >= $n_of_pages) {
            $end_for = $n_of_pages;
        }

        for ($i = 1; $i <= $end_for; $i++) {
            if ($i === 1) {
                $html_pagination .= "<button class='button-primary' 
                                             value='".esc_attr($i)."'>$i</button>&nbsp;&nbsp;";
            } else {
                $html_pagination .= "<button class='".esc_attr($button_class)."' 
                                             value='".esc_attr($i)."'>$i</button>&nbsp;&nbsp;";
            }
        }

        if ($n_of_pages > 3) {
            $html_pagination .= "...&nbsp;&nbsp;
                                <button class='".esc_attr($button_class)."'
                                    value='".esc_attr($n_of_pages)."'>
                                    Last &raquo;</button>
                                    &nbsp;&nbsp;";
        }

        $html_pagination .= '</div>';

        //loader
        $html_pagination .= "<span class='yasr-last-ratings-loader' id='".esc_attr($span_loader_id)."'>&nbsp;
                                <img alt='loader' src='" . esc_url(YASR_IMG_DIR) . "/loader.gif' >
                            </span>";

        $html_pagination .= '</div>'; //End yasr-log-page-navigation

        return $html_pagination;
    }
}