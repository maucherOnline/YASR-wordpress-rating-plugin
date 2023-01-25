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

    /**
     * @author Dario Curvino <@dudo>
     *
     * @since  3.2.1
     * @return void
     */
    public function queryOverall () {
        //default page
        $paged = 1;

        //if get_query_var('paged'), get the new page
        if (get_query_var('paged')) {
            $paged = (int)get_query_var('paged');
        }

        $this->query_args = array(
            'posts_per_page' => '10',
            'post_status'    => 'publish',
            'order'          => 'DESC',
            'orderby'        => 'meta_value',
            'meta_key'       => 'yasr_overall_rating',
            'paged'          => $paged,
        );
    }

    /**
     * Return the shortcode
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.2.1
     * @return string
     */
    public function returnShortcode() {
        // The Query
        $the_query         = new WP_Query($this->query_args);

        $shortcode_content = '';

        // The Loop
        if ($the_query->have_posts() ) {
            while ($the_query->have_posts()) : $the_query->the_post();
                //This is page id or post id
                $post_id = get_the_ID();
                $shortcode_content .= $this->content($post_id);
            endwhile;

            /* Restore original Post Data */
            wp_reset_postdata();

            $shortcode_content .= $this->pagination($the_query);
            return $shortcode_content;
        } else {
            return esc_html__('No posts found', 'yet-another-stars-rating');
        }
    }

    /**
     * Return the shortcode content
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.2.1
     *
     * @param $post_id
     *
     * @return string
     */
    public function content($post_id) {
        $thumb = '';
        if (has_post_thumbnail($post_id) === true) {
            $thumb = '<div class="post-thumbnail">
                          <a href="'.get_the_permalink().'">
                              '.get_the_post_thumbnail($post_id, 'thumbnail', array( 'class' => 'alignleft' ) ).'
                          </a>
                      </div>';
	    }

        return "<div>
                    <h3 class='yasr-entry-title'>
                        <a href=".esc_url(get_the_permalink())." rel='bookmark'>
                            ".esc_html(get_post_field( 'post_title', $post_id, 'raw' ))."
                        </a>
                    </h3>
                    <div class='yasr-entry-meta'>
                        <a href='".esc_url(get_author_posts_url(get_the_author_meta('ID')))."'>
                          ".get_the_author()."
                        </a>
                         <span class='tp-post-item-date'>".get_the_date()."</span>
                    </div> <!-- End .entry-meta -->
                    <div class='yasr-entry-content'>
                        ".$thumb . get_the_excerpt()."    
                    </div>
                </div>";
    }

    /**
     * Return the pagination links
     * https://developer.wordpress.org/reference/functions/paginate_links/
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.2.1
     *
     * @param $query
     *
     * @return string|string[]|null
     */
    public function pagination ($query) {
        $big = 999999999; // need an unlikely integer

        return paginate_links(
            array(
                'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'  => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total'   => $query->max_num_pages
            )
        );
    }
}