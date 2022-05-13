<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/**
 * Public filters
 *
 * @since 2.4.3
 *
 * Class YasrPublicFilters
 */
class YasrIncludesFilters {

    /**
     * This filters will hook for show custom texts
     *
     * @author Dario Curvino <@dudo>
     * @since 2.6.6
     *
     */
    public function filterCustomTexts() {
        add_filter('yasr_cstm_text_before_overall', array($this, 'filterTextOverall'), 10);
        add_filter('yasr_cstm_text_before_vv',      array($this, 'filterTextVVBefore'), 10, 3);
        add_filter('yasr_cstm_text_after_vv',       array($this, 'filterTextVVAfter'), 10, 3);
        add_filter('yasr_vv_saved_text',            array($this, 'filterTextRatingSaved'), 10);
        add_filter('yasr_vv_updated_text',          array($this, 'filterTextRatingUpdated'), 10);
        add_filter('yasr_cstm_text_already_voted',  array($this, 'filterTextAlreadyVoted'), 10);
        add_filter('yasr_must_sign_in',             array($this, 'filterTextMustSignIn'),10);
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.4
     */
    public function cachingPluginSupport () {
        //Autooptimize
        add_filter('autoptimize_filter_js_dontmove', static function ($excluded_files) {
            if(is_array($excluded_files)) {
                $excluded_files[] = 'wp-includes/js/dist/';
            }
            return $excluded_files;
        });

        //wp rocket
        add_filter('rocket_exclude_defer_js', static function  ($excluded_files) {
            if(is_array($excluded_files)) {
                $excluded_files[] = 'wp-includes/js/dist/';
            }
            return $excluded_files;
        });

        //Delete caches for supported plugins on visitor vote
        //Can't use is_singular() here because always return false
        add_action('yasr_action_on_visitor_vote',          array($this, 'deleteCaches'));
        add_action('yasr_action_on_visitor_multiset_vote', array($this, 'deleteCaches'));
    }

    /**
     * Get text_before_overall from db if exists and return it replacing %overall_rating% pattern with the vote
     *
     * @author Dario Curvino <@dudo>
     * @since 2.6.6
     *
     * @param $overall_rating
     *
     * @return string|string[]
     */
    public function filterTextOverall ($overall_rating) {
        return str_replace('%rating%', $overall_rating, YASR_TEXT_BEFORE_OVERALL);
    }

    /**
     * Get text_before_visitor_rating from db if exists and return it replacing the patterns with the votes
     *
     * @author Dario Curvino <@dudo>
     * @since 2.6.6
     *
     * @param $number_of_votes
     * @param $average_rating
     * @param $unique_id
     *
     * @return string|string[]
     */
    public function filterTextVVBefore ($number_of_votes, $average_rating, $unique_id) {
        //no need to escape, it is done later when string is printed
        return $this->strReplaceInput(YASR_TEXT_BEFORE_VR, $number_of_votes, $average_rating, $unique_id);
    }

    /**
     * Get text_after_visitor_rating from db if exists and return it
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.6
     *
     * @param $number_of_votes
     * @param $average_rating
     * @param $unique_id
     *
     * @return string|string[]
     */
    public function filterTextVVAfter ($number_of_votes, $average_rating, $unique_id) {
        $custom_text  = '<span id="yasr-vv-text-container-'.$unique_id.'" class="yasr-vv-text-container">';
        $custom_text .= YASR_TEXT_AFTER_VR;
        $custom_text .= '</span>';
        return $this->strReplaceInput($custom_text, $number_of_votes, $average_rating, $unique_id);
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.9.5
     *
     * @return mixed
     */
    public function filterTextRatingSaved() {
        return YASR_TEXT_RATING_SAVED;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.9.5
     *
     * @return mixed
     */
    public function filterTextRatingUpdated() {
        return YASR_TEXT_RATING_UPDATED;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.6.6
     * @param $rating
     *
     * @return array|string|string[]
     */
    public function filterTextAlreadyVoted ($rating) {
        return str_replace('%rating%', $rating, YASR_TEXT_USER_VOTED);
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.6
     *
     * @return mixed|string|void
     */
    public function filterTextMustSignIn () {
        return YASR_TEXT_MUST_SIGN_IN;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.5.9
     *
     * @param $subject
     * @param $number_of_votes
     * @param $average_rating
     * @param $unique_id
     *
     * @return string|string[]
     */
    protected function strReplaceInput($subject, $number_of_votes, $average_rating, $unique_id) {
        //This will contain the number of votes
        $number_of_votes_container  = '<span id="yasr-vv-votes-number-container-'. $unique_id .'">';

        //this will contain the average
        $average_rating_container   = '<span id="yasr-vv-average-container-'. $unique_id .'">';

        return str_replace(
            array(
                '%total_count%',
                '%average%'
            ),
            array(
                $number_of_votes_container . $number_of_votes . '</span>',
                $average_rating_container  . $average_rating  . '</span>'
            ),
            $subject
        );
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since refactored in 2.7.4
     * @param $array_action_visitor_vote
     */
    public function deleteCaches($array_action_visitor_vote) {
        if (isset($array_action_visitor_vote['post_id'])) {
            $post_id = $array_action_visitor_vote['post_id'];
        } else {
            return;
        }

        if (isset($array_action_visitor_vote['is_singular'])) {
            $is_singular = $array_action_visitor_vote['is_singular'];
        } else {
            return;
        }

        //Adds support for wp super cache
        if (function_exists('wp_cache_post_change')) {
            wp_cache_post_change($post_id);
        }

        //Adds support for wp rocket, thanks to GeekPress
        //https://wordpress.org/support/topic/compatibility-with-wp-rocket-2
        if (function_exists('rocket_clean_post')) {
            rocket_clean_post($post_id);
        }

        //Adds support for LiteSpeed Cache plugin
        if (method_exists('\LiteSpeed\Purge', 'purge_post')) {
            (new LiteSpeed\Purge)->purge_post($post_id);
        }

        //Adds support for Wp Fastest Cache
        if ($is_singular === 'true') {
            if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'singleDeleteCache')) {
                $GLOBALS['wp_fastest_cache']->singleDeleteCache(false, $post_id);
            }
        } else {
            if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
                $GLOBALS['wp_fastest_cache']->deleteCache();
            }
        }

        //cache enabler support
        if(class_exists('Cache_Enabler') &&
            method_exists('Cache_Enabler', 'clear_page_cache_by_post_id')) {
            Cache_Enabler::clear_page_cache_by_post_id($post_id);
        }

    }

}
