const yasrPaginationButtonsUser  = document.getElementById('yasr-user-log-page-navigation-buttons');
const yasrPaginationButtonsAdmin = document.getElementById('yasr-admin-log-page-navigation-buttons');

if(yasrPaginationButtonsUser) {
    yasrLogWidget();
}
if(yasrPaginationButtonsAdmin) {
    yasrLogWidget('yasr-admin');
}

function yasrLogWidget(prefix='yasr-user') {
    let isAdminWidget = false;
    if (prefix === 'yasr-admin') {
        isAdminWidget = true;
    }

    const totalPages = document.getElementById(`${prefix}-log-total-pages`).dataset.yasrLogTotalPages;

    const ajaxAction = `${prefix}_change_log_page`;
    const nonce      = document.getElementById(`${prefix}-log-nonce-page`).value;

    let rowContainer = []; //array containing all the DOM containers of the rows
    let spanVote     = [];
    let rowTitle     = []; //array containing all the DOM containers for the title
    let rowDate      = []; //array containing all the DOM containers for the dates

    let userNameSpan = false;
    let avatar       = false
    if(isAdminWidget === true) {
        userNameSpan = [];
        avatar       = [];
    }

    for (let i = 0; i < 8; i++) {
        rowContainer[i] = document.getElementById(`${prefix}-log-div-child-${i}`);
        spanVote[i]     = document.getElementById(`${prefix}-log-vote-${i}`);
        rowTitle[i]     = document.getElementById(`${prefix}-log-post-${i}`);
        rowDate[i]      = document.getElementById(`${prefix}-log-date-${i}`);

        if(isAdminWidget === true) {
            userNameSpan[i] = document.getElementById(`${prefix}-log-user-${i}`);
            avatar[i]       = document.getElementById(`${prefix}-log-avatar-${i}`);
        }
    }

    jQuery(`.${prefix}-log-page-num`).on('click', function () {
        const pagenum = parseInt(this.value);
        yasrUpdateLogUsersPagination(pagenum, totalPages, prefix);
        yasrPostDataLogUsers(pagenum, rowContainer, spanVote, rowTitle, rowDate, totalPages, userNameSpan,
            avatar, prefix, ajaxAction, nonce);
    });

    jQuery(document).ajaxComplete(function (event, xhr, settings) {
        let isYasrAjaxCall = true;

        if (typeof settings.data === 'undefined') {
            return;
        }

        //check if the ajax call is done by yasr with action yasr_change_log_page
        isYasrAjaxCall = settings.data.search(`action=${ajaxAction}`);

        if (isYasrAjaxCall !== -1) {
            jQuery(`.${prefix}-log-page-num`).on('click', function () {
                const pagenum = parseInt(this.value);
                yasrUpdateLogUsersPagination(pagenum, totalPages, prefix);
                yasrPostDataLogUsers(pagenum, rowContainer, spanVote, rowTitle, rowDate, totalPages,
                    userNameSpan, avatar, prefix, ajaxAction, nonce);
            });

        }
    });
}

/**
 *  Update the pagination
 * @param pagenum
 * @param totalPages
 * @param prefix
 * @returns {string}
 */
function yasrUpdateLogUsersPagination (pagenum, totalPages, prefix) {
    //cast to int
    pagenum = parseInt(pagenum);

    let newPagination = '';
    if (pagenum >= 3 && totalPages > 3) {
        newPagination += `<button class="${prefix}-log-page-num" value="1">
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
            newPagination += `<button class="${prefix}-log-page-num" value="${i}">${i}</button>&nbsp;&nbsp;`;
        }
    }

    if (totalPages > 3 && pagenum < totalPages) {
        newPagination += `...&nbsp;&nbsp;
            <button class="${prefix}-log-page-num" value="${totalPages}"> Last &raquo;</button>&nbsp;&nbsp;`;
    }

    if(prefix === 'yasr-admin') {
        return yasrPaginationButtonsAdmin.innerHTML = newPagination;
    }

    return yasrPaginationButtonsUser.innerHTML = newPagination;
}

/**
 *
 * Show / hide the loader, and call the ajax action
 *
 * @param pagenum
 * @param rowContainer
 * @param spanVote
 * @param rowTitle
 * @param rowDate
 * @param totalPages
 * @param userNameSpan
 * @param avatar
 * @param prefix
 * @param ajaxAction
 * @param nonce
 */
function yasrPostDataLogUsers(pagenum, rowContainer, spanVote, rowTitle, rowDate, totalPages, userNameSpan, avatar,
                              prefix, ajaxAction, nonce) {

    const loader = document.getElementById(`${prefix}-log-loader-metabox`);

    //show the loader
    loader.style.display = 'inline';

    let data = {
        action: ajaxAction,
        pagenum: pagenum,
        totalpages: totalPages,
        yasr_user_log_nonce: nonce
    };

    jQuery.post(yasrWindowVar.ajaxurl, data, function (response) {
        let title;
        for (let i=0; i < 8; i++) {
            if (response.data[i]) {
                rowContainer[i].style.display = 'block';
                spanVote[i].innerText = parseInt(response.data[i].vote);

                if(Array.isArray(userNameSpan)) {
                    userNameSpan[i].innerText = response.data[i].user_nicename;
                }
                if(Array.isArray(avatar)) {
                    avatar[i].src = response.data[i].avatar_url;
                }

                title = `<a href="${response.data[i].permalink}">${response.data[i].post_title}</a>`
                //update the title
                rowTitle[i].innerHTML = title;
                //update the date
                rowDate[i].innerText = response.data[i].date
            } else {
                rowContainer[i].style.display = 'none';
            }
        }
        //hide the loader
        loader.style.display = 'none';
    });
}