<?php

/**
 * @author Dario Curvino <@dudo>
 *
 * @since 3.2.1
 * YasrOrderPosts
 */
class YasrDisplayPosts extends YasrShortcode {

    public $orderby;
    public $sort;
    private $query_args;
    public function __construct($atts, $shortcode_name) {
        parent::__construct($atts, $shortcode_name);

        if ($atts !== false) {
            $atts = (shortcode_atts(
                array(
                    'orderby'       => 'vv_most',
                    'sort'          => 'DESC',
                ), $atts, $shortcode_name
            ));
        }

        //@todo validate orderby att
        if($atts['orderby']) {

        }

        if($atts['sort'] !== 'ASC') {
            $atts['sort'] = 'DESC';
        }

        $this->orderby = $atts['orderby'];
        $this->sort    = $atts['sort'];

        $this->queryOverall();
    }

    public function queryOverall () {
        $this->query_args = array(
            'posts_per_page' => '10',
            'post_status'    => 'publish',
            'order'          => 'DESC',
            'orderby'        => 'meta_value',
            'meta_key'       => 'yasr_overall_rating',
        );
    }

    public function returnShortcode() {
        // The Query
        $the_query = new WP_Query($this->query_args);

        $shortcode_content = '';

        // The Loop
        if ($the_query->have_posts() ) {
            while ($the_query->have_posts()) : $the_query->the_post();

                $post_id = get_the_ID();//This is page id or post id
                $shortcode_content .= '<p>'.esc_html(get_post_field( 'post_title', $post_id, 'raw' )).'</p>';

            endwhile;
            /* Restore original Post Data */
            wp_reset_postdata();

            return $shortcode_content;

        } else {
            return 'no posts found';
        }
    }
}