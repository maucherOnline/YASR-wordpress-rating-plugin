<?php

/**
 * This class is a collection of methods to print settings description
 *
 * @author Dario Curvino <@dudo>
 * @since  3.4.1
 */
class YasrSettingsDescriptions {

    /**
     * Return the description of auto insert
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.6
     * @return string
     */
    public function descriptionAutoInsert() {
        $name = esc_html__('Auto Insert Options', 'yet-another-stars-rating');

        $description = sprintf(
            esc_html__(
                'Automatically adds YASR in your posts or pages. %s
            Disable this if you prefer to use shortcodes.', 'yet-another-stars-rating'
            ), '<br />'
        );

        return $this->settingsFieldDescription($name, $description);
    }

    /**
     * Return the title and the setting description
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.4.1
     *
     * @param $title
     * @param $description
     *
     * @return string
     */
    public function settingsFieldDescription($title, $description) {
        $div_desc    = '<div class="yasr-settings-description">';
        $end_div     = '.</div>';

        return $title . $div_desc . $description . $end_div;
    }
}