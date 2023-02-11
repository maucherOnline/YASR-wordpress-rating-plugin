<?php

/**
 * @author Dario Curvino <@dudo>
 * @since  3.3.1
 */
class YasrStats {
    public static function printTabs($active_tab) {
        ?>
        <h2 class="nav-tab-wrapper yasr-no-underline">

            <a href="?page=yasr_stats_page&tab=logs" class="nav-tab
                <?php echo ($active_tab === 'logs') ? 'nav-tab-active' : ''; ?>"
            >
                <?php esc_html_e('Visitor Votes', 'yet-another-stars-rating'); ?>
            </a>

            <a href="?page=yasr_stats_page&tab=logs_multi" class="nav-tab
            <?php echo ($active_tab === 'logs_multi') ? 'nav-tab-active' : ''; ?>"
            >
                <?php esc_html_e('MultiSet', 'yet-another-stars-rating'); ?>
            </a>

            <a href="?page=yasr_stats_page&tab=overall" class="nav-tab
            <?php echo ($active_tab === 'overall') ? 'nav-tab-active' : ''; ?>"
            >
                <?php esc_html_e('Overall Rating', 'yet-another-stars-rating'); ?>
            </a>

            <?php
                /**
                * Use this hook to add a tab into yasr_stats_page
                */
                do_action('yasr_add_stats_tab', $active_tab);
            ?>

            <a href="?page=yasr_settings_page-pricing" class="nav-tab">
                <?php esc_html_e('Upgrade', 'yet-another-stars-rating'); ?>
            </a>

        </h2>

        <?php
    }
}