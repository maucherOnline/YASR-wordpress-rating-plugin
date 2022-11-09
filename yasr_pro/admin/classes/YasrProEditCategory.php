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


class YasrProEditCategory {
    public static function init() {
        //hook function on save
        add_action('edited_category', array('YasrProEditCategory', 'updatePostAndTermMeta'), 10, 2);
    }

    /**
     * Hook when a category is updated
     *
     * https://developer.wordpress.org/reference/hooks/edited_taxonomy/
     * In this case, $term_id is category_id
     *
     * @param  int $term_id
     * @return void;
     *
     **/
    public static function updatePostAndTermMeta($term_id) {

        //If checkbox is not selected, return
        if(!isset($_POST['yasr-pro-checkbox-itemtype-category'])) {
            return;
        }

        $supported_itemTypes = YASR_SUPPORTED_SCHEMA_TYPES;
        $selected_itemType = $_POST['yasr-review-type'];

        //if the value of yasr-review-type is not in $supported_itemTypes, return
        if (!in_array( $selected_itemType, $supported_itemTypes, true ) ) {
            return;
        }

        //Select argument to retrive post
        $args = array(
            'numberposts' => -1, // -1 returns all posts
            'category'    => $term_id, //$term_id is the category id
            'fields'      => 'ids', // only get post IDs.
        );

        $array_posts_id = get_posts($args);

        foreach ($array_posts_id as $post_id) {
            update_post_meta($post_id, 'yasr_review_type', $selected_itemType);
        }

        update_term_meta($term_id, 'yasr_review_type', $selected_itemType);

    }

}
