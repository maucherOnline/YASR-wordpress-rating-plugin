<?php

/**
 * @author Dario Curvino <@dudo>
 * @since
 * @return
 */
class YasrRichSnippetsItemTypes {

    /**
     * @var array
     */
    public static $schema_types = array(
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

    /**
     * By default, YASR_SUPPORTED_SCHEMA_TYPES is json_encoded to better support PHP <7
     * This function just return an array of the itemTypes
     *
     * @author Dario Curvino <@dudo>
     * @since  3.3.7
     * @return array
     */
    public static function yasr_return_schema_types() {
        return apply_filters('yasr_filter_itemtypes', self::$schema_types);
    }


}