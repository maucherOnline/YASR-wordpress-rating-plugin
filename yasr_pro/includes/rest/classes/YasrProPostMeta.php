<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProPostMeta extends YasrPostMeta {

    /**
     * This add yasr pro postMeta values
     **
     * Get Yasr Post Meta Values and insert in the rest response
     * YOURSITE.COM/wp-json/wp/v2/posts?_field=meta
     * or
     * YOURSITE.COM/wp-json/wp/v2/posts/<POSTID>?_field=meta
     *
     */
    public function registerPostMeta () {
        //'post' here works also for CPT

        register_meta(
            'post',
            'yasr_pro_reviews_in_comment_enabled',
            array(
                'show_in_rest' => true,
                'single'       => true,
                'type'         => 'number',
                'auth_callback' => static function() {
                    return current_user_can('edit_posts');
                }
            )
        );
    }

}