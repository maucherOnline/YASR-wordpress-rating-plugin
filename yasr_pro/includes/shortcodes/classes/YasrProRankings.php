<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProRankings extends YasrRankings {

    public $rows                      = 10;
    public $text_position             = 'after';
    public $text                      = '';
    public $view                      = 'most';
    public $min_votes_most_rated      = '1';
    public $min_votes_highest_rated   = '2';
    public $start_date                = false;
    public $end_date                  = false;
    public $category                  = false;
    public $custom_post               = false;
    public $display                   = 'login';

    /**
     * YasrCRShortcodes constructor.
     *
     * @param $atts
     * @param $shortcode_name
     */
    public function __construct($atts, $shortcode_name) {
        parent::__construct($atts, $shortcode_name);

        if ($atts !== false) {
            $atts = (shortcode_atts(
                array(
                    'rows'          => 10,
                    'size'          => 'medium',
                    'text_position' => 'after',
                    'text'          => '',
                    'view'          => 'most',
                    'minvotesmost'  => '1',
                    'minvoteshg'    => '1',
                    'start_date'    => false,
                    'end_date'      => false,
                    'category'      => false,
                    'custom_post'   => false,
                    'display'       => 'login'
                ),
                $atts,
                $shortcode_name
            ));

            $this->rows                    = $this->setLimit($atts['rows']);
            $this->size                    = sanitize_text_field($atts['size']);
            $this->text_position           = $atts['text_position'];
            $this->text                    = sanitize_text_field($atts['text']);
            $this->view                    = $atts['view'];
            $this->min_votes_most_rated    = $this->setLimit($atts['minvotesmost'], 1);
            $this->min_votes_highest_rated = $this->setLimit($atts['minvoteshg']);
            $this->start_date              = $this->checkDate(sanitize_text_field($atts['start_date']));
            $this->end_date                = $this->checkDate(sanitize_text_field($atts['end_date']));
            $this->category                = $this->cleanCategory(json_encode($atts['category']));
            $this->custom_post             = $this->cleanCpt($atts['custom_post']);
            $this->display                 = $atts['display'];

            //default must be most
            if ($this->text_position !== 'after') {
                $this->text_position = 'before';
            }

            //default must be most
            if ($this->view !== 'highest') {
                $this->view = 'most';
            }

            if ($this->display !== 'displayname') {
                $this->display = 'login';
            }

        }

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.3
     *
     * @param $atts_category | must be json_encoded
     *
     * @return false|null
     */
    public function cleanCategory($atts_category) {
        $categories = json_decode($atts_category);

        if ($categories) {
            //if is array, cast all array members to int
            if (is_array($categories)) {
                $category = implode(",", $categories);
            }
            else {
                $category = $categories;
            }

            $category = trim($category);
            //This can be a single int value or an array of ints values
            return rtrim($category, ',');
        }

        return false;

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.3
     *
     * @param $cpt
     *
     * @return false|string
     */
    public function cleanCpt($cpt) {
        $cpt = sanitize_text_field($cpt);
        //if custom post type exists, return it, otherwise still false (will show all results)
        if (post_type_exists($cpt)) {
            return $cpt;
        }

        return false;
    }

    /**
     * This function get the shortcode (or rest) params and return an array with values that will be used in the query
     *
     * If data comes from shortcode, the data is cleaned in the constructor.
     * If data comes by rest, the data is cleaned with function yasr_pro_rest_sanitize
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.2
     *
     * @param array  $atts contains shortcode or rest request params
     * @param string $ranking
     *
     * @return array
     */
    public static function setQueryAttributes($atts, $ranking='most') {

        global $wpdb;
        //default value
        $array_to_return = array(
            'order_by'       => 'DESC',
            'limit'          => 10,
            'ctg'            => false,
            'cpt'            => false,
            'required_votes' => false,
            'display'        => 'login',
            //these params are not from the atts
            'from_clause'    => '',
            'and_clause'     => '',
            'date'           => '',
            'limit_clause'   => ' LIMIT 10',
            'orderby_clause' => '',
            'having_clause'  => '',
        );

        if (isset($atts['order_by'])) {
            if ($atts['order_by'] === 'asc' || $atts['order_by'] === 'ASC') {
                $array_to_return['order_by'] = 'asc';
            }
        }

        if ($ranking === 'highest') {
            $array_to_return['orderby_clause'] = ' ORDER BY rating '.$array_to_return['order_by'].', number_of_votes DESC ';
        } else {
            $array_to_return['orderby_clause'] = ' ORDER BY number_of_votes  '.$array_to_return['order_by'].', rating DESC';
        }

        if (isset($atts['limit'])) {
            $array_to_return['limit'] = $atts['limit'];
            $array_to_return['limit_clause'] = ' LIMIT ' . $array_to_return['limit'];
        }

        if(isset($atts['start_date']) && $atts['start_date'] !== false) {
            $array_to_return['date'] = ' AND p.post_date > \'' . $atts['start_date'] . '\'';
        }

        if(isset($atts['end_date']) && $atts['end_date'] !== false) {
            $array_to_return['date'] .= ' AND p.post_date <= \'' . $atts['end_date'] . '\'';
        }

        if (isset($atts['ctg'])) {
            //This can be a single int value or an array of ints values
            $array_to_return['ctg'] = $atts['ctg'];
            $array_to_return['from_clause'] = ", $wpdb->term_relationships AS t ";
            $array_to_return['and_clause']  = " AND t.term_taxonomy_id IN ($array_to_return[ctg])";
            $array_to_return['and_clause'] .= ' AND t.object_id = p.ID';
        }

        if (isset($atts['cpt'])) {
            $array_to_return['cpt']         = $atts['cpt'];
            $array_to_return['and_clause']  = " AND p.post_type = '$array_to_return[cpt]'";
        }

        if (isset($atts['required_votes'])) {
            //this mean that the request is coming from rest (&required_votes=XXX)
            if(is_numeric($atts['required_votes'])) {
                $required_votes = (int) $atts['required_votes'];
            }

            else if(is_array($atts['required_votes'])) {
                if ($ranking === 'highest') {
                    $required_votes = (int) $atts['required_votes']['highest'];
                }
                else {
                    $required_votes = (int) $atts['required_votes']['most'];
                }
            } else {
                $required_votes = 1;
            }
        } else {
            $required_votes = 1;
        }

        $array_to_return['having_clause']  = ' HAVING number_of_votes >= ' . $required_votes;

        if(isset($atts['display'])) {
            $array_to_return['display'] = $atts['display'];
        }

        return $array_to_return;

    }

    /**
     * Check if the given string is a date in format Y/m/d and return it, false otherwise
     *
     * @author Dario Curvino <@dudo>
     * @since  2.7.8
     * @param  $date string
     *
     * @return bool|string
     */
    public function checkDate ($date) {
        $time_stamp = strtotime($date);
        //check if the time stamp is not false
        if ($time_stamp !== false) {
            //date return false is the date is not in the right format, or the string with the date otherwise
            return date('Y-m-d', $time_stamp);
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.3
     *
     * @param     $value
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public function setLimit ($value, $min=10, $max=99) {
        $value = (int)$value;
        $min   = (int)$min;
        $max   = (int)$max;

        if ($value < 1) {
            $value = $min;
        }
        elseif ($value > 99) {
            $value = $max;
        }
        return $value;
    }
}