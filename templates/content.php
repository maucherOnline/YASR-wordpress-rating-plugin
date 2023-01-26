<?php
/**
 * Template file for shortcode yasr_display_posts
 *
 * @author Dario Curvino <@dudo>
 * @since  3.2.1
 *
 * To customize this template, create a folder in your current theme (better use a child theme) named "yasr" and copy it there.
 */

$post_id = get_the_ID();

$thumb = '';
if (has_post_thumbnail($post_id) === true) {
    $thumb = '<div class="yasr-post-thumbnail">
                  <a href="'.esc_url(get_the_permalink()).'">
                      '.get_the_post_thumbnail($post_id, 'thumbnail', array( 'class' => 'alignleft' ) ).'
                  </a>
              </div>';
}

?>
<div>
    <h3 class='yasr-entry-title'>
        <a href="<?php the_permalink()?>" rel='bookmark'>
            <?php echo esc_html(get_post_field( 'post_title', $post_id, 'raw' )) ?>
        </a>
    </h3>
    <div class='yasr-entry-meta'>
        <a href='<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID')))?>'>
            <?php the_author() ?>
        </a>
        <span class='tp-post-item-date'>
            <?php the_date() ?>
        </span>
    </div> <!-- End .entry-meta -->
    <div class='yasr-entry-content'>
        <?php echo wp_kses_post($thumb . get_the_excerpt()); ?>
    </div>
</div>
