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

//e.g. http://localhost/plugin_development/wp-content/plugins/yet-another-stars-rating/includes/js/
define('YASR_JS_DIR_INCLUDES', plugins_url() . '/' . YASR_RELATIVE_PATH_INCLUDES . '/js/');
//CSS directory absolute URL
define('YASR_CSS_DIR_INCLUDES', plugins_url() . '/' . YASR_RELATIVE_PATH_INCLUDES . '/css/');

global $wpdb;
//defining tables names
define('YASR_LOG_TABLE',              $wpdb->prefix . 'yasr_log');
define('YASR_LOG_MULTI_SET',          $wpdb->prefix . 'yasr_log_multi_set');
define('YASR_MULTI_SET_NAME_TABLE',   $wpdb->prefix . 'yasr_multi_set');
define('YASR_MULTI_SET_FIELDS_TABLE', $wpdb->prefix . 'yasr_multi_set_fields');

require YASR_ABSOLUTE_PATH_INCLUDES . '/yasr-includes-functions.php';
require YASR_ABSOLUTE_PATH_INCLUDES . '/yasr-includes-db-functions.php';
require YASR_ABSOLUTE_PATH_INCLUDES . '/yasr-widgets.php';
require YASR_ABSOLUTE_PATH_INCLUDES . '/shortcodes/yasr-shortcode-functions.php';


/**
 * Callback function for the spl_autoload_register above.
 *
 * @param $class
 */
function yasr_autoload_includes_classes($class) {
    /**
     * If the class being requested does not start with 'Yasr' prefix,
     * it's not in Yasr Project
     */
    if (0 !== strpos($class, 'Yasr')) {
        return;
    }
    $file_name =  YASR_ABSOLUTE_PATH_INCLUDES . '/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name)) {
        require($file_name);
    }
}

//AutoLoad Yasr Classes, only when a object is created
spl_autoload_register('yasr_autoload_includes_classes');

//Get settings
$settings         = new YasrSettingsValues();
$general_settings = $settings->getGeneralSettings();
$style_settings   = $settings->getStyleSettings();
$multi_settings   = $settings->getMultiSettings();

//do defines based on the settings
$do_define = new YasrDefineOptions();
$do_define->generalSettings($general_settings); //set all the defines for general settings
$do_define->styleSettings($style_settings);
$do_define->multisetSettings($multi_settings);
$do_define->onPluginsLoaded();
$do_define->misc();

//run includes filters
$yasr_includes_filter = new YasrIncludesFilters();
$yasr_includes_filter->filterCustomTexts();

//Load window.var used by YASR
$load_script = new YasrScriptsLoader();
$load_script->loadRequiredScripts();

//support for caching plugins
$yasr_includes_filter->cachingPluginSupport();

//Init Ajax
$init_ajax = new YasrShortcodesAjax();
$init_ajax->init();

//To better support php version < 7, I can't use an array into define
//I can use const here, because it is a primitive value
//https://stackoverflow.com/questions/1290318/php-constants-containing-arrays
//https://stackoverflow.com/questions/2447791/php-define-vs-const
const YASR_SUPPORTED_SCHEMA_TYPES =
    array (
        'BlogPosting',
        'Book',
        'Course',
        'CreativeWorkSeason',
        'CreativeWorkSeries',
        'Episode',
        'Event',
        'Game',
        'LocalBusiness',
        'MediaObject',
        'Movie',
        'MusicPlaylist',
        'MusicRecording',
        'Organization',
        'Product',
        'Recipe',
        'SoftwareApplication'
    );

//here the array member must contain main itemtype name
//e.g. yasr_softwareapplication contain 'SoftwareApplication'
const YASR_SUPPORTED_SCHEMA_TYPES_ADDITIONAL_FIELDS =
    array(
        'yasr_schema_title',
        'yasr_book_author',
        'yasr_book_bookedition',
        'yasr_book_bookformat',
        'yasr_book_isbn',
        'yasr_book_number_of_pages',
        'yasr_localbusiness_address',
        'yasr_localbusiness_pricerange',
        'yasr_localbusiness_telephone',
        'yasr_movie_actor',
        'yasr_movie_datecreated',
        'yasr_movie_director',
        'yasr_movie_duration',
        'yasr_product_brand',
        'yasr_product_global_identifier_select',
        'yasr_product_global_identifier_value',
        'yasr_product_price',
        'yasr_product_price_availability',
        'yasr_product_price_currency',
        'yasr_product_price_url',
        'yasr_product_price_valid_until',
        'yasr_product_sku',
        'yasr_recipe_cooktime',
        'yasr_recipe_description',
        'yasr_recipe_keywords',
        'yasr_recipe_nutrition',
        'yasr_recipe_preptime',
        'yasr_recipe_recipecategory',
        'yasr_recipe_recipecuisine',
        'yasr_recipe_recipeingredient',
        'yasr_recipe_recipeinstructions',
        'yasr_recipe_video',
        'yasr_softwareapplication_category',
        'yasr_softwareapplication_os',
        'yasr_softwareapplication_price',
        'yasr_softwareapplication_price_availability',
        'yasr_softwareapplication_price_currency',
        'yasr_softwareapplication_price_url',
        'yasr_softwareapplication_price_valid_until',
    );

//Load rest API
require YASR_ABSOLUTE_PATH_INCLUDES . '/rest/yasr-rest.php';