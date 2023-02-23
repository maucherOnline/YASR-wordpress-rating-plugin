import {yasrReturnErrorDiv, yasrReturnSuccessDiv} from "../../../admin/js/src/yasr-admin-functions";

/**
 * Function to hook at yasr_stats_page_csv
 */
const yasrExportData = () => {

    const nonce       = document.getElementById('yasr_csv_nonce').value;
    const buttonVV    = document.getElementById('yasr-export-csv-visitor_votes');
    const buttonMV    = document.getElementById('yasr-export-csv-visitor_multiset');
    const answerDivVV = document.getElementById('yasr-export-ajax-result-visitor_votes');
    const answerDivMV = document.getElementById('yasr-export-ajax-result-visitor_multiset');

    //nonce is the same for all buttons
    let data = {
        nonce: nonce
    }

    //event on click when the export for yasr_visitor_votes is clicked
    buttonVV.addEventListener('click', function () {
        data.action = 'yasr_export_csv_vv';
        yasrExportCsvPost(data, answerDivVV)
    });

    //event on click when the export for visitor multi is clicked
    buttonMV.addEventListener('click', function () {
        data.action = 'yasr_export_csv_mv';
        yasrExportCsvPost(data, answerDivMV)
    });

    /**
     *
     *
     * @param data       | an object with nonce and action
     * @param answerDiv  | the div where to print results
     */
    const yasrExportCsvPost = (data, answerDiv) => {
        answerDiv.innerHTML = yasrWindowVar.loaderHtml;
        answerDiv.innerHTML += '<span> Getting data, please wait</span>';

        jQuery.post(ajaxurl, data, function (response) {
        }).done((response) => {
            response = yasrValidJson(response);
            if (response === false) {
                answerDiv.innerHTML = yasrReturnErrorDiv('Not a valid Json Element');
                return;
            }
            if (response.status === 'error') {
                answerDiv.innerHTML = yasrReturnErrorDiv(response.text);
                return;
            }

            //Print success
            answerDiv.innerHTML = yasrReturnSuccessDiv(response.text);
        }).fail(function (response) {
            let error = `Error in ajax request, status code ${response.status}`;
            answerDiv.innerHTML = yasrReturnErrorDiv(error);
        });
    }

}

wp.hooks.addAction( 'yasr_stats_page_csv', 'yasr', yasrExportData);
