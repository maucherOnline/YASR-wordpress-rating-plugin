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

//action is in the main file
function yasr_on_create_blog(WP_Site $new_site) {
    if (is_plugin_active_for_network( 'yet-another-stars-rating/yet-another-stars-rating.php' )) {
        switch_to_blog($new_site->blog_id);
        YasrOnInstall::createTables();
        restore_current_blog();
    }
}

// Deleting the table whenever a blog is deleted
function yasr_on_delete_blog($tables) {
    global $wpdb;

    $prefix = $wpdb->prefix . 'yasr_';  //Table prefix

    $yasr_multi_set_table    = $prefix . 'multi_set';
    $yasr_multi_set_fields   = $prefix . 'multi_set_fields';
    $yasr_log_multi_set      = $prefix . 'log_multi_set';
    $yasr_log_table          = $prefix . 'log';

    $tables[] = $yasr_multi_set_table;
    $tables[] = $yasr_multi_set_fields;
    $tables[] = $yasr_log_multi_set;
    $tables[] = $yasr_log_table;

    return $tables;
}

/**
 * Check if the current page is the Gutenberg block editor.
 *
 * @since  2.2.3
 *
 * @return bool
 */
function yasr_is_gutenberg_page() {
    if (function_exists('is_gutenberg_page') && is_gutenberg_page() ) {
        // The Gutenberg plugin is on.
        return true;
    }

    $current_screen = get_current_screen();

    if ($current_screen !== null
        && method_exists($current_screen, 'is_block_editor')
        && $current_screen->is_block_editor() ) {
        // Gutenberg page on 5+.
        return true;
    }

    return false;
}