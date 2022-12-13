<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/****** Delete data value from yasr tabs when a post or page is deleted
 * Added since yasr 0.3.3
 ******/
add_action('admin_init', 'admin_init_delete_data_on_post_callback');

function admin_init_delete_data_on_post_callback() {

    if (current_user_can('delete_posts')) {
        add_action('delete_post', 'yasr_erase_data_on_post_page_remove_callback');
    }

}

function yasr_erase_data_on_post_page_remove_callback($post_id) {
    global $wpdb;

    delete_metadata('post', $post_id, 'yasr_overall_rating');
    delete_metadata('post', $post_id, 'yasr_review_type');
    delete_metadata('post', $post_id, 'yasr_multiset_author_votes');

    //Delete multi value
    $wpdb->delete(
        YASR_LOG_MULTI_SET,
        array(
            'post_id' => $post_id
        ),
        array(
            '%d'
        )
    );

    $wpdb->delete(
        YASR_LOG_TABLE,
        array(
            'post_id' => $post_id
        ),
        array(
            '%d'
        )
    );


}
