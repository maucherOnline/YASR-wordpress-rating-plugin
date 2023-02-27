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

    private $upload_dir_writable;

    //Here I will store the pdo object
    public $pdo = null;

    /**
     * Init the class
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.3.3
     * @return void
     */
    public function init () {
        add_action('yasr_stats_tab_content',     array($this, 'tabContent'));

        add_action('wp_ajax_yasr_export_csv_vv', array($this, 'returnVisitorVotesData'));

        add_action('wp_ajax_yasr_export_csv_mv', array($this, 'returnVisitorMultiData'));

        add_action('wp_ajax_yasr_export_csv_ov', array($this, 'returnOverallRatingData'));

        //keep this here, so we can have a wp_die immediately if unable to connect
        $this->pdoConnect();
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
    public function tabContent ($active_tab) {
        if ($active_tab === 'yasr_csv_export') {
            $upload_dir                = wp_upload_dir();
            $this->upload_dir_writable = wp_is_writable($upload_dir ['path']);
            $nonce                     = wp_create_nonce('yasr-export-csv');
            ?>
            <div>
                <h3>
                    <?php esc_html_e('Export Data', 'yet-another-stars-rating'); ?>
                </h3>
                <div class="yasr-help-box-settings" style="display: block">
                    <?php
                        esc_html_e('All the .csv files are saved into', 'yet-another-stars-rating');
                        echo ' ' . '<strong>'.$upload_dir ['baseurl'].'</strong>. ';
                        esc_html_e('The files are deleted automatically after 7 days.', 'yet-another-stars-rating');

                    if($this->upload_dir_writable === false) {
                        $error = esc_html__("Upload folder is not writable, data can't be saved!", 'yet-another-stars-rating');
                        echo '<div style="margin-top: 20px; padding-left: 5px; border: 1px solid #c3c4c7; border-left-color: #d63638; 
                                          border-left-width: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                                          <h3>'.$error.'</h3>
                              </div>';
                    }
                    ?>
                </div>

                <div class="yasr-container">
                    <input type="hidden"
                           name="yasr_csv_nonce"
                           value="<?php echo esc_attr($nonce) ?>"
                           id="yasr_csv_nonce">

                    <!-- Boxes starts here -->
                    <!-- Visitor Votes -->
                    <div class="yasr-box">
                        <?php
                            $description = esc_html__('Export all ratings saved through the shortcode ',
                                'yet-another-stars-rating');
                            $description .= ' <strong>yasr_visitor_votes</strong>';
                            $this->printExportBox('visitor_votes', 'Visitor Votes', $description);
                        ?>
                    </div>
                    <!-- Visitor Multiset -->
                    <div class="yasr-box">
                        <?php
                            $description = esc_html__('Export all ratings saved through the shortcode ',
                                'yet-another-stars-rating');
                            $description .= ' <strong>yasr_visitor_multiset</strong>';
                            $this->printExportBox('visitor_multiset', 'Visitor Multi Set', $description);
                        ?>
                    </div>
                    <!-- Overall Rating -->
                    <div class="yasr-box">
                        <?php
                            $description = esc_html__('Save all the author ratings', 'yet-another-stars-rating');
                            $this->printExportBox('overall_rating', 'Overall Rating', $description);
                        ?>
                    </div>
                </div>
            </div>
        <?php
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
        $button_disabled = '';
        if($this->upload_dir_writable === false) {
            $button_disabled = 'disabled';
        }

        $button_id    = 'yasr-export-csv-' . $name;
        $answer_id    = 'yasr-export-ajax-result-'.$name;
        $name_hidden  = 'yasr_export_'. $name;

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
            <button class="button-primary" id="<?php echo esc_attr($button_id)?>" <?php echo esc_attr($button_disabled)?>>
                <?php esc_html_e( 'Export Data', 'yet-another-stars-rating' );  ?>
            </button>

            <input type="hidden"
                   name="<?php echo esc_attr($name_hidden) ?>"
                   value="<?php echo esc_attr($name) ?>">
        </div>
        <div id="<?php echo esc_attr($answer_id) ?>" style="margin: 5px 20px;" >
        </div>
        <div class="yasr-indented-answer">
            <?php
                $this->createLinks($name);
            ?>
        </div>
        <?php
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
     * Check the nonce, set file path and return the sql
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     * @return void
     */
    public function returnVisitorVotesData() {
        $this->checkNonce();

        $this->setFilePath('visitor_votes');

        global $wpdb;

        $sql = 'SELECT 
                    posts.post_title AS TITLE, 
                    IF(log.user_id = 0, "Anonymous", IFNULL(users.user_login, "User Deleted")) AS USER, 
                    log.vote AS VOTE, 
                    log.date AS DATE 
                FROM 
                    '. $wpdb->posts .' AS posts 
                    JOIN '.YASR_LOG_TABLE.' AS log ON posts.ID = log.post_id 
                    LEFT JOIN '. $wpdb->users .' AS users ON log.user_id = users.ID 
                WHERE 
                    log.user_id = 0 OR users.ID IS NOT NULL OR log.user_id <> 0
                ORDER BY DATE DESC;';

        $this->doQueryAndSaveCsv($sql);
    }

    /**
     * Check the nonce, set file path and return the sql
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.3.3
     * @return void
     */
    public function returnVisitorMultiData() {
        $this->checkNonce();

        $this->setFilePath('visitor_multiset');

        global $wpdb;

        //get logs
        $sql = 'SELECT posts.post_title as TITLE,
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
                ORDER BY log.date DESC'
        ;

        $this->doQueryAndSaveCsv($sql);
    }

    /**
     * Check the nonce, set file path and return the sql
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.3.3
     * @return void
     */
    public function returnOverallRatingData() {
        $this->checkNonce();

        $this->setFilePath('overall_rating');

        global $wpdb;

        $sql = 'SELECT p.post_title AS title,
                u.user_login  AS author,
                m.meta_value  AS rating,
                p.post_date   AS date
                FROM '.$wpdb->posts.'    AS p,
                     '.$wpdb->users.'    AS u,
                     '.$wpdb->postmeta.' AS m
                WHERE m.post_id = p.ID
                AND   meta_key = "yasr_overall_rating"
                AND   p.post_author = u.ID
                ORDER BY p.post_date DESC; ';

        $this->doQueryAndSaveCsv($sql);
    }

    /**
     * Do the query and write csv
     *
     * @author Dario Curvino <@dudo>
     * @since  3.3.3
     *
     * @param     $columns
     * @param     $sql
     *
     * @return void
     */
    public function doQueryAndSaveCsv($sql) {
        //be sure to initialize it again
        $this->pdoConnect();

        $result = @$this->pdo->query($sql);

        if ($result) {
            //store here the name of columns
            $columns = array();

            // loop for every column
            for ($i = 0; $i < $result->columnCount(); $i++) {
                //get column meta returns an array
                //https://www.php.net/manual/en/pdostatement.getcolumnmeta.php
                $col = $result->getColumnMeta($i);
                //get col['name'] and make it uppercase
                $columns[] = strtoupper($col['name']);
            }

            //open file in write mode
            $open_csv = $this->openCsv('w');

            //write the columns header
            $this->writeCSV($open_csv, $columns);

            //open file in append mode
            $open_csv = $this->openCsv('a');

            //loop the query result
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                //write row by row
                $this->writeCSV($open_csv, $row);
            }

            //close the csv
            fclose($open_csv);

            //empty the pdo var, no needed anymore
            $this->pdo = null;

            $success = esc_html__('CSV file created, refresh the page to download it.', 'yet-another-stars-rating');
            $this->returnAjaxResponse('success', $success);
        }  else {
            $error_text = esc_html__('Error while preparing data to export', 'yet-another-stars-rating');
            $this->returnAjaxResponse('error', $error_text);
        }
    }

    /**
     * Open the file and return it if no error are found
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     *
     * @param $mode  | fopen mode. w=write, a = append
     *
     * @return false|mixed|resource
     */
    public function openCsv ($mode) {
        $error_txt = esc_html__('Error while creating the CSV file.', 'yet-another-stars-rating');

        // Open file in append mode
        $file_open = fopen($this->file_and_path, $mode);

        if($file_open === false) {
            $this->returnAjaxResponse('error', $error_txt);
        }

        return $file_open;
    }

    /**
     * Write into the csv file
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     *
     * @param $opened_file
     * @param $array_csv
     *
     * @return void
     */
    public function writeCSV($opened_file, $array_csv) {
        $error_txt = esc_html__('Error while creating the CSV file.', 'yet-another-stars-rating');

        if ($array_csv) {
            //write the opened file
            $success    = fputcsv($opened_file, $array_csv);

            if($success === false) {
                $this->returnAjaxResponse('error', $error_txt);
            }
            return;
        }

        $this->returnAjaxResponse('error', $error_txt);
    }

    /**
     * Check for nonce
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.3.3
     * @return true|void
     */
    public function checkNonce () {
        $nonce          = $_POST['nonce'];
        $error_nonce    = esc_html__('Invalid Nonce. Data can\'t be exported.', 'yet-another-stars-rating');

        $valid_nonce = YasrShortcodesAjax::validNonce($nonce, 'yasr-export-csv', $error_nonce);

        if($valid_nonce !== true) {
            echo $valid_nonce;
            die();
        }

        return true;
    }

    /**
     * Initialize $this->pdo, if it is null
     *
     * @author Dario Curvino <@dudo>
     *
     * @since  3.3.3
     * @return void
     */
    public function pdoConnect() {
        if ($this->pdo === null) {
            $this->pdo = YasrDB::PDOConnect();
        }
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