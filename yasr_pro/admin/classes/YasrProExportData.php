<?php

/**
 * @author Dario Curvino <@dudo>
 * @since
 * @return
 */
class YasrProExportData {
    private $file_and_path;

    public $temp_dir_abs;

    public function init () {
        //file name with date. e.g. format is 2020-Apr-25-10
        $file_name     = 'yasr_' . date('Y-M-d__H:i:s') . '.csv';

        $this->temp_dir_abs  = WP_CONTENT_DIR;
        $this->file_and_path = $this->temp_dir_abs .'/'. $file_name;

        //Simply add the tabs on settings page
        add_action('yasr_add_stats_tab', array($this, 'exportTab'), 999);

        add_action('yasr_stats_tab_content', array($this, 'tabContent'));
    }

    /**
     * The new tab
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.1
     *
     * @param $active_tab
     *
     * @return void
     */
    public function exportTab ($active_tab) {
        ?>
        <a href="?page=yasr_stats_page&tab=yasr_csv_export"
           class="nav-tab <?php if ($active_tab === 'yasr_csv_export') {
               echo 'nav-tab-active';
           } ?>">
            <?php esc_html_e('Export data', 'yasr-multiset-csv-export'); ?>
        </a>
        <?php
    }

    /**
     * Tab content
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.1
     *
     * @param $active_tab
     *
     * @return void
     */
    public function tabContent($active_tab) {
        if ($active_tab === 'yasr_csv_export') {

            $array_csv = $this->checkIfPost();

            if($array_csv) {
                $this->createCSV($array_csv);
            }
            ?>

            <div class="yasr-settingsdiv">
                <?php
                    $this->drowTable();
                ?>
            </div>
            <?php
        } //End tab ur options
    }

    /**
     * Check if $_POST['yasr_csv_nonce'], and if so
     * call returnResults
     *
     * @return array|object|void|null
     */
    public function checkIfPost() {
        if(isset($_POST['yasr_csv_nonce'])) {
            $nonce = $_POST['yasr_csv_nonce'];

            if (!wp_verify_nonce( $nonce, 'yasr-multiset-csv' ) ) {
                wp_die(esc_html__('Error while checking nonce', 'yet-another-stars-rating'));
            }

            if (!current_user_can( 'manage_options' ) ) {
                wp_die(esc_html__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
            }


            return $this->returnVisitorMultiData();
        }
    }

    /**
     * Do the query to export visitor multiset and return results
     *
     * @return array|object|null
     */
    private function returnVisitorMultiData() {
        global $wpdb;

        //get logs
        $results = $wpdb->get_results(
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

        return($results);
    }

    /**
     * Create the csv file, if file already exists (must have same second)
     * delete it
     *
     * @param $array_csv
     */
    public function createCSV($array_csv) {
        if ($array_csv) {
            //IF file with same name already exists, delete it
            if (file_exists($this->file_and_path)) {
                unlink($this->file_and_path);
            }

            // Open file in append mode
            $opened_file = fopen($this->file_and_path, 'ab');

            $array_column_names = array(
                'TITLE',
                'SET NAME',
                'FIELD',
                'VOTE',
                'DATE',
                'SET ID'
            );

            fputcsv($opened_file, $array_column_names);

            foreach ($array_csv as $value) {
                fputcsv($opened_file, $value);
            }

            fclose($opened_file);
        }
    }

    /**
     * Drow form and table, set the nonce
     */
    public function drowTable () {
        $yasr_multiset_csv_nonce = wp_create_nonce('yasr-multiset-csv');
        ?>
        <h3>
            <?php _e('Export Multi Set', 'yet-another-stars-rating'); ?>
        </h3>

        <table class="form-table" id="yasr-export-multiset-csv">
            <tr>
                <td>
                    <div class="yasr-indented-answer">
                        <form action="<?php echo esc_url(admin_url('admin.php?page=yasr_stats_page&tab=yasr_csv_export')) ?>"
                              method="post">
                            <button class="button-primary" id="yasr-export-multiset-csv-submit">
                                <?php _e( 'Export CSV Multi Set', 'yasr-multiset-csv-export' );  ?>
                            </button>
                            <input type="hidden"
                                   name="yasr_csv_nonce"
                                   id="yasr-export-multiset-csv-submit-nonce"
                                   value="<?php echo esc_attr($yasr_multiset_csv_nonce) ?>">
                        </form>
                    </div>

                    <div id="yasr-export-multiset-csv-answer" class="yasr-indented-answer">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php $this->createLinks(); ?>
                </td>
            </tr>
        </table>
        <?php

    }

    /**
     * Create link to download the file
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.3.1
     * @return void
     */
    public function createLinks() {
        $now = time();

        $directory_obj = new DirectoryIterator($this->temp_dir_abs);
        $output_array = array();

        $i=0;
        foreach($directory_obj as $file) {
            //check if is a file
            if ($file->isFile() && ($file->getExtension() === 'csv')) {
                //get file name
                $file_name = $file->getFilename();

                //if file name doesn't start with yasr_, go to next iteration
                if(substr($file_name, 0, 5) !== "yasr_") {
                    continue;
                }
                //if files are older than 1 day, delete
                if ($now - $file->getCTime() >= 60 * 60 * 24) {
                    unlink($this->file_and_path);
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
            echo '<a href="'.esc_url($output['url']).'">'.esc_html($output['name']).'</a>';
            echo '<br />';
        }

    }
}