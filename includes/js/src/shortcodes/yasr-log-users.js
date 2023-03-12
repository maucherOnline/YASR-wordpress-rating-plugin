//Vote user log
//jQuery(document).ready(function () {
    const paginationButtons = document.getElementById('yasr-user-log-page-navigation-buttons');
    const totalPages        = document.getElementById('yasr-user-log-total-pages').dataset.yasrLogTotalPages;

    let rowTitle = []; //array containing all the DOM containers for the title
    let rowDate  = []; //array containing all the DOM containers for the dates

    for (let i=0; i < 8; i++) {
        rowTitle[i] = document.getElementById(`yasr-user-log-post-${i}`);
        rowDate[i]  = document.getElementById(`yasr-user-log-date-${i}`);
    }

    //Log
    jQuery('.yasr-user-log-page-num').on('click', function () {
        const pagenum = parseInt(this.value);
        yasrUpdateLogUsersPagination(pagenum);
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
                const pagenum = parseInt(this.value);
                yasrUpdateLogUsersPagination(pagenum);
                yasrPostDataLogUsers(pagenum);
            });

        }
    });

/**
 *  Update the pagination
 * @param pagenum
 * @returns {string}
 */
function yasrUpdateLogUsersPagination (pagenum) {
        //cast to int
        pagenum = parseInt(pagenum);

        let newPagination = '';
        if (pagenum >= 3 && totalPages > 3) {
            newPagination += `<button class="yasr-user-log-page-num" value="1">
                &laquo; First </button>&nbsp;&nbsp;...&nbsp;&nbsp;`
        }

        let startFor = pagenum - 1;

        if (startFor <= 0) {
            startFor = 1;
        }

        let endFor = pagenum + 1;

        if (endFor >= totalPages) {
            endFor = totalPages;
        }

        for (let i = startFor; i <= endFor; i++) {
            if (i === pagenum) {
                newPagination += `<button class="button-primary" value="${i}">${i}</button>&nbsp;&nbsp;`;
            } else {
                newPagination += `<button class="yasr-user-log-page-num" value="${i}">${i}</button>&nbsp;&nbsp;`;
            }
        }

        if (totalPages > 3 && pagenum < totalPages) {
            newPagination += `...&nbsp;&nbsp;
                <button class="yasr-user-log-page-num" value="${totalPages}"> Last &raquo;</button>&nbsp;&nbsp;`;
        }

        return paginationButtons.innerHTML = newPagination;
    }

    /**
     *  Show / hide the loader, and call the ajax action yasr_change_user_log_page_front
     */
    function yasrPostDataLogUsers (pagenum) {
        const loader = document.getElementById('yasr-loader-user-log-metabox');

        //show the loader
        loader.style.display = 'inline';

        let data = {
            action: 'yasr_change_user_log_page_front',
            pagenum: pagenum,
            totalpages: totalPages
        };

        jQuery.post(yasrWindowVar.ajaxurl, data, function (response) {
            let title;
            for (let i=0; i < 8; i++) {
                if (response.data[i]) {
                    title = `<a href="${response.data[i].permalink}">${response.data[i].post_title}</a>`
                    //update the title
                    rowTitle[i].innerHTML = title;
                    //update the date
                    rowDate[i].innerText = response.data[i].date
                }
            }
            //hide the loader
            loader.style.display = 'none';
        });
    }

//});


