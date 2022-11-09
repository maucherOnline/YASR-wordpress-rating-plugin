<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@gmail.com)

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


/**
 * This function print the right box for premium version
 * If platinum license is used, then print Skype and slack support
 *
 */
function yasr_pro_settings_panel_support() {
    $url = admin_url(). 'admin.php?page=yasr_settings_page-contact';

    $div = "<div class='yasr-donatedivdx' id='yasr-ask-five-stars'>";

    $text = '<div class="yasr-donate-title">
                 <span class="dashicons dashicons-unlock"></span>'
                     . esc_html__('You\'re using YASR Pro!', 'yet-another-stars-rating') .
             '</div>';

    $text .=   '<div class="yasr-donate-single-resource">
                    <span class="dashicons dashicons-editor-help" style="color: #6c6c6c"></span>
                    <a href="'.esc_url($url).'">'
                        . esc_html__('Help', 'yet-another-stars-rating') .
                    '</a>
                </div>';

    if(yasr_fs()->is_plan('yasr_platinum') ) {

        $text .= '<div class="yasr-donate-single-resource">
                        <span class="dashicons dashicons-format-chat" style="color: #6c6c6c"></span>
                            <a target="blank" href="skype:live:support_58062">'
                                . esc_html__('Skype support', 'yet-another-stars-rating') .
                            '</a>
                   </div>';

        $text .= '<div class="yasr-donate-single-resource">
                      <span class="dashicons dashicons-format-chat" style="color: #6c6c6c"></span>
                          <a target="blank" href="https://wordpress.slack.com/messages/D2BUTQNDP">'
                              . esc_html__('Slack support', 'yet-another-stars-rating') .
                          '</a>
                   </div>';

    }

    $div_and_text = $div . $text . '</div>';

    echo wp_kses_post($div_and_text);

}