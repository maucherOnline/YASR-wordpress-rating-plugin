<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly


/**
 *
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
        add_action('plugins_loaded', array($this, 'widgetLastRatings'));

        add_action('plugins_loaded', array($this, 'editCategoryForm'));
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
     * Adds widget to show last ratings in dashboard
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function widgetLastRatings() {
        //This is for the admins (show all votes in the site)
        if (current_user_can('manage_options')) {
            add_action('wp_dashboard_setup', array($this, 'lastRatingAdmin'));
        }

        //This is for all the users to see where they've voted
        add_action('wp_dashboard_setup', array($this, 'lastRatingUser'));
    }

    /**
     * Add widget for admin, show all ratings
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function lastRatingAdmin() {
        wp_add_dashboard_widget(
            'yasr_widget_log_dashboard', //slug for widget
            'Recent Ratings', //widget name
            'yasr_widget_log_dashboard_callback' //function callback
        );
    }

    /**
     * Add widget for user, show all ratings that user give
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function lastRatingUser() {
        wp_add_dashboard_widget(
            'yasr_users_dashboard_widget', //slug for widget
            'Your Ratings', //widget name
            'yasr_users_dashboard_widget_callback' //function callback
        );
    }

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