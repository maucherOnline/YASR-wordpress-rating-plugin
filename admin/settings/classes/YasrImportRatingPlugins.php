<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@gmail.com)

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

class YasrImportRatingPlugins {

    /**
     * Add ajax action for plugin import
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.6
     */
    public function addAjaxActions () {
        add_action( 'wp_ajax_yasr_import_wppr', array($this, 'wpprAjaxCallback') );

        add_action( 'wp_ajax_yasr_import_kksr', array($this, 'kksrAjaxCallback') );

        add_action( 'wp_ajax_yasr_import_ratemypost', array($this, 'ratemypostAjaxCallback') );

        add_action( 'wp_ajax_yasr_import_mr', array($this, 'mrAjaxCallback') );

    }

    /**
     * Return true if wp post ratings is installed
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return bool
     */
    public function searchWPPR() {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('wp-postratings/wp-postratings.php')) {
            return true;
        }
        return false;
    }

    /**
     * Return true if KK star ratings is installed
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return bool
     */
    public function searchKKSR() {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('kk-star-ratings/index.php')) {
            return true;
        }
        return false;
    }

    /**
     * Return true if rate my post is installed
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return bool
     */
    public function searchRMP() {
        if (is_plugin_active('rate-my-post/rate-my-post.php')) {
            return true;
        }
        global $wpdb;

        $rmp_table = $wpdb->prefix . 'rmp_analytics';

        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '%s'", $rmp_table)) === $rmp_table) {
            return true;
        }
        return false;
    }

    /**
     * Return true if multi rating is installed
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return bool
     */
    public function searchMR() {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('multi-rating/multi-rating.php')) {
            return true;
        }
        return false;
    }

    /**
     * Returns the number of necessary INSERT query for Wp post rating
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return int|mixed
     */
    public function wpprQueryNumber() {
        $number_of_query_transient = get_transient('yasr_wppr_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->returnWPPRData();

        //set counter to 0
        $i = 0;

        if (empty($logs)) {
            return 0;
        }

        //count insert queries
        foreach ($logs as $column) {
            for ($j = 1; $j <= $column->ratings_users; $j++) {
                $i++;
            }
        }

        set_transient('yasr_wppr_import_query_number', $i, DAY_IN_SECONDS);

        return $i;

    }

    /**
     * Returns the number of necessary INSERT query for KK star ratings
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return int|mixed
     */
    public function kksrQueryNumber() {
        $number_of_query_transient = get_transient('yasr_kksr_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->returnKKSRData();

        //set counter to 0
        $i = 0;

        if (empty($logs)) {
            return 0;
        }

        //count insert queries
        foreach ($logs as $column) {
            for ($j = 1; $j <= $column->ratings_users; $j++) {
                $i++;
            }
        }

        set_transient('yasr_kksr_import_query_number', $i, DAY_IN_SECONDS);

        return $i;

    }

    /**
     * Returns the number of necessary INSERT query for rate my post
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return int|mixed
     */
    public function rmpQueryNumber() {
        global $wpdb;

        $number_of_query_transient = get_transient('yasr_rmp_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->returnRMPData();

        if (empty($logs)) {
            return 0;
        }

        set_transient('yasr_rmp_import_query_number', $wpdb->num_rows, DAY_IN_SECONDS);

        return $wpdb->num_rows;

    }

    /**
     * Returns the number of necessary INSERT query for multi rating
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return int|mixed
     */
    public function mrQueryNumber() {
        $number_of_query_transient = get_transient('yasr_mr_import_query_number');

        if ($number_of_query_transient !== false) {
            return $number_of_query_transient;
        }

        $logs = $this->returnMRData();

        //set counter to 0
        $i = 0;

        if (empty($logs)) {
            return 0;
        }

        //count insert queries
        foreach ($logs as $column) {
            for ($j = 1; $j <= $column->ratings_users; $j++) {
                $i++;
            }
        }
        set_transient('yasr_mr_import_query_number', $i, DAY_IN_SECONDS);

        return $i;

    }

    /**
     * Get WpPostRating Data
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return array|int|object|\stdClass[]
     */
    public function returnWPPRData() {
        global $wpdb;

        $logs = $wpdb->get_results(
            "SELECT pm.post_id, 
                        MAX(CASE WHEN pm.meta_key = 'ratings_average' THEN pm.meta_value END) as ratings_average,
                        MAX(CASE WHEN pm.meta_key = 'ratings_users' THEN pm.meta_value END) as ratings_users
                   FROM $wpdb->postmeta as pm,
                         $wpdb->posts as p
                   WHERE pm.meta_key IN ('ratings_average', 'ratings_users')
                       AND pm.meta_value <> 0
                       AND pm.post_id = p.ID
                   GROUP BY pm.post_id
                   ORDER BY pm.post_id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

    /**
     * Get KK star rating data
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return array|int|object|\stdClass[]
     */
    public function returnKKSRData() {
        global $wpdb;

        $logs = $wpdb->get_results(
            "SELECT pm.post_id, 
                        MAX(CASE WHEN pm.meta_key = '_kksr_avg' THEN pm.meta_value END) as ratings_average,
                        MAX(CASE WHEN pm.meta_key = '_kksr_casts' THEN pm.meta_value END) as ratings_users
                    FROM $wpdb->postmeta as pm,
                         $wpdb->posts as p
                    WHERE pm.meta_key IN ('_kksr_avg', '_kksr_casts')
                        AND pm.meta_value <> 0
                        AND pm.post_id = p.ID
                    GROUP BY pm.post_id
                    ORDER BY pm.post_id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

    /**
     * Get rate my post data
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return array|int|object|\stdClass[]
     */
    public function returnRMPData() {
        global $wpdb;

        $rmp_table = $wpdb->prefix . 'rmp_analytics';

        //get logs
        $logs = $wpdb->get_results(
            "SELECT rmp.post AS post_id,
                       rmp.value as vote, 
                       rmp.time AS date,
                       p.ID
                    FROM $rmp_table AS rmp, 
                        $wpdb->posts AS p
                    WHERE rmp.post = p.id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

    /**
     * get multi rating data
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @return array|int|object|\stdClass[]
     */
    public function returnMRData() {
        global $wpdb;

        $logs = $wpdb->get_results(
            "SELECT pm.post_id, 
                        MAX(CASE WHEN pm.meta_key = 'mr_rating_results_star_rating' THEN pm.meta_value END) as ratings_average,
                        MAX(CASE WHEN pm.meta_key = 'mr_rating_results_count_entries' THEN pm.meta_value END) as ratings_users
                    FROM $wpdb->postmeta as pm,
                         $wpdb->posts as p
                    WHERE pm.meta_key IN ('mr_rating_results_star_rating', 'mr_rating_results_count_entries')
                        AND pm.meta_value <> 0
                        AND pm.post_id = p.ID
                    GROUP BY pm.post_id 
                    ORDER BY pm.post_id"
        );

        if (empty($logs)) {
            return 0;
        }

        return $logs;
    }

    /**
     * Ajax callback for import data from WordPress post Ratings
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     */
    public function wpprAjaxCallback() {

        if($_POST['nonce']) {
            $nonce = $_POST['nonce'];
        } else {
            exit();
        }

        if (!wp_verify_nonce( $nonce, 'yasr-import-wppr-action' ) ) {
            die('Error while checking nonce');
        }

        if (!current_user_can( 'manage_options' ) ) {
            die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
        }

        global $wpdb;

        //get logs
        //With Wp Post Rating I need to import postmeta.
        //It has his own table too, but can be disabled in the settings.
        //The only way to be sure is get the postmeta

        $wppr = new YasrImportRatingPlugins();

        $logs = $wppr->returnWPPRData();

        if(empty($logs)) {
            echo json_encode(esc_html__('No WP Post Rating data found'));
        } else {
            $result = false;

            /****** Insert logs ******/
            foreach ($logs as $column) {

                if($column->ratings_average > 5) {
                    $column->ratings_average = 5;
                }

                for ($i=1; $i<=$column->ratings_users; $i++) {

                    //check if rating_average is not null.
                    //I found out that sometimes Wp Post Rating can save value with null data (sigh!!)
                    if ($column->ratings_average !== null) {

                        $result = $wpdb->replace(
                            YASR_LOG_TABLE,
                            array(
                                'post_id'      => $column->post_id,
                                'user_id'      => 0, //not stored on wp post rating
                                'vote'         => $column->ratings_average,
                                'date'         => 'wppostrating', //not stored on wp post rating
                                'ip'           => 'wppostrating'//not stored on wp post rating
                            ),
                            array('%d', '%d', '%f', '%s', '%s')
                        );
                    }
                }
            }

            if ($result) {
                yasr_save_option_imported_plugin('wppr');

                $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
                echo json_encode($string_to_return);
            }

        }
        die();
    }

    /**
     * Ajax callback for import data from KK Star Ratings
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     */
    public function kksrAjaxCallback() {

        if($_POST['nonce']) {
            $nonce = $_POST['nonce'];
        } else {
            exit();
        }

        if (!wp_verify_nonce( $nonce, 'yasr-import-kksr-action' ) ) {
            die('Error while checking nonce');
        }

        if (!current_user_can( 'manage_options' ) ) {
            die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
        }

        global $wpdb;

        //get logs
        //With KK star rating I need to import postmeta.
        $kksr = new YasrImportRatingPlugins();

        $logs= $kksr->returnKKSRData();

        if(empty($logs)) {
            echo json_encode(esc_html__('No KK Star Ratings data found'));
        } else {
            $result = false;

            /****** Insert logs ******/
            foreach ($logs as $column) {
                if($column->ratings_average > 5) {
                    $column->ratings_average = 5;
                }

                for ($i=1; $i<=$column->ratings_users; $i++) {
                    $result = $wpdb->replace(
                        YASR_LOG_TABLE,
                        array(
                            'post_id'      => $column->post_id,
                            'user_id'      => 0, //not stored on KK star rating
                            'vote'         => $column->ratings_average,
                            'date'         => 'kkstarratings', //not stored KK star rating
                            'ip'           => 'kkstarratings'//not stored KK star rating
                        ),
                        array('%d', '%d', '%f', '%s', '%s')
                    );
                }
            }

            if ($result) {
                yasr_save_option_imported_plugin('kksr');

                $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
                echo json_encode($string_to_return);
            }

        }
        die();
    }

    /**
     * Ajax callback for import data from rate My Post
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     */
    public function ratemypostAjaxCallback() {

        if($_POST['nonce']) {
            $nonce = $_POST['nonce'];
        } else {
            exit();
        }

        if (!wp_verify_nonce($nonce, 'yasr-import-ratemypost-action')) {
            die('Error while checking nonce');
        }

        if (!current_user_can( 'manage_options' ) ) {
            die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
        }

        global $wpdb;

        $rmp = new YasrImportRatingPlugins();

        //get logs
        $logs=$rmp->returnRMPData();

        if(empty($logs)) {
            echo json_encode(esc_html__('No Rate My Post data found'));
        } else {
            $result = false;

            /****** Insert logs ******/
            foreach ($logs as $column) {
                $result = $wpdb->replace(
                    YASR_LOG_TABLE,
                    array(
                        'post_id'      => $column->post_id,
                        'user_id'      => 0, //seems like rate my post store all users like -1, so I cant import the user_id
                        'vote'         => $column->vote,
                        'date'         => $column->date,
                        'ip'           => 'ratemypost'
                    ),
                    array('%d', '%d', '%f', '%s', '%s')
                );
            }

            if ($result) {
                yasr_save_option_imported_plugin('rmp');

                $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
                echo json_encode($string_to_return);
            }
        }
        die();
    }

    /**
     * Ajax callback for import data from multi rating
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     */
    public function mrAjaxCallback() {

        if($_POST['nonce']) {
            $nonce = $_POST['nonce'];
        } else {
            exit();
        }

        if (!wp_verify_nonce( $nonce, 'yasr-import-mr-action' ) ) {
            die('Error while checking nonce');
        }

        if (!current_user_can( 'manage_options' ) ) {
            die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
        }

        global $wpdb;

        $mr_exists = new YasrImportRatingPlugins();

        //get logs
        //With Multi Rating I need to import postmeta.
        $logs=$mr_exists->returnMRData();

        if(empty($logs)) {
            echo json_encode(esc_html__('No Multi Rating data found'));
        } else {
            $result = false;

            /****** Insert logs ******/
            foreach ($logs as $column) {

                if($column->ratings_average > 5) {
                    $column->ratings_average = 5;
                }

                for ($i=1; $i<=$column->ratings_users; $i++) {
                    $result = $wpdb->replace(
                        YASR_LOG_TABLE,
                        array(
                            'post_id'      => $column->post_id,
                            'user_id'      => 0, //not stored on KK star rating
                            'vote'         => $column->ratings_average,
                            'date'         => 'multirating', //not stored KK star rating
                            'ip'           => 'multirating'//not stored KK star rating
                        ),
                        array('%d', '%d', '%f', '%s', '%s')
                    );
                }
            }

            if ($result) {
                yasr_save_option_imported_plugin('mr');

                $string_to_return = esc_html__('Woot! All data have been imported!', 'yet-another-stars-rating');
                echo json_encode($string_to_return);
            }

        }

        die();
    }

    /**
     * Returns an alert box
     *
     * @author Dario Curvino <@dudo>
     * @since  2.0.0
     * @param $plugin
     * @param $number_of_queries
     */
    public function alertBox($plugin, $number_of_queries) {

        echo '<div class="yasr-alert-box">';
        echo wp_kses_post(sprintf(__(
            'To import %s seems like %s %d %s INSERT queries are necessary. %s
                There is nothing wrong with that, but some hosting provider can have a query limit/hour. %s
                I strongly suggest to contact your hosting and ask about your plan limit',
            'yet-another-stars-rating'
        ),$plugin, '<strong>', $number_of_queries, '</strong>', '<br />','<br />'));
        echo '</div>';

    }

}