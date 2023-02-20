<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly


/**
 * Class to export data
 *
 * @author Dario Curvino <@dudo>
 * @since  3.3.3
 */
class YasrProExportData {
    private $file_and_path;

    /**
     * Init the class
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.3.3
     * @return void
     */
    public function init () {
        //Simply add the tabs on settings page
        add_action('yasr_add_stats_tab',     array($this, 'exportTab'), 999);

        add_action('yasr_stats_tab_content', array($this, 'tabContent'));

        add_action('wp_ajax_yasr_export_csv_vv', array($this, 'returnVisitorVotesData'));
    }

    /**
     * The new tab
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     *
     * @param $active_tab
     *
     * @return void
     */
    public function exportTab ($active_tab) {
        ?>
        <a href="?page=yasr_stats_page&tab=yasr_csv_export"
           id="yasr_csv_export"
           class="nav-tab <?php if ($active_tab === 'yasr_csv_export') {
               echo 'nav-tab-active';
           } ?>"
        >
            <?php
                esc_html_e('Export data', 'yet-another-stars-rating');
                if (yasr_fs()->is__premium_only()) { //these if can't be merged
                    if (yasr_fs()->can_use_premium_code()) {
                        echo YASR_LOCKED_FEATURE;
                    }
                }

            ?>
        </a>
        <?php
    }

    /**
     * Tab content
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     *
     * @param $active_tab
     *
     * @return void
     */
    public function tabContent($active_tab) {
        if ($active_tab === 'yasr_csv_export') {
            $this->checkIfPost();

            $this->printPage();
        } //End tab ur options
    }

    /**
     * Check if $_POST['yasr_csv_nonce'], and later create the csv according to the post data
     *
     * @return void
     */
    public function checkIfPost() {
        if(isset($_POST['yasr_csv_nonce'])) {
            $nonce          = $_POST['yasr_csv_nonce'];

            $valid_nonce = YasrShortcodesAjax::validNonce($nonce, 'yasr-export-csv');

            if ($valid_nonce !== true) {
                wp_die(esc_html__('Error while checking nonce', 'yet-another-stars-rating'));
            }

            if (!current_user_can( 'manage_options' ) ) {
                wp_die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
            }

            /*
            if(isset($_POST['yasr_export_visitor_multiset']) && $_POST['yasr_export_visitor_multiset']) {
                $this->setFilePath('visitor_multiset');
                $data_to_export = $this->returnVisitorMultiData();
            }*/


        }
    }

    /**
     * Set file name and path
     */
    public function setFilePath($post_prefix) {
        //file name with date. e.g. format is 2020-Apr-25-10
        $file_name     = 'yasr_' . $post_prefix . '_' . date('Y-M-d__H:i:s') . '.csv';

        $this->file_and_path = WP_CONTENT_DIR .'/'. $file_name;
    }

    /**
     * Create the csv file, if file already exists (must have same second)
     * delete it
     *
     * @param $array_csv array must have properties "result" and "columns"
     */
    public function createCSV($array_csv) {
        if ($array_csv) {
            $error_txt = __('Error in creating the CSV file.', 'yet-another-stars-rating');

            // Open file in append mode
            $opened_file = fopen($this->file_and_path, 'ab');

            $success = fputcsv($opened_file, $array_csv['columns']);

            if($success === false) {
                $this->returnAjaxResponse('error', $error_txt);
            }

            foreach ($array_csv['results'] as $value) {
                $success = fputcsv($opened_file, $value);
                if($success === false) {
                    $this->returnAjaxResponse('error', $error_txt);
                }
            }

            fclose($opened_file);

            $success = __('CSV file created, refresh the page to download it.', 'yet-another-stars-rating');
            $this->returnAjaxResponse('success', $success);
        }
    }

    /**
     * Drow form, set the nonce
     */
    public function printPage () {
        $nonce       = wp_create_nonce('yasr-export-csv');
        ?>
        <div>
            <h3>
                <?php esc_html_e('Export Data', 'yet-another-stars-rating'); ?>
            </h3>
            <div class="yasr-help-box-settings" style="display: block">
                <?php
                    $url = wp_upload_dir();
                    esc_html_e('All the .csv files are saved into', 'yet-another-stars-rating');
                    echo ' ' . '<strong>'.$url['baseurl'].'</strong>. ';
                    esc_html_e('The files are deleted automatically after 7 days.', 'yet-another-stars-rating');
                ?>
            </div>

            <div class="yasr-container">
                <input type="hidden"
                       name="yasr_csv_nonce"
                       value="<?php echo esc_attr($nonce) ?>"
                       id="yasr_csv_nonce">
                <div class="yasr-box">
                    <?php
                        $description = esc_html__('Export all ratings saved through the shortcode ',
                            'yet-another-stars-rating');
                        $description .= ' <strong>yasr_visitor_votes</strong>';
                        $this->printExportBox('visitor_votes', 'Visitor Votes', $description);
                    ?>
                </div>
                <div class="yasr-box">
                    <?php
                        $description = esc_html__('Save all the author ratings', 'yet-another-stars-rating');
                        $this->printExportBox('overall_rating', 'Overall Rating', $description);
                    ?>
                </div>
                <div class="yasr-box">
                    <?php
                        $description = esc_html__('Export all ratings saved with shortcode',
                            'yet-another-stars-rating');
                        $description .= ' <strong>yasr_visitor_multiset</strong>';
                        $this->printExportBox('visitor_multiset', 'Visitor Multi Set', $description);
                    ?>
                </div>

                <div class="yasr-box">ciao</div>
                <div class="yasr-box">ciao</div>
                <div class="yasr-box">ciao</div>
            </div>
        </div>

        <?php

    }

    /**
     * Create link to download the file
     *
     * @author Dario Curvino <@dudo>
     *
     * @param $post_prefix
     * @since  3.3.3
     * @return void
     */
    public function createLinks($post_prefix) {
        $this->setFilePath($post_prefix);

        $now = time();

        $directory_obj = new DirectoryIterator(WP_CONTENT_DIR);
        $output_array = array();

        $i=0;
        foreach($directory_obj as $file) {

            //check if is a file
            if ($file->isFile() && ($file->getExtension() === 'csv')) {
                //get file name
                $file_name = $file->getFilename();

                $length = strlen('yasr_'.$post_prefix);

                //if file name doesn't start with yasr_ + post_prefix, go to next iteration
                if(substr($file_name, 0, $length) !== "yasr_".$post_prefix) {
                    continue;
                }
                //if files are older than 1 week, delete and go to next iteration
                if ($now - $file->getCTime() >= WEEK_IN_SECONDS) {
                    unlink(WP_CONTENT_DIR . '/' . $file_name);
                    continue;
                }

                //save in an array url and file name
                $output_array[$i]['url']  = content_url() . '/' . $file_name;
                $output_array[$i]['name'] = $file_name;
                $i++;
            }
        }

        //sort array by name, most recent file first
        arsort($output_array);

        //echo the array
        foreach ($output_array as $output) {
            echo '<p>';
            echo '<span class="dashicons dashicons-arrow-down-alt"></span>';
            echo '<a href="'.esc_url($output['url']).'">'.esc_html($output['name']).'</a>';
            echo '</p>';
        }
    }

    /**
     * Print the box with button to export data
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     *
     * @param $name            string     what to export
     * @param $readable_name   string     readable name
     * @param $description     string     box description
     *
     * @return void
     */
    private function printExportBox ($name, $readable_name, $description) {
        $id          = 'yasr-export-csv-' . $name;
        $name_hidden = 'yasr_export_'. $name;

        $translated_readable_name = sprintf('%s', esc_html__($readable_name));
        ?>
        <div>
            <h4>
                <?php
                $h5_text  = esc_html__('Export', 'yet-another-stars-rating');
                $h5_text .= ' ' . $translated_readable_name;

                echo $h5_text;
                ?>
            </h4>
            <h5>
                <?php echo yasr_kses($description); ?>
            </h5>
            <hr />
            <button class="button-primary" id="<?php echo esc_attr($id) ?>">
                <?php esc_html_e( 'Export Data', 'yet-another-stars-rating' );  ?>
            </button>

            <input type="hidden"
                   name="<?php echo esc_attr($name_hidden) ?>"
                   value="<?php echo esc_attr($name) ?>">
        </div>
        <div id="yasr-export-vv-ajax-result" style="margin: 5px 20px;" >
        </div>
        <div class="yasr-indented-answer">
            <?php
                $this->createLinks($name);
            ?>
        </div>
        <?php
    }

    /**
     * Do the query to export visitor votes data and return along with csv columns
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     * @return void
     */
    public function returnVisitorVotesData() {
        $this->setFilePath('visitor_votes');

        global $wpdb;

        $data_to_export = array();

        $data_to_export['results'] = $wpdb->get_results(
            'SELECT posts.post_title as TITLE,
            users.user_login as USER,
            log.vote as VOTE,
            log.date as DATE
            FROM ' . $wpdb->posts .' as posts,
            '.$wpdb->users.' as users,
            ' . YASR_LOG_TABLE . '   as log
            WHERE posts.ID = log.post_id
            ORDER BY log.date DESC',
            ARRAY_A);

        if(!empty($data_to_export['results'])) {
            $data_to_export['columns'] = array(
                'TITLE',
                'USER',
                'VOTE',
                'DATE',
            );
            $this->createCSV($data_to_export);
        } else {
            $error_text = __('Error while preparing data to export', 'yet-another-stars-rating');

            $this->returnAjaxResponse('error', $error_text);
        }

    }

    /**
     * Do the query to export visitor multiset and return along with csv columns
     *
     * @return array
     */
    private function returnVisitorMultiData() {
        global $wpdb;

        $array_to_return = array();

        //get logs
        $array_to_return['results'] = $wpdb->get_results(
            'SELECT posts.post_title as TITLE,
            multiset.set_name as "SET NAME",
            field.field_name as FIELD,
            log.vote as VOTE,
            log.date as DATE,
            log.set_type as "SET ID"
            FROM ' . $wpdb->posts .' as posts,
                ' . YASR_LOG_MULTI_SET . '  as log,
                ' . YASR_MULTI_SET_NAME_TABLE .'  as multiset,
                ' . YASR_MULTI_SET_FIELDS_TABLE . ' as field
            WHERE log.set_type = multiset.set_id
            AND   field.parent_set_id = log.set_type
            AND   log.field_id = field.field_id
            AND   posts.ID = log.post_id
            ORDER BY log.date DESC',
            ARRAY_A
        );

        $array_to_return['columns'] = array(
            'TITLE',
            'SET NAME',
            'FIELD',
            'VOTE',
            'DATE',
            'SET ID'
        );

        return($array_to_return);
    }

    /**
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     *
     * @param $status
     * @param $text
     *
     * @return void
     */
    public function returnAjaxResponse ($status, $text) {
        echo json_encode(array(
            'status' => $status,
            'text'   => wp_kses_post($text)
        ));
        //close Ajax
        die();
    }
}