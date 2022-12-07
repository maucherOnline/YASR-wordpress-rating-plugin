<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProUrAverageShortcodes extends YasrShortcode {
    /**
     * Callback for yasr_pro_average_comments_ratings
     *
     * Print an average from the five stars rating of the post
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     * @return string|void
     */
    public function fiveStarsAverage () {
        $size = $this->starSize();

        $comments_average_obj   = new YasrCommentsRatingData();
        $comment_review_enabled = $comments_average_obj->commentReviewEnabled($this->post_id);
        $comments_stats         = $comments_average_obj->getCommentStats($this->post_id);

        if ($comment_review_enabled === 0 && $comments_stats === false) {
            return esc_html__('Comment Reviews for this post are disabled. Please enable it first', 'yasr-pro');
        }

        YasrScriptsLoader::loadOVMultiJs();

        //here, comments reviews are enabled but no reviews has been left yet
        if($comments_stats === false) {
            $comments_stats['average']    = 0;
            $comments_stats['n_of_votes'] = 0;
        }

        $average_html_id = yasr_return_dom_id('yasr-pro-average-comments-ratings-');

        $this->shortcode_html .= "<div class='yasr-rater-stars' 
                                       id='$average_html_id' 
                                       data-rater-starsize='$size' 
                                       data-rating='".$comments_stats['average']."'>
                                  </div>";

        $this->customTextAfter($comments_stats['n_of_votes'], $comments_stats['average']);

        return  $this->shortcode_html;

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.9.8
     * @param $n_of_votes
     * @param $average_rating
     */
    protected function customTextAfter($n_of_votes, $average_rating) {
        $string_to_search = false;

        //if is single page
        if (is_singular() && is_main_query()) {
            if (defined('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS')
                && YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS !== ''
            ) {
                $string_to_search = YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS;
            }
        }
        //if is archive page
        else {
            if (defined('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE')
                && YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE !== ''
            ) {
                $string_to_search = YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE;
            }
        }

        if ($string_to_search !== false) {
            $text_after_star = str_replace(
                array('%total_count%', '%average%'),
                array($n_of_votes, $average_rating),
                $string_to_search
            );
            $this->shortcode_html  .= '<div class="yasr-container-custom-text-comments-rating">
                                         <span id="yasr-custom-text-after-comments-rating">' . $text_after_star . '</span>
                                     </div>';
        }
    }

}