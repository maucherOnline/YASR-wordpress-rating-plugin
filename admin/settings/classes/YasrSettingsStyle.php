<?php

class YasrSettingsStyle {
    public function init() {

        add_filter('yasr_filter_style_options', array($this, 'defaultStarSet'));

    }

    /**
     * Filter the $style_options and, if a default value doesn't exist,
     * set 'rater-yasr' as default
     * 
     * @param $style_options
     *
     * @return mixed
     */
    public static function defaultStarSet($style_options) {
        if (!array_key_exists('stars_set_free', $style_options)) {
            $style_options['stars_set_free'] = 'rater-yasr'; //..default value if not exists
        }
        return $style_options;
    }

}