<?php
if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly


class YasrProReviewMultiset extends YasrMultiSet {

    /**
     * I need the constructor here, because for this shortcode the default setid value must not be YASR_FIRST_SETID,
     * but the one saved in the database, if exists
     *
     * @param $atts
     * @param $shortcode_name
     */
    public function __construct($atts, $shortcode_name) {
        parent::__construct($atts, $shortcode_name);

        //get the set id enabled for reviews,
        $set_id = yasr_pro_multiset_reviews_enabled($this->post_id);

        //if exists, overwrite the default shortcode attribute set_id
        if($set_id !== false) {
            $atts = shortcode_atts(
                array(
                    'setid' => yasr_pro_multiset_reviews_enabled($this->post_id),
                ),
                $atts,
                $shortcode_name
            );

            $this->set_id = $atts['setid'];
        }
    }

    /**
     * Return the shortcode yasr_pro_average_comments_multiset
     *
     * @author Dario Curvino <@dudo>
     * @since  2.9.8
     * @return string
     */
    public function returnReviewMultiset () {
        $shortcode_html = '<!-- Yasr Multi Set Average Reviews-->';

        $multiset_content = (new YasrCommentsRatingData())->getCommentMultisetRatings($this->post_id, $this->set_id);
        if ($multiset_content === false) {
            return $this->returnErrorData($shortcode_html);
        }
        $this->star_readonly = 'true';

        $shortcode_html  = '<table class="yasr_table_multi_set_shortcode yasr_pro_average_review_multi">';

        $shortcode_html .= $this->returnMultisetRows($multiset_content);
        $shortcode_html .= $this->returnAverageRowIfEnabled(false, $multiset_content);

        $shortcode_html .= '</table>';
        $shortcode_html .= '<!-- End Shortcode -->';

        YasrScriptsLoader::loadOVMultiJs();

        return $shortcode_html;
    }

}