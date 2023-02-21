//get active Tab
import {getActiveTab} from "./yasr-admin-functions";

const activeTab = getActiveTab();

if (activeTab === 'yasr_csv_export') {

    const nonce       = document.getElementById('yasr_csv_nonce').value;
    const buttonVV    = document.getElementById('yasr-export-csv-visitor_votes');
    const answerDivVV = document.getElementById('yasr-export-vv-ajax-result');

    buttonVV.addEventListener('click', function () {

        answerDivVV.innerHTML = yasrWindowVar.loaderHtml;
        answerDivVV.innerHTML += '<span> Getting data, please wait</span>';

        const data = {
            action: 'yasr_export_csv_vv',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
        }).done ((response) => {
            response = yasrValidJson(response);
            if(response === false) {
                answerDivVV.innerHTML = yasrReturnErrorDiv('Not a valid Json Element');
                return;
            }
            if(response.status === 'error') {
                answerDivVV.innerHTML = yasrReturnErrorDiv(response.text);
                return;
            }

            //Print success
            answerDivVV.innerHTML = yasrReturnSuccessDiv(response.text);
        }).fail(function(response) {
            let error = `Error in ajax request, status code ${response.status}`;
            answerDivVV.innerHTML = yasrReturnErrorDiv(error);
        });
    });
}

/**
 * Return an error div
 * @param text
 * @returns {`<div class="notice notice-error" style="padding: 10px">${string}</div>`}
 */
const yasrReturnErrorDiv = (text) => {
    return `<div class="notice notice-error" style="padding: 10px">${text}</div>`;
}

/**
 * Return a success div
 * @param text
 * @returns {`<div class="notice notice-success" style="padding: 10px">${string}</div>`}
 */
const yasrReturnSuccessDiv = (text) => {
    return `<div class="notice notice-success" style="padding: 10px">${text}</div>`;
}