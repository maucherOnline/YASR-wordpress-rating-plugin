<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProAverageMultiset extends YasrMultiSet {
    /**
     * Return the average of both multiset
     *
     * @return false|float|int
     */
    public function returnAverageMultiset () {
        $visitor_multiset = false;

        if($this->shortcode_name === 'yasr_pro_average_visitor_multiset') {
            $visitor_multiset = true;
        }

        return YasrDB::returnMultiSetAverage($this->post_id, $this->set_id, $visitor_multiset);
    }

    /**
     * Return the html for the stars
     *
     * @return string
     */
    public function printAverageMultiset () {
        $average_multiset = $this->returnAverageMultiset();
        $stars_size = $this->starSize();

        $this->shortcode_html = '<!--YASR PRO' . $this->shortcode_name .' -->';

        //generate an unique id to be sure that every element has a different ID
        $average_multiset_html_id = yasr_return_dom_id($this->shortcode_name . '-');

        $html_stars = "<div class='yasr-overall-rating'>
                                 <div class='yasr-rater-stars'
                                     id='$average_multiset_html_id'
                                     data-rating='$average_multiset'
                                     data-rater-starsize='$stars_size' >
                                 </div>
                             </div>";

        $this->shortcode_html .= $html_stars;
        $this->shortcode_html .= '<!-- END YASR PRO' . $this->shortcode_name .' -->';

        return $this->shortcode_html;
    }

}