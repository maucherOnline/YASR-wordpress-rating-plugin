<?php
if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/**
 * Class YasrProCustomFields
 */
class YasrProCustomFields extends YasrCustomFields {

    /**
     * This function returns true or false if reviews in comments are enabled for a post or page
     * Only in edit screen
     *
     * YOURSITE.COM/wp-json/wp/v2/posts?_fields=yasr_pro_comment_review_enabled
     * or
     * YOURSITE.COM/wp-json/wp/v2/posts/<POSTID>?_fields=yasr_pro_comment_review_enabled
     *
     */
    public function returnCommentReviewsEnabledForPost() {
        $post_types = YasrCustomPostTypes::returnAllPostTypes();
        $schema = array(
            'type'    => 'boolean',
            'context' =>  array('edit')
        );

        //Register Visitor Votes
        register_rest_field(
            $post_types,
            'yasr_pro_comment_review_enabled',
            array(
                'get_callback' => array($this, 'getCommentReviewsEnabledForPost'),
                'schema'  => $schema
            )
        );
    }

    /* Can't be private
     *
     * This can also works in anonymous function, like this
     * array(
     *     'get_callback' => function () {
     *          $yasr_comment_rating_data_obj = new YasrCommentsRatingsData();
     *          return (bool)$yasr_comment_rating_data_obj->commentReviewEnabled();
     *      }
     */

    protected function getCommentReviewsEnabledForPost() {
        $yasr_comment_rating_data_obj = new YasrCommentsRatingData();
        return (bool)$yasr_comment_rating_data_obj->commentReviewEnabled();
    }

}