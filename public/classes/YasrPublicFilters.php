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
class YasrPublicFilters {

    public function addFilters() {
        // Auto insert overall rating and visitor rating
        if (YASR_AUTO_INSERT_ENABLED === 1) {
            add_filter('the_content', array($this, 'autoInsert'));
        }

        //stars next to the title
        if (YASR_STARS_TITLE === 'yes') {
            add_filter('the_title', array($this, 'filterTitle'));
            add_action('wp_enqueue_scripts', static function() {
                YasrShortcode::enqueueScripts();
                YasrScriptsLoader::loadOVMultiJs();
            });
        }

    }

    /**
     * @param $content
     *
     * @return bool|string|void
     */
    public static function autoInsert($content) {
        //If this is a page and auto insert is excluded for pages, return
        if (YASR_AUTO_INSERT_EXCLUDE_PAGES === 'yes' && is_page()) {
            return $content;
        }

        if (YASR_AUTO_INSERT_CUSTOM_POST_ONLY === 'yes' && YasrCustomPostTypes::isCpt() === false) {
            return $content;
        }

        $post_id = get_the_ID();

        //check if for this post or page auto insert is off
        $post_excluded = get_post_meta($post_id, 'yasr_auto_insert_disabled', true);

        //hook here if you want to manually enable or disable the auto insert
        $disable_on_this_post = apply_filters('yasr_auto_insert_disable', $post_excluded, $content);

        if ($disable_on_this_post === 'yes') {
            return $content;
        }

        if(self::excludePostType() === true) {
            return $content;
        }

        //add stars to the content
        return self::addStarsToContent($content);

    } //End function yasr_auto_insert_shortcode_callback

    /**
     * If the current post type is excluded (the filter yasr_auto_insert_exclude_cpt must be used) return true
     *
     * It can be ANY post type, not only CPT
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.5
     *
     * @return bool
     */
    public static function excludePostType() {
        //create an empty array
        $excluded_cpt = array();

        //this hooks can be used to add cpt to exclude from the auto_insert
        $excluded_cpt = apply_filters('yasr_auto_insert_exclude_cpt', $excluded_cpt);

        //Excluded_cpt must be an array
        if (is_array($excluded_cpt) && !empty($excluded_cpt)) {
            //sanitize
            $excluded_cpt = filter_var_array($excluded_cpt, FILTER_UNSAFE_RAW);

            $post_type = get_post_type();

            //if one element in the array is found, return content
            foreach ($excluded_cpt as $cpt) {
                if ($cpt === $post_type) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Helper function to append stars
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.5
     * @param $content
     *
     * @return false|string
     */
    public static function addStarsToContent ($content) {
        $shortcode_align = YASR_AUTO_INSERT_ALIGN;

        //if it is not left, or right, default is center
        if ($shortcode_align !== 'left' && $shortcode_align !== 'right') {
            $shortcode_align = 'center';
        }

        $container_div_overall = "<div style='text-align:{$shortcode_align}' class='yasr-auto-insert-overall'>";
        $container_div_visitor = "<div style='text-align:{$shortcode_align}' class='yasr-auto-insert-visitor'>";
        $closing_div           = '</div>';

        $overall_rating_code   = $container_div_overall . '[yasr_overall_rating size="' . YASR_AUTO_INSERT_SIZE . '"]'
            . $closing_div;
        $visitor_votes_code    = $container_div_visitor . '[yasr_visitor_votes size="' . YASR_AUTO_INSERT_SIZE . '"]'
            . $closing_div;

        //avoid undefined
        $content_and_stars = false;

        if (YASR_AUTO_INSERT_WHAT === 'overall_rating') {
            switch (YASR_AUTO_INSERT_WHERE) {
                case 'top':
                    $content_and_stars = $overall_rating_code . $content;
                    break;

                case 'bottom':
                    $content_and_stars = $content . $overall_rating_code;
                    break;

                case 'both' :
                    $content_and_stars = $overall_rating_code . $content . $overall_rating_code;
                    break;
            } //End Switch
        }
        elseif (YASR_AUTO_INSERT_WHAT === 'visitor_rating') {
            switch (YASR_AUTO_INSERT_WHERE) {
                case 'top':
                    $content_and_stars = $visitor_votes_code . $content;
                    break;

                case 'bottom':
                    $content_and_stars = $content . $visitor_votes_code;
                    break;

                case 'both':
                    $content_and_stars = $visitor_votes_code . $content . $visitor_votes_code;
                    break;
            } //End Switch
        }
        elseif (YASR_AUTO_INSERT_WHAT === 'both') {
            switch (YASR_AUTO_INSERT_WHERE) {
                case 'top':
                    $content_and_stars = $overall_rating_code . $visitor_votes_code . $content;
                    break;

                case 'bottom':
                    $content_and_stars = $content . $overall_rating_code . $visitor_votes_code;
                    break;

                case 'both':
                    $content_and_stars = $overall_rating_code . $visitor_votes_code .
                        $content .
                        $overall_rating_code . $visitor_votes_code;
                    break;
            } //End Switch
        }

        return $content_and_stars;
    }


    /**
     * @since 2.4.3
     * Filter the_title to show stars next it
     *
     * @param $title
     *
     * @return string
     */
    public function filterTitle($title) {
        if (in_the_loop() && !is_feed()) {
            $post_id = get_the_ID();

            if (YASR_STARS_TITLE_EXCLUDE_PAGES === 'yes' && get_post_type($post_id) === 'page') {
                return $title;
            }

            $content_after_title = false;

            if (YASR_STARS_TITLE_WHAT === 'visitor_rating') {
                $content_after_title = $this->filterTitleVV($post_id);
            }

            if (YASR_STARS_TITLE_WHAT === 'overall_rating') {
                $content_after_title = $this->filterTitleOV($post_id);
            }

            //if only in archive pages
            if (YASR_STARS_TITLE_WHERE === 'archive') {
                if (is_archive() || is_home()) {
                    $this->removeWidgetTitleFromExcerptMore($content_after_title);
                    return $title . $content_after_title;
                }
            } //if only in single posts/pages
            elseif (YASR_STARS_TITLE_WHERE === 'single') {
                if (is_singular()) {
                    return $title . $content_after_title;
                }
            } //always return in both
            elseif (YASR_STARS_TITLE_WHERE === 'both' ) {
                //add the filter only if is archive or home
                if (is_archive() || is_home()) {
                    $this->removeWidgetTitleFromExcerptMore($content_after_title);
                }
                return $title . $content_after_title;
            } //else return just the title (if YASR_STARS_TITLE_WHERE is undefined, and should never happens)
            else {
                return $title;
            }
        }

        //if not in the loop
        return $title;
    }

    /**
     * Returns Visitor Votes (div and span)
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.1
     *
     * @param $post_id
     *
     * @return mixed|void
     */
    public function filterTitleVV($post_id) {
        //returns int
        $stored_votes = YasrDatabaseRatings::getVisitorVotes();

        $number_of_votes = $stored_votes['number_of_votes'];
        $average_rating  = $stored_votes['average'];

        $htmlid = 'yasr-visitor-votes-readonly-rater-' . str_shuffle(uniqid());

        $vv_widget = '<div class="yasr-vv-stars-title-container">';
        $vv_widget .= "<div class='yasr-stars-title yasr-rater-stars'
                          id='$htmlid'
                          data-rating='$average_rating'
                          data-rater-starsize='16'
                          data-rater-postid='$post_id'
                          data-rater-readonly='true'
                          data-readonly-attribute='true'
                      ></div>";
        $vv_widget .= "<span class='yasr-stars-title-average'>$average_rating ($number_of_votes)</span>";
        $vv_widget .= '</div>';

        YasrVisitorVotes::defineVvAttributes($stored_votes);

        //Use this hook to customize widget
        //if doesn't exist a filter for yasr_title_vv_widget, put $vv_widget into $content_after_title
        return apply_filters('yasr_title_vv_widget', $vv_widget, $stored_votes);
    }


    /**
     * Returns Overall Rating (span)
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.1
     *
     * @param $post_id
     *
     * @return mixed|void
     */
    public function filterTitleOV ($post_id) {
        $overall_rating = YasrDatabaseRatings::getOverallRating($post_id);

        //first, overall widget contains overall rating
        $overall_widget = '';

        //only if overall rating > 0
        if ($overall_rating > 0) {
            $overall_rating_obj = new YasrOverallRating(false, false);
            $overall_attributes = $overall_rating_obj->returnAttributes(
                16, $post_id, 'yasr-stars-title', $overall_rating
            );

            $overall_widget = "<span class='yasr-stars-title-average'>";
            $overall_widget .= $overall_attributes['html_stars'];
            $overall_widget .= "</span>";

            YasrOverallRating::defineOvAttributes($overall_rating);
        }


        //Use this hook to customize widget overall
        //if doesn't exists a filter for yasr_title_overall_widget, put $overall_widget into $content_after_title
        return apply_filters('yasr_title_overall_widget', $overall_widget, $overall_rating);
    }

    /**
     * Many theme print inside the <!--Read More--> tag a span with the title
     * This cause the widget breaking the layout.
     * More info here https://wordpress.stackexchange.com/questions/383212/filter-post-title-without-affecting-screen-reader-text
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.1
     *
     * @param $content_to_remove
     * @return void;
     */
    public function removeWidgetTitleFromExcerptMore ($content_to_remove) {
        add_filter('excerpt_more', function ($more_link_element) use ($content_to_remove) {
            $more_link_element = str_replace($content_to_remove, '', $more_link_element);
            return $more_link_element;
        },9999,1);
    }
}
