//get active Tab
import {getActiveTab} from "./yasr-admin-functions";

const activeTab = getActiveTab();

if (activeTab === 'yasr_csv_export') {
    const nonce     = document.getElementById('yasr_csv_nonce').value;
    const buttonVV  = document.getElementById('yasr-export-csv-visitor_votes');
    const answerDiv = document.getElementById('yasr-export-vv-ajax-result');

    buttonVV.addEventListener('click', function () {
        answerDiv.innerHTML = yasrWindowVar.loaderHtml;
        answerDiv.innerHTML += '<span> Getting data, please wait</span>';

        const data = {
            action: 'yasr_export_csv_vv',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            response = yasrValidJson(response);
            if(response === false) {
                answerDiv.innerHTML = 'Not a valid Json Element';
                return;
            }
            if(response.status === 'error') {
                answerDiv.innerHTML = yasrReturnErrorDiv(response.text);
                return;
            }

            //Print success
            answerDiv.innerHTML = yasrReturnSuccessDiv(response.text);
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