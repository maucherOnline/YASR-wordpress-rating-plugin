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
        add_filter('yasr_export_box_button',     array($this, 'replaceButton'), 10, 3);

        add_action('yasr_export_box_end',        array($this, 'createLinks'));

        add_action('wp_ajax_yasr_export_csv_vv', array($this, 'returnVisitorVotesData'));

        add_action('wp_ajax_yasr_export_csv_mv', array($this, 'returnVisitorMultiData'));

        add_action('wp_ajax_yasr_export_csv_ov', array($this, 'returnOverallRatingData'));

        //keep this here, so we can have a wp_die immediately if unable to connect
        $this->pdoConnect();
    }

    /**
     * Replace the export button with a working button
     *
     * @author Dario Curvino <@dudo>
     *
     * @since 3.3.3
     *
     * @param $button
     * @param $button_id
     * @param $button_disabled
     *
     * @return string
     */
    public function replaceButton($button, $button_id, $button_disabled) {
        return  '<button class="button-primary" id="'.esc_attr($button_id).'"'. esc_attr($button_disabled).'>'.
                    esc_html__( 'Export Data', 'yet-another-stars-rating' ).'
                 </button>';
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