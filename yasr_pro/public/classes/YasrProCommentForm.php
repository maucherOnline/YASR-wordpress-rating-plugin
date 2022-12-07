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

class YasrProCommentForm {
    public function init() {
        //Avoid double review and display new fields to be filled
        add_action('comment_form_before', array($this, 'avoidDoubleReview'));

        //Save metadata and setcookie
        add_action('comment_post',        array($this, 'saveRatings'));

        //If I'm not on admin pages, show the new input for store comments
        if (!is_admin()) {
            add_filter('comment_text',    array($this, 'displayFiveStarsInCommentList'), 999); //Show the new input fields
        }

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  refactor in 2.9.5
     */
    public function avoidDoubleReview() {
        $post_id = get_the_ID();

        $yasr_comment_rating_data_obj = new YasrCommentsRatingData();
        $comment_review_enabled       = (bool)$yasr_comment_rating_data_obj->commentReviewEnabled();

        if ($comment_review_enabled === true) {
            $user_logged = $this->actionsLoggedUsers($post_id);

            if($user_logged === false) {
                $this->actionsAnonymousUsers($post_id);
            }
        }
    }

    /**
     * Add or remove new fields in comment form
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.5
     * @param $post_id
     *
     * @return bool If user is logged or not
     */
    public function actionsLoggedUsers($post_id) {
        //check only if user is logged in
        if (is_user_logged_in()) {
            $comments_array = $this->fiveStarsCommentQuery($post_id);

            //If array is not empty means that review for that post or page exists
            //Only check for five stars review, no multiset
            if (!empty($comments_array)) {
                //Remove the new input fields, to support all themes (i.e. this is not need for twenty14 but is need for hueman)
                remove_action('comment_form_logged_in_after', array($this, 'printNewFields'));
            } else {
                add_action('comment_form_logged_in_after', array($this, 'printNewFields')); //New input fields, for logged in users
            }
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.9.5
     * @param $post_id
     */
    public function actionsAnonymousUsers ($post_id) {
        if(YASR_PRO_UR_COMMENT_ALLOW_ANONYMOUS === 'no') {
            add_action('comment_form_top', array($this, 'loginRequired')); //For non - logged in users
            return;
        }

        $cookie_name = 'yasr_rated_comment_' . $post_id;

        if (isset($_COOKIE[$cookie_name])) {
            $cookie_value = stripslashes($_COOKIE[$cookie_name]);
            $cookie_value = json_decode($cookie_value, true);

            if (in_array($post_id, $cookie_value)) {
                //Remove the new input fields, to support all themes (i.e. this is not needed for twenty14 but is needed for hueman)
                remove_action('comment_form_logged_in_after', array($this, 'printNewFields'));
                return;
            }
        }

        add_action('comment_form_before_fields', array($this, 'printNewFields')); //New input fields, for non logged in users
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.9.5
     * @param $post_id
     *
     * @return array|int|\WP_Comment[]
     */
    private function fiveStarsCommentQuery($post_id) {
        $post_id         = (int)$post_id;
        $current_user_id = get_current_user_id();

        //Settings argument to get comments
        //further doc https://codex.wordpress.org/Class_Reference/WP_Comment_Query
        $args = array(
            'post_id' => $post_id,
            'user_id' => $current_user_id,
            'meta_query' => array(
                array(
                    'key' => 'yasr_pro_visitor_review_rating',
                )
            ),
            'orderby' => 'meta_value'
        );

        //Define new class that allows querying WordPress database tables 'wp_comments' and 'wp_commentmeta'.
        $comments_query = new WP_Comment_Query;
        return $comments_query->query($args);
    }


    /**
     * Add empty new fields
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.5
     */
    public function printNewFields() {
        //load required js for reviews in comments
        YasrProScriptsLoader::loadReviewsInComments();

        $post_id = get_the_ID();
        ?>
        <div id="yasr-pro-container-review">
            <div id="yasr-pro-new-input-comment-form">
                <?php
                    echo $this->printInputFiveStars();
                    echo $this->printInputTitle();
                    $set_id = yasr_pro_multiset_reviews_enabled($post_id);
                    //if post meta exists, return the multiset
                    if($set_id !== false) {
                        //print an empty Multiset
                        echo wp_kses_post($this->printEmptyMultiSet($post_id, $set_id));
                    }
                ?>

           </div>

            <?php do_action('yasr_ur_add_custom_form_fields'); ?>
        </div>
        <?php
    }

    /**
     * Print Stars and hidden field for saving data
     *
     * @return string
     */
    private function printInputFiveStars() {
        return '
            <div id="yasr-pro-rating-name-comment-form">'
               . esc_html__('Overall Rating', 'yet-another-stars-rating') .
            '</div>
            <div id="yasr-pro-five-stars-review"
                 data-rater-starsize="' . esc_attr(yasr_pro_comment_star_size()) . '"
            >
            </div>
            <input type="hidden"
                   id="yasr-pro-visitor-review-rating"
                   name="yasr_pro_visitor_review_rating"
                   value="-1"
            >';
    }

    /**
     * Return an input field where insert the title
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.7
     */
    private function printInputTitle() {
        return '
        <p class="comment-form-title">
            <label for="yasr-pro-visitor-review-title"></label>'. //need to concatenate or it create a white space
            '<input id="yasr-pro-visitor-review-title"
                   name="yasr_pro_visitor_review_title"
                   type="text"
                   size="35"
                   tabindex="5"
                   placeholder="'.esc_attr__('Add a headline', 'yasr-pro' ).'"
            />
        </p>';
    }

    /**
     * @param int $post_id
     * @param int $set_id
     *
     * @return string
     */
    private function printEmptyMultiSet($post_id, $set_id) {
        $multiset_content = YasrDB::multisetFieldsAndID($set_id);

        $html_multiset    = $this->printMultiSet($multiset_content, $set_id, $post_id);

        //this must echo or wp_kses_post will strip this out
        echo '<input type="hidden" id="yasr-pro-multiset-review-rating"
                     name="yasr_pro_multiset_review_rating" value="-1">';

        return $html_multiset;
    }

    /************************************* Functions with rating data ***********************************/


    /**
     * Display new fields with data in comment list
     *
     * @param $html
     *
     * @return mixed|string
     */
    public function displayFiveStarsInCommentList($html) {
        if(is_feed()) {
            return $html;
        }
        if (have_comments()) {
            //load required js for reviews in comments
            YasrProScriptsLoader::loadReviewsInComments();

            $comment_id = get_comment_ID();

            if (!$comment_id) {
                return $html;
            }

            $comment      = get_comment($comment_id);
            $review_title = get_comment_meta($comment_id, 'yasr_pro_visitor_review_title',  true);
            $rating       = get_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', true);

            //if this is a reply be sure to just return the comment text
            if ($comment && $comment->comment_parent !== '0') {
                return $html;
            }

            if ($rating) {
                $review_body = '<span class="yasr-pro-rating-comment-body">'
                                   . wp_kses_post($comment->comment_content) .
                               '</span>';

                $author          = (int)get_comment($comment_id)->user_id; //get the user id
                $current_user_id = get_current_user_id();

                //if current user id is not 0 is needed to block anonymous user to edit reviews from other anonymous
                //If the user logged is the author of the review
                if ($current_user_id !== 0 && $author === $current_user_id) {
                    $rating_and_title = $this->editableReview($comment_id, (int)$comment->comment_post_ID, $rating, $review_title);
                } else {
                    $rating_and_title = $this->readOnlyReview($comment_id, (int)$comment->comment_post_ID, $rating, $review_title);
                }

                //if there is no hook for yasr_ur_display_custom_fields, $comment_id is returned
                $html = apply_filters('yasr_ur_display_custom_fields', $comment_id);

                if ($html === $comment_id) {
                    $html = '';
                }

                $html .= $rating_and_title;
                $html .= $review_body;
            }

            return $html;
        }

        return $html;
    }

    /**
     * Return an editable 5 stars review
     *
     * @param $comment_id
     * @param $rating
     * @param $review_title
     *
     * @return string
     */
    private function editableReview($comment_id, $post_id, $rating, $review_title) {
        $div_stars            = $this->printFiveStarsDiv($comment_id, $rating, false);

        $title_container_id   = 'yasr-pro-visitor-title-editable-div-'.$comment_id;

        //New row
        $review_title_span = '<div id="'.esc_attr($title_container_id).'"
                                  class="yasr-pro-visitor-title-editable-div">
                                  '.$this->printReviewTitle($comment_id, $review_title).'
                                  '.$this->printEditReviewTitle($comment_id, $review_title).'
                               </div>';

        $multi_set = '<br>'
            . $this->printMultisetTable($comment_id, $post_id, false, 'yasr-pro-update-review-multiset');

        return $div_stars.$review_title_span.$multi_set;
    }

    /**
     * Return a readOnly 5 stars Review
     *
     * @param $comment_id
     * @param $post_id
     * @param $rating
     * @param $review_title
     *
     * @return string
     */
    private function readOnlyReview($comment_id, $post_id, $rating, $review_title) {
        return $this->printFiveStarsDiv($comment_id, $rating).
               $this->printReviewTitle($comment_id, $review_title, false).
               $this->printMultisetTable($comment_id, $post_id, true);
    }

    /**
     * Print five stars div with data
     *
     * @author Dario Curvino <@dudo>
     * @since       2.9.7
     * @param       $comment_id
     * @param       $rating
     * @param bool  $readonly
     *
     * @return string
     */
    private function printFiveStarsDiv($comment_id, $rating, $readonly=true) {
        $px_size = yasr_pro_comment_star_size();
        $id =  'yasr-pro-review-'.$comment_id;

        if($readonly === false) {
            $div_container           = 'yasr-pro-loader-update-review-rating-'.$comment_id;
            $loader_and_vote_updated = '<div id="'.esc_attr($div_container).'"
                                                 class="yasr-pro-loader-update-vote-comment">&nbsp;</div>';
        } else {
            $loader_and_vote_updated = '';
        }

        $nonce = wp_create_nonce('yasr_pro_nonce_update_comment_rating');

        return "<div class='yasr-pro-rating-and-loader-comment'>
                    <div id='".esc_attr($id)."'
                        class='yasr-rater-stars-in-comment-rated'
                        data-rater-commentid='".esc_attr($comment_id)."'
                        data-rating='".esc_attr($rating)."'
                        data-rater-starsize='".esc_attr($px_size)."'
                        data-rater-readonly='".esc_attr(json_encode($readonly))."'
                        data-rater-nonce='".esc_attr($nonce)."'>
                    </div>
                </div>".$loader_and_vote_updated;
    }

    /**
     * Print the review title and the link to edit it
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param string comment_id
     * @param string $review_title
     * @param bool   $edit_link
     * @return string
     */
    private function printReviewTitle($comment_id, $review_title, $edit_link=true) {
        //return the link without the link to edit
        if($edit_link === false) {
            return '<span class="yasr-pro-rating-comment-title">
                        <strong>'
                            .esc_html($review_title).
                        '</strong>
                    </span>';
        }

        $title_name_id                    = 'yasr-pro-visitor-title-editable-'.$comment_id;
        $edit_title_link                  = 'yasr-pro-edit-visitor-title-'.$comment_id;
        $ajax_nonce_update_comment_title  = wp_create_nonce('yasr_pro_nonce_update_comment_title');

        $title = '<span class="yasr-pro-visitor-title-editable"
                        id="'.esc_attr($title_name_id).'"
                        data-commentId="'.esc_attr($comment_id).'"
                        data-nonce-title="'.esc_attr($ajax_nonce_update_comment_title).'"
                   />'
                    .esc_html($review_title).
                  '</span>';
        $link = '<a href="#" id="'.esc_attr($edit_title_link).'">'
                    . esc_html__('Edit Title', 'yasr-pro') .
                '</a>';

        return $title.$link;

    }

    /**
     * Print the text field and the links when the Review's title is edit
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param $comment_id
     * @param $review_title
     *
     * @return string
     */
    private function printEditReviewTitle($comment_id, $review_title) {
        $span_container_title = 'yasr-pro-hidden-form-visitor-title-span-'.$comment_id;
        $title_hidden_id      = 'yasr-pro-hidden-form-visitor-title-'.$comment_id;
        $span_container_links = 'yasr-pro-hidden-form-visitor-title-links-'.$comment_id;
        $update_link          = 'yasr-pro-update-visitor-title-'.$comment_id;
        $undo_link            = 'yasr-pro-undo-title-rating-comment-'.$comment_id;

        return '<span id="'.esc_attr($span_container_title).'" class="yasr-pro-hidden-form-visitor-title-span">
                    <input type="text"
                        value="'.esc_attr($review_title).'"
                        maxlength="100"
                        id="'.esc_attr($title_hidden_id).'"
                        class="yasr-pro-hidden-form-visitor-title"
                    />
                    <span id="'.esc_attr($span_container_links).'">
                        <a href="#" id="'.esc_attr($update_link).'">'
                            . esc_html__('Update', 'yasr-pro') .
                        '</a>
                        &nbsp;&nbsp;&nbsp;
                        <a href="#" id="'.esc_attr($undo_link).'">'
                            . esc_html__('Undo', 'yasr-pro') .
                        '</a>
                    </span>
              </span>';
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     *
     * @param int    $comment_id
     * @param int    $post_id
     * @param bool   $readonly
     * @param string $class //default is yasr-multiset-visitors-rater
     *                      //yasr-pro-update-review-multiset is used when an user can update the vote
     *
     * @return false|string
     */
    private function printMultisetTable($comment_id, $post_id, $readonly, $class='yasr-multiset-visitors-rater') {
        $set_id = yasr_pro_multiset_reviews_enabled($post_id);

        if($set_id === false) {
            return false;
        }

        $multiset_content = YasrDB::returnMultisetContent($post_id, $set_id, false, $comment_id);

        return $this->printMultiSet($multiset_content, $set_id, $post_id, $readonly, $class, $comment_id);
    }

    /*********************************** Saving function *******************************************/

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.9.5
     * @param $comment_id
     */
    public function saveRatings($comment_id) {
        $comment        = get_comment($comment_id);
        $post_id        = (int)$comment->comment_post_ID;
        $comment_parent = (int)$comment->comment_parent;

        if($this->ratingCanBeSaved($post_id, $comment_parent) === false) {
            return;
        }

        do_action('yasr_ur_save_custom_form_fields', $comment_id); //here we can hook new form fields

        //Save title
        $this->saveCommentmetaTitle($comment_id);

        //Save five star rating
        $this->saveCommentMetaRating($comment_id, $post_id);


        do_action('yasr_ur_do_content_after_save_commentmeta', $comment_id);
    }

    /**
     * Save meta title
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.5
     * @param int $comment_id
     */
    private function saveCommentmetaTitle ($comment_id) {
        if ((isset($_POST['yasr_pro_visitor_review_title'])) && ($_POST['yasr_pro_visitor_review_title'] !== '')) {
            $title = wp_filter_nohtml_kses($_POST['yasr_pro_visitor_review_title']);
            add_comment_meta($comment_id, 'yasr_pro_visitor_review_title', $title);
            return;
        }

        //if review title is mandatory and is not filled
        if (YASR_PRO_UR_RATING_MANDATORY === 'yes') {
            //delete the saved comment
            wp_delete_comment($comment_id, true);

            $error_message = __('Please insert a title for the review', 'yasr-pro');
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die($error_message);
        }

    }

    /**
     * Save Comment Meta rating
     *
     * @author Dario Curvino <@dudo>
     * @since 2.6.5
     * @param int $comment_id
     * @param int $post_id
     */
    private function saveCommentMetaRating ($comment_id, $post_id) {
        $five_star_comment_meta = false;
        $rating                 = false;

        //If isset and is numeric and is not negative insert the rating into db
        if ((isset($_POST['yasr_pro_visitor_review_rating']))
            && (is_numeric($_POST['yasr_pro_visitor_review_rating']))
            && ($_POST['yasr_pro_visitor_review_rating'] > 0)
            && ($_POST['yasr_pro_visitor_review_rating'] <= 5)
        ) {
            //when rating in comment form is saved, it can be only an int from 1 to 5
            $rating = (int)$_POST['yasr_pro_visitor_review_rating'];

            $five_star_comment_meta = add_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', $rating);
        }

        //if multiset is enabled, main five stars is mandatory
        $set_id              = yasr_pro_multiset_reviews_enabled($post_id);

        $five_star_mandatory = false;
        $multiset_enabled    = false;
        if($set_id !== false) {
            $five_star_mandatory = true;
            $multiset_enabled    = true;
        }


        //if rating is mandatory and is not filled
        if ($rating === false &&(YASR_PRO_UR_RATING_MANDATORY === 'yes' || $five_star_mandatory === true)) {
            $error_message= esc_html__('Please insert the rating for this review', 'yasr-pro');
            $this->commentReviewDie($comment_id, $error_message);
        }

        if($multiset_enabled === true) {
            $multiset_data_saved = $this->saveMultiSetData($comment_id, $post_id, $set_id);
            //Delete the comment if multiset data didn't get saved
            if($multiset_data_saved === false) {
                $error_message= esc_html__('Please fill all ratings.', 'yasr-pro');
                $this->commentReviewDie($comment_id, $error_message);
            } else {
                $this->setReviewCookie($post_id, $five_star_comment_meta);
                return;
            }
        } else {
            $this->setReviewCookie($post_id, $five_star_comment_meta);
        }

    }

    /**
     * Save multiset data
     *
     * @param int $comment_id
     * @param int $post_id
     * @param int $set_id
     * @since 2.9.7
     *
     * @return bool
     */
    private function saveMultiSetData ($comment_id, $post_id, $set_id) {
        if (isset($_POST['yasr_pro_multiset_review_rating'])) {
            if($_POST['yasr_pro_multiset_review_rating'] === '-1') {
                return false;
            }

            $rating = json_decode(stripslashes($_POST['yasr_pro_multiset_review_rating']), true);

            //be sure $rating is an array
            if (!is_array($rating)) {
                return false;
            }

            global $wpdb;

            //clean array, so if an user rate same field twice, take only the last rating
            $rating = yasr_unique_multidim_array($rating, 'field');

            //count the ratings given in the multiset, and if are less of the multisetlenght, return error
            if(count($rating) < YasrDB::multisetLength($set_id)) {
                $error_message= esc_html__('Please fill all ratings.', 'yasr-pro');
                $this->commentReviewDie($comment_id, $error_message);
            }

            foreach ($rating as $rating_values) {
                $id_field = (int) $rating_values['field'];
                $set_id   = (int) $rating_values['setid'];
                $rating   = (int) $rating_values['rating'];

                //insert comment_id here
                $wpdb->replace(
                    YASR_LOG_MULTI_SET,
                    array(
                        'field_id'   => $id_field,
                        'set_type'   => $set_id,
                        'post_id'    => $post_id,
                        'comment_id' => $comment_id,
                        'vote'       => $rating,
                        'user_id'    => get_current_user_id(),
                        'date'       => date('Y-m-d H:i:s'),
                        'ip'         => yasr_get_ip()
                    ),
                    array("%d", "%d", "%d", "%d", "%d", "%s", "%s", "%s")
                );
            } //End foreach ($rating as $rating_values)
            return true; //end if !== -1
        }
        return false;

    }

    /**
     * Set review cookie
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param int $post_id
     * @param int|bool $five_star_comment_meta  | it is an int if yasr_pro_visitor_review_rating comment meta has been saved
     */
    private function setReviewCookie($post_id, $five_star_comment_meta) {
        if(is_int($five_star_comment_meta)) {
            $cookie_name = 'yasr_rated_comment_' . $post_id;
            yasr_setcookie($cookie_name, $post_id);
            return; //everything is ok, return
        }
    }


    /**
     * Delete comment and metadata
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param int $comment_id
     * @param string $error_message
     */
    private function commentReviewDie($comment_id, $error_message) {
        //this will also delete metadata
        wp_delete_comment($comment_id, true);
        /** @noinspection ForgottenDebugOutputInspection */
        wp_die($error_message);
    }

    /**
     * Return true if comment meta data can be saved, false otherwise
     *
     * @author Dario Curvino <@dudo>
     * @since 2.6.5
     * @param int $post_id
     * @param int $comment_parent
     *
     * @return bool
     */
    private function ratingCanBeSaved ($post_id, $comment_parent) {
        $yasr_comment_rating_data_obj = new YasrCommentsRatingData();
        $comment_review_enabled       = (bool)$yasr_comment_rating_data_obj->commentReviewEnabled($post_id);

        //if reviews in comment are not enabled, return
        if ($comment_review_enabled === false) {
            return false;
        }

        //don't save metadata and don't do controls if this is an answer to a comment
        if ($comment_parent !== 0) {
            return false;
        }

        //if user is not logged and anonymous are not allowed, return
        if (YASR_PRO_UR_COMMENT_ALLOW_ANONYMOUS === 'no' && !is_user_logged_in()) {
            //DO NOT use wp_die here, or the setting will be required to be set even if user in reviews are not
            //set for a single post or page
            return false;
        }

        //check if user already rated, if so, just return;
        if (is_user_logged_in()) {
            $comments_array = $this->fiveStarsCommentQuery($post_id);

            //If array is not empty means that review for this post or page exists
            if (!empty($comments_array)) {
                return false;
            }
        }

        return true;
    }


    /*************************************** MISC ************************************************/

    /**
     * @author      Dario Curvino <@dudo>
     * @since       2.9.7
     *
     * @param array    $multiset_content
     * @param int      $set_id
     * @param int      $post_id
     * @param false    $readonly
     * @param string   $class    //default is yasr-multiset-visitors-rater
     *                           //yasr-pro-update-review-multiset is used when an user can update the vote
     * @param bool|int $comment_id
     *
     * @return string
     */
    public function printMultiSet($multiset_content, $set_id, $post_id, $readonly=false,
        $class = 'yasr-multiset-visitors-rater', $comment_id=false
    ) {
        YasrScriptsLoader::loadOVMultiJs();
        $nonce = wp_create_nonce('yasr_pro_nonce_update_comment_multiset');

        $html_multiset = '<table class="yasr_pro_multiset_comments">';

        foreach ($multiset_content as $set_content) {
            if(isset($set_content['average_rating'])) {
                $rating = (int)$set_content['average_rating'];
            } else {
                $rating = 0;
            }

            if($readonly === false) {
                $div_container           = 'yasr-pro-loader-update-multiset-rating-'.$comment_id.'-'.$set_content['id'];
                $loader_and_vote_updated = '<span id="'.esc_attr($div_container).'"></span>';
            } else {
                $loader_and_vote_updated = '';
            }

            $unique_id_identifier = 'yasr-pro-multiset-comment-' . str_shuffle(uniqid());

            //todo after data- remove 'rater'
            $html_stars = "<div class='".esc_attr($class)."'
                               id='$unique_id_identifier'
                               data-rater-postid='".esc_attr($post_id)."'
                               data-rater-setid='".esc_attr($set_id)."'
                               data-rater-set-field-id='".esc_attr($set_content['id'])."'
                               data-rating='".esc_attr($rating)."'
                               data-rater-starsize='".yasr_pro_comment_star_size()."'
                               data-rater-commentId='".esc_attr($comment_id)."'
                               data-yasr-nonce='".esc_attr($nonce)."'
                               data-rater-readonly='".json_encode($readonly)."'>
                           </div>";

            $html_multiset .= '<tr>
                                   <td>
                                       <div>
                                           <span class="yasr-multi-set-name-field">'.$set_content['name'].'</span>
                                       </div>
                                   </td>
                                   <td>'
                                      .$html_stars.$loader_and_vote_updated.
                                   '</td>
                                </tr>';

        } //End foreach
        $html_multiset .= '</table>';

        return $html_multiset;
    }


    /**
     * Add login link and register to the comment form is user is not logged in
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.5
     */
    public function loginRequired() {

        $login_url = wp_login_url(get_permalink());
        $reg_url   = wp_registration_url();

        $login_link = '<a href="'.$login_url.'">';
        $reg_link   = '<a href="'.$reg_url.'">';

        $string = sprintf(
            __('If you want to leave a review, please %s login %s or %s register %s first', 'yasr-pro'),
            $login_link, '</a>', $reg_link, '</a>'
        );

        echo wp_kses_post($string);

    }
}
