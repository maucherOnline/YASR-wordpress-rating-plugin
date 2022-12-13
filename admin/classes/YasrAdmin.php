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
    }

    /**
     * Load add_action that run in admin
     *
     * @author Dario Curvino <@dudo>
     * @since  3.1.7
     * @return void
     */
    public function loadActions() {
        add_action('plugins_loaded', array($this, 'widgetLastRatings'));

        add_action('plugins_loaded', array($this, 'editCategoryForm'));
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