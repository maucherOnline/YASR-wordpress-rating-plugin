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

class YasrProUrAdmin {

    public function init() {
        ///// While editing post or page
        // Function to draw the "Enable review in comments" in the metabox
        add_action('yasr_add_content_bottom_topright_metabox', array($this, 'urMetaboxClassicEditor'));

        //Add content in the metabox below the editor, multiset tab
        add_action('yasr_add_content_multiset_tab_pro',        array($this, 'urMetaboxMultiset'), 10, 2);

        //When post is saved, check if enable or disable reviews in comments
        add_action('yasr_on_save_post',                        array($this, 'urSavePostMeta'));
        //When post is save, check if enable or disable multiset in comments
        add_action('yasr_on_save_post',                        array($this, 'urMultiSetSavePostMeta'));
        //Adds pro tab on tinymce popup
        add_action('yasr_add_tabs_on_tinypopupform',           array($this, 'urTinypopupTabs'), 30);
        //Function to draw the content of the pro tinypopup content
        add_action('yasr_add_content_on_tinypopupform',        array($this, 'urTinypopupContent'));

        /////Settings
        //Simply add the tabs on settings page
        add_action('yasr_add_settings_tab',                    array($this, 'urSettingsTab'));
        //Add new page
        add_action('yasr_settings_tab_content',                array($this, 'urSettingsPage'));
        //Hook the "Select Ranking" in the select inside "Settings -> Rankings"
        add_filter('yasr_settings_select_ranking',             array($this, 'urAddRankingOnSelect'));

        //Comments Dashboard
        add_filter('comment_text',                             array($this, 'displayReviewsCommentDashboard'), 999);
        add_action('deleted_comment',                          array($this, 'deleteReviewsCommentMeta'));

        /////Ajax action, must be here even if works only in front end
        //Used in comment form when updating the rating
        add_action('wp_ajax_yasr_pro_update_comment_rating',  array($this, 'urUpdateReviewRating'));

        add_action('wp_ajax_yasr_pro_update_comment_multiset_rating', array($this, 'urUpdateMultisetRating'));

        //Used in comment form when updating the title
        add_action('wp_ajax_yasr_pro_update_comment_title',  array($this, 'urUpdateReviewTitle'));
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $post_id
     */
    public function urMetaboxClassicEditor($post_id) {
        wp_nonce_field( 'yasr_nonce_comment_review_enabled_action', 'yasr_nonce_comment_review_enabled');

        $yasr_comment_rating_data_obj = new YasrCommentsRatingData();
        $comment_review_enabled = (bool)$yasr_comment_rating_data_obj->commentReviewEnabled();
        ?>
        <hr />
        <p>
            <?php
            if ($comment_review_enabled === true ) {
                esc_html_e("Reviews in comments for this post / page are ENABLED", 'yasr-pro');
            } else {
                esc_html_e("Reviews in comments for this post / page are DISABLED", 'yasr-pro');
            }
            ?>
        </p>

        <div id="yasr-toprightmetabox-reviews-in-comments-switcher">
            <div class="yasr-onoffswitch-big" id="yasr-switcher-enable-reviews-in-comments">
                <input type="checkbox" name="yasr_pro_review_in_comments" class="yasr-onoffswitch-checkbox" value="yes"
                       id="yasr-pro-comments-enabled-yes" <?php if ($comment_review_enabled === true) {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-pro-comments-enabled-yes">
                    <span class="yasr-onoffswitch-inner yasr-onoffswitch-onoff-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <br/>
        <div id="yasr-pro-reviews-comments-enabled-message">
        </div>
        <?php

    }

    /**
     * Add hidden field
     * value attribute is the value that will be saved
     * data-enabled-multi is the set saved for the current page. This value is not updated in js, only read
     *
     * @param $post_id
     * @param $first_set_id
     * @author Dario Curvino <@dudo>
     * @since 3.0.5
     */
    public function urMetaboxMultiset($post_id, $first_set_id) {
        //this value must not be overwritten
        $enabled_multiset = yasr_pro_multiset_reviews_enabled($post_id);

        //default value is the set enabled for comments
        $value_to_save    = $enabled_multiset;

        //and if there is no set enabled, set value to the first set id
        if($value_to_save === false) {
            $value_to_save = $first_set_id;
        }
        ?>

        <?php
            /**
             * This field contains the enabled multiset if for the post if exists, or the first set id if doesn't
             */
        ?>
        <input type="hidden" name="yasr_pro_review_setid" id="yasr-pro-review-setid"
               value="<?php echo esc_attr($value_to_save) ?>">

        <?php
            /**
            * This field contains the enabled multiset if for the post, or empty if doens't
            */
        ?>
        <input type="hidden" name="yasr_pro_review_setid_postmeta_value" id="yasr-pro-review-setid-postmeta-value"
               value="<?php echo esc_attr($enabled_multiset) ?>">
        <?php
    }


    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $post_id
     */
    public function urSavePostMeta($post_id) {
        //this mean there we're not in the classic editor
        if (!isset($_POST['yasr_nonce_comment_review_enabled'])) {
            return;
        }

        $nonce = $_POST['yasr_nonce_comment_review_enabled'];

        if (!wp_verify_nonce($nonce, 'yasr_nonce_comment_review_enabled_action')) {
            return;
        }

        if (isset($_POST['yasr_pro_review_in_comments'])) {
            $post_data = 1;
        } else {
            $post_data = 0;
        }

        //if urDeletePostMeta mean that post meta can be saved
        if($this->urDeletePostMeta($post_id, (bool)$post_data) === false) {

            //save the post meta
            $this->urUpdatePostMeta($post_id, $post_data);
        }

    }

    /**
     * Save yasr_pro_review_setid post meta
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param $post_id
     */
    public function urMultiSetSavePostMeta($post_id) {

        if (!isset($_POST['yasr_nonce_multiset_review_enabled'])) {
            return;
        }

        $nonce = $_POST['yasr_nonce_multiset_review_enabled'];

        if (!wp_verify_nonce($nonce, 'yasr_nonce_multiset_review_enabled_action')) {
            return;
        }

        $checkbox_enabled            = false;
        $set_id                      = false;
        $set_id_post_meta_value      = false;
        $save_with_checkbox_disabled = false;

        if (isset($_POST['yasr_pro_multiset_review_enabled'])) {
            $checkbox_enabled = $_POST['yasr_pro_multiset_review_enabled'];
        }

        if (isset($_POST['yasr_pro_review_setid'])) {
            $set_id = $_POST['yasr_pro_review_setid'];
        }

        if (isset($_POST['yasr_pro_review_setid_postmeta_value'])) {
            $set_id_post_meta_value = $_POST['yasr_pro_review_setid_postmeta_value'];
        }

        //IF more than one multiset are used, and if for example the second set is selected, on page load the checkbox is disabled
        // (first multiset is used, not the first). So if the $set_id_post_meta_value is the same of the set_id, save it
        //even if checkbox is not selected
        //keep is_numeric and not use is_int
        //keep == instead of ===
        if(is_numeric($set_id) && is_numeric($set_id_post_meta_value) && $set_id == $set_id_post_meta_value) {
            $save_with_checkbox_disabled = true;
        }

        if ( $checkbox_enabled !== false && $set_id !== false
            || $save_with_checkbox_disabled === true) {
            $set_id    = (int)$_POST['yasr_pro_review_setid'];

            //set_id column in multi set table is auto increment, so the first set_id is always 1.
            //if it is < 1, return
            if($set_id < 1) {
                return;
            }

            //insert post meta
            update_post_meta($post_id, 'yasr_pro_review_setid', $set_id);

            //When the multi set in comment is enabled, yasr_pro_reviews_in_comment_enabled must be saved too
            //this is only necessary in gutenberg, where isset($_POST['yasr_pro_review_in_comments']) is false
            if (!isset($_POST['yasr_pro_review_in_comments'])) {
                $this->urUpdatePostMeta($post_id, 1);
            }
        } else {
            delete_post_meta($post_id, 'yasr_pro_review_setid');
        }

    }

    /**
     * Check Auto insert setting and delete post meta if not needed
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param int $post_id
     * @param bool $post_data  true if yasr_pro_review_in_comments is set to YES, false otherwise
     *
     * @return bool
     */
    private function urDeletePostMeta($post_id, $post_data) {
        //If by default, user reviews in comment is no, and post data is no, do not save/delete useless data
        if(YASR_PRO_UR_COMMENT_AUTO_INSERT === 'no' && $post_data === false) {
            delete_post_meta($post_id, 'yasr_pro_reviews_in_comment_enabled');
            return true;
        }

        //same but if everything is enabled
        if(YASR_PRO_UR_COMMENT_AUTO_INSERT === 'yes' && $post_data === true) {
            delete_post_meta($post_id, 'yasr_pro_reviews_in_comment_enabled');
            return true;
        }

        return false;
    }


    /**
     * Save post meta yasr_pro_reviews_in_comment_enabled
     *
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param int $post_id
     * @param int $post_data  1 or 0
     */
    private function urUpdatePostMeta ($post_id, $post_data) {
        //insert post meta
        update_post_meta($post_id, 'yasr_pro_reviews_in_comment_enabled', $post_data);
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     **/
    public function urTinypopupTabs() {
        ?>
        <a href="#" id="yasr-pro-link-tab-comments"
           class="nav-tab yasr-nav-tab"><?php esc_html_e("User Reviews", 'yasr-pro'); ?></a>
        <?php
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     */
    public function urTinypopupContent() {
        ?>
        <div id="yasr-pro-content-comments" class="yasr-content-tab-tinymce" style="display:none">
            <table id="yasr-table-tiny-popup-comments" class="form-table">
                <tr>
                    <th>
                        <label for="yasr-pro-rating-stats-progressbars">
                            <?php esc_html_e("Insert Progress Bars", "yasr-pro"); ?>
                        </label>
                    </th>
                    <td><input type="button" class="button-primary"
                               name="yasr-pro-rating-stats-progressbars"
                               id="yasr-pro-rating-stats-progressbars"
                               value="<?php esc_attr_e("Insert Progress Bars stats", "yasr-pro") ?>"
                        />
                        <br/>
                        <small>
                            <?php esc_html_e("Insert progress bars statistics for review in comments", "yasr-pro"); ?>
                        </small>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-pro-rating-stats-average">
                            <?php esc_html_e("Insert Average Rating", "yasr-pro"); ?>
                        </label>
                    </th>
                    <td>
                        <input type="button"
                               class="button-primary"
                               name="yasr-pro-rating-stats-average"
                               id="yasr-pro-rating-stats-average"
                               value="<?php esc_attr_e("Insert Stars Average", "yasr-pro") ?>"
                        /><br/>
                        <small>
                            <?php esc_html_e("Insert the average (in stars) of all ratings in comments", "yasr-pro"); ?>
                        </small>

                        <div id="yasr-pro-tinymce-choose-size-comments-stars">
                            <small>
                                <?php esc_html_e("Choose Size", 'yet-another-stars-rating'); ?>
                            </small>
                            <div class="yasr-tinymce-button-size">
                                <?php
                                    echo (new YasrEditorHooks)->tinyMceButtonCreator('yasr_pro_average_comments_ratings');
                                ?>
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-pro-rating-stats-progressbars">
                            <?php esc_html_e("Ranking from reviews", "yasr-pro"); ?>
                        </label>
                    </th>
                    <td>
                        <input type="button"
                               class="button-primary"
                               name="yasr-pro-rankings-from-review"
                               id="yasr-pro-rankings-from-review"
                               value="<?php esc_attr_e("Insert Ranking From Reviews", "yasr-pro") ?>"/>
                        <br/>
                        <small>
                            <?php esc_html_e("Show up a ranking build from the reviews", "yasr-pro"); ?>
                        </small>
                    </td>
                </tr>

            </table>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {

                //Tinymce
                jQuery('#yasr-pro-link-tab-comments').on("click", function () {
                    jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
                    jQuery('#yasr-pro-link-tab-comments').addClass('nav-tab-active');

                    jQuery('.yasr-content-tab-tinymce').hide();
                    jQuery('#yasr-pro-content-comments').show();
                });

                //Add shortcode for comments review statistics. This is pro only
                //This is for the progressbars
                jQuery('#yasr-pro-rating-stats-progressbars').on("click", function () {
                    var shortcode = '[yasr_pro_comments_ratings_progressbars]';

                    if (tinyMCE.activeEditor == null) {
                        //this is for tinymce used in text mode
                        jQuery("#content").append(shortcode);
                    } else {
                        // inserts the shortcode into the active editor
                        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
                    }

                    // closes thickbox
                    tb_remove();
                });

                //And this is for the average
                jQuery('#yasr-pro-rating-stats-average').on("click", function () {
                    jQuery('#yasr-pro-tinymce-choose-size-comments-stars').toggle('slow');
                });

                //Tab this cause is inside a div

                //Add shortcode for comments review statistics. This is pro only
                //This is for the progressbars
                jQuery('#yasr-pro-rankings-from-review').on("click", function () {
                    var shortcode = '[yasr_pro_ur_ranking]';

                    if (tinyMCE.activeEditor == null) {
                        //this is for tinymce used in text mode
                        jQuery("#content").append(shortcode);
                    } else {
                        // inserts the shortcode into the active editor
                        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
                    }

                    // closes thickbox
                    tb_remove();
                });

            });
        </script>

        <?php

    }

    /**
     * Callback for yasr_add_settings_tab, add the "User Reviews tab"
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $active_tab
     */
    public function urSettingsTab($active_tab) {
        ?>
        <a href="?page=yasr_settings_page&tab=ur_general_options"
           id="ur_general_options"
           class="nav-tab <?php if ($active_tab === 'ur_general_options') {
               echo 'nav-tab-active';
           } ?>">
            <?php
                esc_html_e("User Reviews", 'yasr-pro');
                echo YASR_LOCKED_FEATURE;
            ?>
        </a>

        <?php

    }


    /**
     * Callback for yasr_settings_tab_content, add page content
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $active_tab
     */
    public function urSettingsPage($active_tab) {
        if ($active_tab === 'ur_general_options') {
            ?>
            <div class="yasr-settings-table">
                <form action="options.php" method="post" id="yasr_settings_form">
                    <?php
                    settings_fields('yasr_ur_general_options_group');
                    do_settings_sections('yasr_ur_general_options_tab');
                    submit_button(YASR_SAVE_All_SETTINGS_TEXT);
                    ?>
                </form>
            </div>
            <?php
        } //End tab ur options
    }


    /**
     * Hook into yasr_settings_select_ranking and add ranking used by YASR UR
     *
     * @author Dario Curvino <@dudo>
     * @since  2.7.1
     * @param  $select_array
     * @return array
     */
    public function urAddRankingOnSelect($select_array) {
        $select_array[] = 'yasr_pro_ur_ranking';
        return $select_array;
    }


    /**
     * Shows the stars and the titkle in wp-admin/edit-comments.php
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refatcored as method
     *
     * @param $html
     *
     * @return mixed|string
     */
    public function displayReviewsCommentDashboard($html) {

        $comment_id = get_comment_ID();
        $review_title = esc_attr(get_comment_meta( $comment_id, 'yasr_pro_visitor_review_title', true ));
        $rating = get_comment_meta( $comment_id, 'yasr_pro_visitor_review_rating', true );
        $review_body = get_comment_text( $comment_id );

        //generate an unique id to be sure that every element has a different ID
        $comment_rating_html_id  = yasr_return_dom_id('yasr-pro-visitor-review-rater-');

        if ($rating) {

            $rating = '<div class="yasr-rater-star-comment"
                        id="'.$comment_rating_html_id.'"
                        data-rating="'.$rating.'">
                   </div>';

            $review_title_span = "<span class=\"yasr-pro-rating-comment-title\"><strong>$review_title</strong></span>";

            $html = $rating . $review_title_span . '<p>' . $review_body;
        }

        return $html;

    }


    /**
     * Delete YASR comment meta when comment is deleted
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $comment_id
     */
    public function deleteReviewsCommentMeta($comment_id) {
        delete_comment_meta($comment_id, 'yasr_pro_visitor_review_title');
        delete_comment_meta($comment_id, 'yasr_pro_visitor_review_rating');

        //delete multiset data, since 2.9.7
        global $wpdb;

        return $wpdb->delete(
            YASR_LOG_MULTI_SET,
            array(
                'comment_id' => $comment_id,
            ),
            array('%d')
        );
    }


    /**
     * Callback for wp_ajax_yasr_pro_update_comment_rating
     *
     * @author Dario Curvino <@dudo>
     */
    public function urUpdateReviewRating() {
        if (isset($_POST['rating']) && isset($_POST['commentId']) && isset($_POST['nonce']) && is_user_logged_in()) {
            $rating     = $_POST['rating'];
            $comment_id = $_POST['commentId'];
            $nonce      = $_POST['nonce'];
        } else {
            die(esc_html__('Wrong data', 'yasr-pro'));
        }

        if(self::currentUserCanUpdateReview($comment_id) === false) {
            die(esc_html__('You can\'t edit someone else review', 'yasr-pro'));
        }

        $error_name_nonce = esc_html__('Wrong nonce. Title can\'t be updated.', 'yasr-pro');
        $valid_nonce = YasrShortcodesAjax::validNonce($nonce, 'yasr_pro_nonce_update_comment_rating', $error_name_nonce);
        if($valid_nonce !== true) {
            die ($valid_nonce);
        }

        $array_to_return = array();

        $result = update_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', $rating);

        if ($result) {
            $string = esc_html__( 'New rating: ', 'yasr-pro' ). $rating;
            $array_to_return['status'] = 'success';
            $array_to_return['text']   = $string;
        } else {
            $array_to_return['status'] = 'error';
            $array_to_return['text']   = esc_html__( 'Something goes wrong', 'yasr-pro' );
        }

        echo json_encode($array_to_return);

        die();
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.9.7
     */
    public function urUpdateMultisetRating() {
        if (isset($_POST['rating']) && isset($_POST['comment_id']) && isset($_POST['field_id']) && isset($_POST['nonce'])
            && is_user_logged_in()
        ) {
            $rating     = (int)$_POST['rating'];
            $comment_id = (int)$_POST['comment_id'];
            $field_id   = (int)$_POST['field_id'];
            $nonce      = $_POST['nonce'];
        } else {
            die(esc_html__('Wrong data', 'yasr-pro'));
        }

        if(self::currentUserCanUpdateReview($comment_id) === false) {
            die(esc_html__('You can\'t edit someone else review', 'yasr-pro'));
        }

        $error_name_nonce = esc_html__('Wrong nonce. Rating can\'t be updated.', 'yasr-pro');
        $valid_nonce      = YasrShortcodesAjax::validNonce($nonce, 'yasr_pro_nonce_update_comment_multiset', $error_name_nonce);
        if($valid_nonce !== true) {
            die ($valid_nonce);
        }

        global $wpdb;
        //no need to insert 'comment_id', it is 0 by default
        $success = $wpdb->update(
            YASR_LOG_MULTI_SET,
            array(
                'field_id' => $field_id,
                'vote'     => $rating,
            ),
            array(
                'field_id'   => $field_id,
                'comment_id' => $comment_id,
            ),
            array('%d', '%d'),
            array('%d', '%d')
        );

        if ($success) {
            $string = esc_html__( 'New rating: ', 'yasr-pro' ). $rating;
            $array_to_return['status'] = 'success';
            $array_to_return['text']   = $string;
        } else {
            $array_to_return['status'] = 'error';
            $array_to_return['text']   = esc_html__( 'Something goes wrong', 'yasr-pro' );
        }

        echo json_encode($array_to_return);

        die();

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since 2.9.7
     * @param  int $comment_id
     * @return bool
     */
    public static function currentUserCanUpdateReview($comment_id) {
        $author     = (int)get_comment($comment_id)->user_id; //get the user id
        if($author !== get_current_user_id()) {
            return false;
        }

        return true;
    }

    /**
     * Callback for wp_ajax_yasr_pro_update_comment_title
     *
     * @author Dario Curvino <@dudo>
     */
    public function urUpdateReviewTitle() {
        if (isset($_POST['title']) && isset($_POST['commentId']) && isset($_POST['nonce'])) {
            $title      = $_POST['title'];
            $comment_id = $_POST['commentId'];
            $nonce      = $_POST['nonce'];
        } else {
            exit();
        }

        $error_name_nonce = __('Wrong nonce. Title can\'t be updated.', 'yasr-pro');

        $valid_nonce = YasrShortcodesAjax::validNonce($nonce, 'yasr_pro_nonce_update_comment_title', $error_name_nonce);
        if($valid_nonce !== true) {
            die ($valid_nonce);
        }

        $array_to_return = array ();

        //Title must be 2 chars
        if (mb_strlen($title) < 2) {
            $text_to_return = __('Title must be at least 2 chars', 'yasr-pro');
            $array_to_return['status'] = 'error';
            $array_to_return['text']  = $text_to_return;
        }

        else if (mb_strlen($title) > 100) {
            $text_to_return = __('Title must be shorter than 100 chars', 'yasr-pro');
            $array_to_return['status'] = 'error';
            $array_to_return['text']   = $text_to_return;
        }

        else {
            $result = update_comment_meta($comment_id, 'yasr_pro_visitor_review_title', $title);

            if ($result) {
                $text_to_return = "<strong>$title</strong> &nbsp;&nbsp;&nbsp;";
                $text_to_return .= __('Title updated', 'yasr-pro');
                $array_to_return['status'] = 'success';
            }
            else {
                $text_to_return = __('Something goes wrong', 'yasr-pro');
                $array_to_return['status'] = 'error';
            }
            $array_to_return['text']  = $text_to_return;
        }

        echo json_encode($array_to_return);

        die();

    }
}
