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

class YasrProLoadAdmin {

    //Use these proprieties to hook
    //https://wordpress.stackexchange.com/questions/386498/remove-action-how-to-access-to-a-method-in-an-child-class
    public $yasr_fake_ratings;
    public $yasr_stylish_admin;
    public $yasr_ur_admin;

    public $yasr_export;

    public function init() {
        //load js or css before the ones in the free version are loaded
        add_action('yasr_add_admin_scripts_begin', array($this, 'loadBefore'));

        //load js or css after the ones in the free version are loaded
        add_action('yasr_add_admin_scripts_end',   array($this, 'loadAfter'));

        //Add js constants to gutenberg
        add_filter('yasr_gutenberg_constants',     array($this, 'gutenbergConstants'));

        //load gutenberg dependencies
        add_action('enqueue_block_editor_assets',  array($this, 'loadGutenbergScripts'));

        //Show support boxes
        add_action('yasr_right_settings_panel_box', array($this, 'platinumSupport'));

        //This will load fake rating metabox
        $this->yasr_fake_ratings  = new YasrProFakeRatings();
        $this->yasr_fake_ratings->init();

        $this->yasr_stylish_admin = new YasrProStylishAdmin();
        $this->yasr_stylish_admin->init();

        $this->yasr_ur_admin = new YasrProUrAdmin();
        $this->yasr_ur_admin->init();

        $this->yasr_export = new YasrProExportData();
        $this->yasr_export->init();

        //Filter menu to show contact page
        yasr_fs()->add_filter('is_submenu_visible', function ($is_visible, $menu_id) {
            if ('contact' !== $menu_id) {
                return $is_visible;
            }

            if(yasr_fs()->is_plan('yasrpro') || yasr_fs()->is_plan('yasr_platinum') || yasr_fs()->is_trial())  {
                return yasr_fs()->can_use_premium_code();
            }

            return null;
        }, 10, 2);

        //Change lock icon
        add_filter('yasr_feature_locked', static function () {
            $text = __('You\'ve unlocked this feature!', 'yasr-pro');
            return '<span class="dashicons dashicons-unlock" title="'.$text.'"></span>';
        }, 10, 1);

        //Change disabled attribute
        add_filter('yasr_feature_locked_html_attribute', static function (){
            return '';
        }, 10, 1);

        //Change disabled attribute
        add_filter('yasr_feature_locked_text', static function () {
            return '';
        });

        //Shows form in edit category page
        add_action('plugins_loaded', static function(){
            if (current_user_can('manage_options')) {
                YasrProEditCategory::init();
            }
        }, 11);
    }

    /**
     * Load scripts before the one in the free versions are loaded
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.2
     *
     * @param $hook
     */
    public function loadBefore($hook) {
        //required to load file uploader
        if (($hook === 'toplevel_page_yasr_settings_page') &&
            (isset($_GET['tab']) && $_GET['tab'] === 'style_options')) {
            wp_enqueue_media();
        }

        //Load javascript hooks
        wp_enqueue_script(
            'yasrproadmin-hooks',
            YASR_PRO_JS_DIR . 'yasr-pro-admin-hooks.js',
            array('jquery'),
            YASR_VERSION_NUM,
            true
        ); //js
    }

    /**
     * Load Css and JS in admin area
     * @author Dario Curvino <@dudo>
     * @since 2.6.2
     *
     * @param $hook
     */
    public function loadAfter($hook) {
        global $yasr_settings_page;

        wp_enqueue_style(
            'yasrcrcssadmin',
            YASR_PRO_CSS_DIR . 'yasr-pro-admin.css',
            false, YASR_VERSION_NUM
        );

        if($hook === 'edit-comments.php') {
            wp_enqueue_script(
                'yasrEditComments',
                YASR_PRO_JS_DIR . 'yasr-pro-edit-comments.js',
                array('yasr-window-var'),
                YASR_VERSION_NUM,
                true
            ); //js
        }

        //add this only in yasr setting page (admin.php?page=yasr_settings_page) and stats page
        if ($hook === $yasr_settings_page || $hook === 'yet-another-stars-rating_page_yasr_stats_page') {
            wp_enqueue_script(
                'yasrprosettings',
                YASR_PRO_JS_DIR . 'yasr-pro-settings.js', array('jquery', 'tippy', 'yasradmin', 'yasr-window-var'),
                YASR_VERSION_NUM,
                true
            ); //js
        }
    }


    /***
     * Change Gutenberg constants
     *
     * @since 2.9.7
     *
     * @return array
     *
     */
    public function gutenbergConstants($constants_array) {
        $constants_array['proVersion']  = json_encode(true);
        $constants_array['lockedClass'] = 'dashicons dashicons-unlock';
        $constants_array['lockedText']  = esc_html__('You\'ve unlocked this feature!', 'yet-another-stars-rating');

        return $constants_array;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     */
    public function loadGutenbergScripts () {
        $current_screen = get_current_screen();
        if ($current_screen !== null
            && property_exists($current_screen, 'base')
            && $current_screen->base === 'post') {
            //Bundled pro file
            wp_enqueue_script(
                'yasr_pro_gutenberg',
                YASR_PRO_JS_DIR . 'yasr-pro-gutenberg.js',
                array('wp-i18n'),
                YASR_VERSION_NUM, true
            );
        }
    }

    public function platinumSupport() {
        if(yasr_fs()->is_plan('yasr_platinum') ) {
            $div = '<div class="yasr-donatedivdx" id="yasr-ask-five-stars">';

            $text = '<div class="yasr-donate-title">
                     <span class="dashicons dashicons-unlock"></span>'
                . esc_html__('You\'re using YASR Platinum!', 'yet-another-stars-rating') .
                '</div>';

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

            $div_and_text = $div . $text . '</div>';

            echo wp_kses_post($div_and_text);
        }
    }

}