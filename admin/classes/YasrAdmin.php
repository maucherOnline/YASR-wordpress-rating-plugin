<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly


/**
 * Class YasrAdmin
 *
 * @author Dario Curvino <@dudo>
 * @since  3.1.7
 *
 */
class YasrAdmin {
    public function init () {
        if(!is_admin()) {
            return;
        }

        $this->loadActions();
        $this->loadAjaxActions();
        $this->freemiusHooks();
    }

    /**
     * Load add_action that run in admin
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    private function loadActions() {
        add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));

        add_action('plugins_loaded', array($this, 'updateVersion'));
        add_action('plugins_loaded', array($this, 'widgetLastRatings'));
        add_action('plugins_loaded', array($this, 'editCategoryForm'));
    }

    /**
     * add ajax endpoint in admin side
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    private function loadAjaxActions() {
        add_action('wp_ajax_yasr_change_log_page',      array($this, 'loadDashboardWidgetAdmin'));

        add_action('wp_ajax_yasr_change_user_log_page', array($this, 'loadDashboardWidgetUser'));
    }

    /**
     * Freemius hooks, actions and filters
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    private function freemiusHooks() {
        /**
         * https://freemius.com/help/documentation/selling-with-freemius/free-trials/
         *
         * With this hook I change the default Freemius behavior to show trial message after 1 week instead of 1 day
         */
        yasr_fs()->add_filter( 'show_first_trial_after_n_sec', static function ($day_in_sec) {
            return WEEK_IN_SECONDS;
        } );

        /**
         * https://freemius.com/help/documentation/selling-with-freemius/free-trials/
         *
         * With this hook I change the default Freemius behavior to show trial every 60 days instead of 30
         */
        yasr_fs()->add_filter( 'reshow_trial_after_every_n_sec', static function ($thirty_days_in_sec) {
            return 2 * MONTH_IN_SECONDS;
        } );
    }

    /**
     * Load Scripts in admin side
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $hook | current page in the admin side
     *
     * @return void
     */
    public function addAdminScripts($hook) {
        global $yasr_settings_page;

        if ($hook === 'yet-another-stars-rating_page_yasr_pricing_page'
            || $hook === 'yet-another-stars-rating_page_yasr_settings_page-pricing') {

            if(!isset($_GET['trial'])) {
                wp_enqueue_style(
                    'yasrcss-pricing',
                    YASR_CSS_DIR_ADMIN . 'yasr-pricing-page.css',
                    false,
                    YASR_VERSION_NUM
                );

                YasrScriptsLoader::loadPrincingPage();
            }
        }

        if ($hook === 'index.php'
            || $hook === 'edit.php'
            || $hook === 'post.php'
            || $hook === 'post-new.php'
            || $hook === 'edit-comments.php'
            || $hook === 'term.php'
            || $hook === 'widgets.php'
            || $hook === 'site-editor.php'
            || $hook === 'appearance_page_gutenberg-edit-site'
            || $hook === $yasr_settings_page
            || $hook === 'yet-another-stars-rating_page_yasr_stats_page'
            || $hook === 'yet-another-stars-rating_page_yasr_pricing_page'
            || $hook === 'yet-another-stars-rating_page_yasr_settings_page-pricing'
        ) {
            YasrScriptsLoader::loadRequiredJs();

            /**
             * Add custom script in one of the page used by YASR, at the beginning
             *
             * @param $hook string
             */
            do_action('yasr_add_admin_scripts_begin', $hook);

            YasrScriptsLoader::loadTippy();
            YasrScriptsLoader::loadYasrAdmin();

            wp_enqueue_style(
                'yasrcss',
                YASR_CSS_DIR_ADMIN . 'yasr-admin.css',
                false,
                YASR_VERSION_NUM
            );

            /**
             * Add custom script in one of the page used by YASR, at the end
             *
             * @param $hook string
             */
            do_action('yasr_add_admin_scripts_end', $hook);
        }

        if ($hook === 'post.php' || $hook === 'post-new.php') {
            YasrScriptsLoader::loadClassicEditor();
        }

        //add this only in yasr setting page (admin.php?page=yasr_settings_page)
        if ($hook === $yasr_settings_page) {
            YasrScriptsLoader::loadCodeEditor();
            YasrScriptsLoader::loadAdminSettings();
            YasrScriptsLoader::loadTableCss();
        }

    }

    /**
     * Update version number and backward compatibility
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function updateVersion() {
        //do only in admin
        if (is_admin() && current_user_can('activate_plugins')) {
            global $wpdb;
            $yasr_stored_options = get_option('yasr_general_options');

            if (YASR_VERSION_INSTALLED !== false) {
                //In version 2.6.6 %overall_rating% pattern is replaced with %rating%
                //Remove March 2023
                if (version_compare(YASR_VERSION_INSTALLED, '2.6.6') === -1) {
                    if(array_key_exists('text_before_overall', $yasr_stored_options)) {
                        $yasr_stored_options['text_before_overall'] =
                            str_replace('%overall_rating%', '%rating%', $yasr_stored_options['text_before_overall']);

                        update_option('yasr_general_options', $yasr_stored_options);
                    }
                }

                //In version 2.7.4 option "text_before_stars" is removed.
                //if it was set to 0, be sure that text before overall is empty
                //Remove May 2023
                if (version_compare(YASR_VERSION_INSTALLED, '2.7.4') === -1) {
                    if (array_key_exists('text_before_stars', $yasr_stored_options)) {
                        if($yasr_stored_options['text_before_stars'] === 0) {
                            $yasr_stored_options['text_before_overall']  = '';

                            update_option('yasr_general_options', $yasr_stored_options);
                        }
                    }
                }

                //In version 2.9.7 the column comment_id is added
                //Remove Dec 2023
                if (version_compare(YASR_VERSION_INSTALLED, '2.9.7') === -1) {
                    $wpdb->query("ALTER TABLE " . YASR_LOG_MULTI_SET . " ADD comment_id bigint(20) NOT NULL AFTER post_id");
                }

            } //Endif yasr_version_installed !== false
            /****** End backward compatibility functions ******/

            //update version num
            if (YASR_VERSION_INSTALLED !== YASR_VERSION_NUM) {
                update_option('yasr-version', YASR_VERSION_NUM);
            }

        }

    }


    /**
     * Adds widget to show last ratings in dashboard
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function widgetLastRatings() {
        //This is for the admins (show all votes in the site)
        if (current_user_can('manage_options')) {
            add_action('wp_dashboard_setup', array($this, 'lastRatingsAdmin'));
        }

        //This is for all the users to see where they've voted
        add_action('wp_dashboard_setup', array($this, 'lastRatingsUser'));
    }

    /**
     * Add widget for admin, show all ratings
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function lastRatingsAdmin() {
        wp_add_dashboard_widget(
            'yasr_widget_log_dashboard', //slug for widget
            'Recent Ratings', //widget name
            array($this, 'loadDashboardWidgetAdmin') //function callback
        );
    }

    /**
     * Add widget for user, show all ratings that user give
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function lastRatingsUser() {
        wp_add_dashboard_widget(
            'yasr_users_dashboard_widget', //slug for widget
            'Your Ratings', //widget name
            array($this, 'loadDashboardWidgetUser') //function callback
        );
    }

    /**
     * This method is hooked both in loadAjaxActions and lastRatingsAdmin
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function loadDashboardWidgetAdmin() {
        $log_widget = new YasrLastRatingsWidget();
        $log_widget->adminWidget();
    } //End callback function


    /**
     * This method is hooked both in loadAjaxActions and lastRatingsUser
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    function loadDashboardWidgetUser() {
        $log_widget = new YasrLastRatingsWidget();
        $log_widget->userWidget();
    } //End callback function

    /**
     * Hook into category page to show YASR select
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function editCategoryForm () {
        if (current_user_can('manage_options')) {
            $edit_category = new YasrEditCategory();
            $edit_category->init();
        }
    }

}