//get active Tab
import {getActiveTab} from "./yasr-admin-functions";

const activeTab = getActiveTab();

if (activeTab === 'yasr_csv_export') {
    const nonce    = document.getElementById('yasr_csv_nonce').value;
    const buttonVV = document.getElementById('yasr-export-csv-visitor_votes');

    buttonVV.addEventListener('click', function () {
        console.log(nonce);

        const data = {
            action: 'yasr_export_csv',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            console.log(response);
        });
    });
}