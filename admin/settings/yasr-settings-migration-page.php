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

$plugin_imported = get_option('yasr_plugin_imported');
?>

<h3><?php esc_html_e('Migration Tools', 'yet-another-stars-rating'); ?></h3>

<table class="form-table yasr-settings-table" id="yasr-migrate-table">
    <tr>
        <td>
            <div>
                <?php
                    $import_plugin = new YasrImportRatingPlugins;

                    if (!$import_plugin->searchWPPR() && !$import_plugin->searchRMP()
                        && !$import_plugin->searchKKSR() && !$import_plugin->searchMR()) {
                        echo wp_kses_post($import_plugin->pluginFoundTitle(''));
                    }

                    if($import_plugin->searchWPPR()){
                        echo wp_kses_post($import_plugin->pluginFoundTitle('WP-PostRatings'));

                        $number_of_stars = (int)get_option('postratings_max', false);

                        if ($number_of_stars && $number_of_stars !== 5) {
                            $error  = '<div class="yasr-indented-answer" style="margin-top: 10px;">';
                            $error .= sprintf(__('You\' re using a star set different from 5 %s
                                Import can not be done', 'yet-another-stars-rating'), '<br />');
                            $error .= '</div>';
                            echo wp_kses_post($error);
                        } else {
                            echo wp_kses_post($import_plugin->noteAverageRating('WP-PostRatings'));

                            $wppr_imported = $import_plugin->alreadyImported(
                                    $plugin_imported, 'wppr', 'WP-PostRatings'
                            );

                            if($wppr_imported !== false) {
                                echo wp_kses_post($wppr_imported);
                            } else {
                                $number_of_queries_wppr = (int) $import_plugin->wpprQueryNumber();

                                if ($number_of_queries_wppr > 1000) {
                                    echo wp_kses_post(
                                            $import_plugin->alertBox('WP-PostRatings', $number_of_queries_wppr)
                                    );
                                }
                                $import_plugin->htmlImportButton('wppr');
                            }
                        }
                    }

                    if($import_plugin->searchKKSR()){
                        echo wp_kses_post($import_plugin->pluginFoundTitle('KK Star Ratings'));
                        echo wp_kses_post($import_plugin->noteAverageRating('KK Star Ratings'));
                        $kksr_imported = $import_plugin->alreadyImported(
                                $plugin_imported, 'kksr', 'KK Star Rating'
                        );

                        if($kksr_imported !== false) {
                            echo wp_kses_post($kksr_imported);
                        }
                        else {
                            $number_of_queries_kksr = (int)$import_plugin->kksrQueryNumber();

                            if($number_of_queries_kksr > 1000) {
                                echo wp_kses_post(
                                        $import_plugin->alertBox('KK Stars Rating', $number_of_queries_kksr)
                                );
                            }
                            $import_plugin->htmlImportButton('kksr');
                        }
                    }

                    if($import_plugin->searchRMP()) {
                        echo wp_kses_post($import_plugin->pluginFoundTitle('Rate My Post'));
                        $rmp_imported = $import_plugin->alreadyImported(
                                $plugin_imported, 'rmp', 'Rate My Post'
                        );

                        if($rmp_imported !== false) {
                            echo wp_kses_post($rmp_imported);
                        }
                        else {
                            $number_of_queries_rmp = (int)$import_plugin->rmpQueryNumber();

                            if($number_of_queries_rmp > 1000) {
                                echo wp_kses_post(
                                        $import_plugin->alertBox('Rate My Post', $number_of_queries_rmp)
                                );
                            }
                            $import_plugin->htmlImportButton('rmp');
                        }
                    }

                    if($import_plugin->searchMR()){
                        echo wp_kses_post($import_plugin->pluginFoundTitle('Multi Rating'));
                        echo wp_kses_post($import_plugin->noteAverageRating('Multi Rating'));
                        $mr_imported = $import_plugin->alreadyImported(
                                $plugin_imported, 'mr', 'Multi Rating'
                        );

                        if($mr_imported !== false) {
                            echo wp_kses_post($mr_imported);
                        }
                        else {
                            $number_of_queries_mr = (int) $import_plugin->mrQueryNumber();

                            if ($number_of_queries_mr > 1000) {
                                echo wp_kses_post(
                                        $import_plugin->alertBox('Multi Rating', $number_of_queries_mr)
                                );
                            }
                            $import_plugin->htmlImportButton('mr');
                        }
                    }

                    do_action('yasr_migration_page_bottom', $plugin_imported);
                ?>
            </div>
        </td>
    </tr>
</table>