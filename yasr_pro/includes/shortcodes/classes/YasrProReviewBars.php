<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProReviewBars extends YasrShortcode{
    /**
     * Doesn't accept any parameter
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     * @return string|void
     */
    public function averageCommentsRatingsProgressBars() {

        YasrScriptsLoader::loadOVMultiJs();

        $comments_data_obj      = new YasrCommentsRatingData();
        $comment_review_enabled = $comments_data_obj->commentReviewEnabled($this->post_id);

        if ($comment_review_enabled === 1) {
            $comments_array = $comments_data_obj->getCommentsWithRatings($this->post_id);

            $unique_id = yasr_return_dom_id('yasr-pro-comment-reviews-stats-');

            $shortcode_html = '<div id="'.$unique_id.'" class="yasr-pro-comment-reviews-stats">';

            //If array is not empty means that at least exists a review for that post or page
            if (!empty($comments_array)) {
                //get all five stars ratings from comment form
                $all_ratings_unorederd = $this->returnAllCommentsRatings($comments_array);

                //get an array with all ratings grouped
                $all_ratings_grouped   = $this->countSameVotes($all_ratings_unorederd);

                //check and add the ratings that has not been given
                $rating_array          = $this->fillWithEmptyVotes($all_ratings_unorederd, $all_ratings_grouped);

                $shortcode_html        .= $this->returnsBarsWithRatings($rating_array, $comments_array);

            } else {
                $shortcode_html .= $this->returnEmptyBars();
            }

            $shortcode_html .= '</div>';

            return $shortcode_html;

        }

        return esc_html__('Comment Reviews for this post are disabled. Please enable it first', 'yasr-pro');

    }

    /**************** Helpers *******************/

    /**
     * Return an array with all five stars ratings, e.g.
     * A post has 2 reviews with rating 4, and a review with rating 2, the returning array will be:
     * array {
     *     [1]=> 4
     *     [2]=> 4
     *     [0]=> 2
     * }
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     *
     * @param  $comments_array
     *
     * @return array
     */
    protected function returnAllCommentsRatings($comments_array) {
        $comments_ids = array();

        //Creating a multidimensional array with all comments ids that has a rating
        foreach ($comments_array as $comment) {
            $comments_ids[] = $comment->comment_ID;
        }

        //create an array with the single ratings
        foreach ($comments_ids as $comment_id) {
            $existing_votes[]  = get_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', true);
        }
        //Sorting from high to low
        arsort($existing_votes);

        return $existing_votes;
    }

    /**
     * Returns an array with all ratings grouped, e.g.
     * A post has 2 reviews with rating 4, and a review with rating 2, the returning array will be:
     *
     * array {
     *      array {
     *          "vote"       => 4
     *          "n_of_votes" => 2
     *      }
     *      array {
     *          "vote"       => 2
     *          "n_of_votes" => 1
     *      }
     * }
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     *
     * @param  $existing_votes
     */
    protected function countSameVotes ($existing_votes) {
        //Counting the rating with same vote, the resulting array will be
        //[vote] => vote_occurence
        //both as int
        $rating_same_vote    = array_count_values($existing_votes);
        $single_rating_array = null; //avoid undefined

        $i = 1;
        //Create an array with the structure vote and n_of_votes
        foreach ($rating_same_vote as $key => $occurence) {
            $single_rating_array[$i]               = array();
            $single_rating_array[$i]['vote']       = $key;       //The key is the vote "name"
            $single_rating_array[$i]['n_of_votes'] = $occurence; //How many time that vote has been given
            $i++;
        }

        return $single_rating_array;
    }

    /**
     * Check if in all ratings there is someone missing, e.g.
     * A post has 2 reviews with rating 4, and a review with rating 1, this means that I need to return also ratings
     * 5, 3, and 1 with 0 as n_of_votes
     *
     *  array {
     *     array {
     *         "vote"       => 5
     *         "n_of_votes" => 0
     *     }
     *      array {
     *         "vote"       => 4
     *         "n_of_votes" => 2
     *      }
     *      array {
     *         "vote"       => 3
     *         "n_of_votes" => 0
     *      }
     *      array {
     *         "vote"       => 2
     *         "n_of_votes" => 1
     *      }
     *      array {
     *         "vote"       => 1
     *         "n_of_votes" => 0
     *      }
     * }
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     * @param  $all_ratings_unorederd array with all ratings, unordered
     * @param  $all_ratings_grouped   array with all ratings, grouped
     */
    protected function fillWithEmptyVotes ($all_ratings_unorederd, $all_ratings_grouped) {
        //find if there is some missing votes
        for ($i = 1; $i <= 5; $i++) {
            if (!in_array($i, $all_ratings_unorederd)) {
                $missing_vote[$i]               = array();
                $missing_vote[$i]['vote']       = $i;
                $missing_vote[$i]['n_of_votes'] = 0; //If is missing n_of_votes is 0
            }
        }

        //If array $missing vote is not empty, merge with $all_ratings_grouped rating array
        if (!empty($missing_vote)) {
            $rating_array = array_merge($all_ratings_grouped, $missing_vote);
        } else {
            $rating_array = $all_ratings_grouped;
        }

        //order array by $rating['votes'] from higher to lower
        arsort($rating_array);

        return $rating_array;
    }

    /**
     *
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     */
    protected function returnsBarsWithRatings ($rating_array, $comments_array) {
        $i = 5;
        $shortcode_html = '';

        //Increasing value for a vote
        $single_vote_increasing_value = 100 / count($comments_array); //this is the total number of review

        foreach ($rating_array as $single_rate) {
            //Find the bar value
            $bar_value = $single_vote_increasing_value * $single_rate['n_of_votes'];
            $bar_value = round($bar_value, 2) . '%';

            $shortcode_html .= self::returnProgressBarsContainer($i, $this->starName($i), $bar_value, $single_rate['n_of_votes']);

            --$i; //decrease i
        }

        return $shortcode_html;
    }

    /**
     * Return single form of word stars if counter is === 1
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     * @param  $i
     *
     * @return string
     */
    protected function starName ($i) {
        $stars_text     = esc_html__('stars', 'yasr-pro');

        if ($i === 1) {
            $stars_text = esc_html__('star', 'yasr-pro');
        }

        return $stars_text;
    }

    /**
     * Return all bars empty
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     */
    protected function returnEmptyBars () {
        $shortcode_html = '';
        for ($i = 5; $i > 0; $i --) {
            $shortcode_html .= self::returnProgressBarsContainer($i, $this->starName($i), 0, 0);
        }

        return $shortcode_html;
    }

    /**
     * Since 2.1.4, return the row with the bar
     *
     * @param int $i
     * @param string $stars_text
     * @param float $bar_value
     * @param int $number_of_votes
     *
     * @return string
     */
    protected static function returnProgressBarsContainer ($i, $stars_text, $bar_value, $number_of_votes) {
        return "<div class='yasr-progress-bar-row-container yasr-w3-container'>
                    <div class='yasr-progress-bar-name'> $i $stars_text </div>
                    <div class='yasr-single-progress-bar-container'>
                        <div class='yasr-w3-border'>
                            <div class='yasr-w3-amber' style='height:17px;width:$bar_value'></div>
                        </div>
                    </div>
                    <div class='yasr-progress-bar-votes-count'>" . $number_of_votes . "</div>
                    <br />
              </div>";
    }
}