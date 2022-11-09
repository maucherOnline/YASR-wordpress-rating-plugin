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
 * @author Dario Curvino <@dudo>
 *
 * Class YasrProURIncludes
 */
class YasrProURIncludes {

    /**
     * @author Dario Curvino <@dudo>
     */
    public function init() {
        $pro_options = get_option('yasr_ur_general_options');

        if ($pro_options) {
            define('YASR_PRO_UR_COMMENT_AUTO_INSERT', $pro_options['comment_stars_auto_insert']);
            define('YASR_PRO_UR_COMMENT_STARS_SIZE', $pro_options['comment_stars_size']);
            define('YASR_PRO_UR_COMMENT_ALLOW_ANONYMOUS', $pro_options['comment_allow_anonymous']);
            define('YASR_PRO_UR_RATING_MANDATORY', $pro_options['comment_rating_mandatory']);
            define('YASR_PRO_UR_COMMENT_RICH_SNIPPET', $pro_options['comment_rich_snippet']);
            if (isset($pro_options['text_after_stars'])) {
                define('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS', $pro_options['text_after_stars']);
            }
            if (isset($pro_options['text_after_stars_archive'])) {
                define('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE', $pro_options['text_after_stars_archive']);
            }

        } else {
            //Avoid undefined
            define('YASR_PRO_UR_COMMENT_AUTO_INSERT', 'no');
            define('YASR_PRO_UR_COMMENT_STARS_SIZE', 'medium');
            define('YASR_PRO_UR_COMMENT_ALLOW_ANONYMOUS', 'no');
            define('YASR_PRO_UR_RATING_MANDATORY', 'no');
            define('YASR_PRO_UR_COMMENT_RICH_SNIPPET', 'no');
            define('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS', false);
            define('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE', false);
        }
        /****** End getting options ******/

        $this->addShortcodes();

    }

    /**
     * Enqueue new shortcodes
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     */
    public function addShortcodes() {
        add_shortcode('yasr_pro_average_comments_ratings',
            static function($atts, $content, $shortcode_tag=false) {
                return (new YasrProUrAverageShortcodes($atts, $shortcode_tag))->fiveStarsAverage();
            }
        );

        add_shortcode('yasr_pro_comments_ratings_progressbars',
            static function($atts, $content, $shortcode_tag=false) {
                return (new YasrProReviewBars($atts, $shortcode_tag))->averageCommentsRatingsProgressBars();
            }
        );

        add_shortcode('yasr_pro_average_comments_multiset',
            static function($atts, $content, $shortcode_tag=false) {
                return (new YasrProReviewMultiset($atts, $shortcode_tag))->returnReviewMultiset();
            }
        );

    }

}
