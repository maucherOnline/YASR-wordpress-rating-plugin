//Vote user log
jQuery(document).ready(function () {
    const totalPages = document.getElementById('yasr-user-log-total-pages').dataset.yasrLogTotalPages;

    //Log
    jQuery('.yasr-user-log-page-num').on('click', function () {
        const pagenum = this.value;
        yasrPostDataLogUsers(pagenum);
    });

    jQuery(document).ajaxComplete(function (event, xhr, settings) {
        let isYasrAjaxCall = true;

        if (typeof settings.data === 'undefined') {
            return;
        }

        //check if the ajax call is done by yasr with action yasr_change_log_page
        isYasrAjaxCall = settings.data.search("action=yasr_change_user_log_page_front");

        if (isYasrAjaxCall !== -1) {
            jQuery('.yasr-user-log-page-num').on('click', function () {
                const pagenum = this.value;
                yasrPostDataLogUsers(pagenum);
            });

        }
    });

    /**
     *  Show / hide the loader, and call the action yasr_change_user_log_page_front
     */
    function yasrPostDataLogUsers (pagenum) {
        const loader = document.getElementById('yasr-loader-user-log-metabox');

        loader.style.display = 'inline';

        let data = {
            action: 'yasr_change_user_log_page_front',
            pagenum: pagenum,
            totalpages: totalPages
        };

        jQuery.post(yasrWindowVar.ajaxurl, data, function (response) {
            jQuery('#yasr-user-log-container').html(response); //This will hide the loader gif too
        });
    }

});


