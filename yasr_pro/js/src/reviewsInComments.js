/****** Yasr User Reviews ******/
const yasr5StarsComments = document.getElementById('yasr-pro-five-stars-review');

//Show rater for the empty comment form
if (yasr5StarsComments !== null) {
    yasrURPrintEmptyFiveStars(yasr5StarsComments);
}

//If found in the dom, show ratings in the comments
const yasrRaterInCommentsRated = document.getElementsByClassName('yasr-rater-stars-in-comment-rated');

if (yasrRaterInCommentsRated.length > 0) {
    yasrUR5PrintFiveStars(yasrRaterInCommentsRated);

    const elems = document.getElementsByClassName('yasr-pro-new-input-comment-form');

    yasrURShowHideNewFieldsIfReply(elems);

    //get all links who allows to edit title
    const editTitleLinks = document.getElementsByClassName('yasr-pro-visitor-title-editable');
    if(editTitleLinks.length > 0) {
        yasrUREditTitle(editTitleLinks);
    }

    const yasrUpdateMultisetReview = document.getElementsByClassName('yasr-pro-update-review-multiset');

    if(yasrUpdateMultisetReview.length > 0) {
        yasrURUpdateMultiset(yasrUpdateMultisetReview);
    }

}

/**
 * If logged in, the author of the review can edit the title
 */
function yasrUREditTitle (editTitleLinks) {

    //Check in the object
    for (let i = 0; i < editTitleLinks.length; i++) {

        (function (i) {

            const commentId         = editTitleLinks.item(i).getAttribute('data-commentId');
            const nonce             = editTitleLinks.item(i).getAttribute('data-nonce-title');
            const editLink          = document.getElementById('yasr-pro-edit-visitor-title-'+commentId);
            const reviewTitle       = document.getElementById('yasr-pro-visitor-title-editable-'+commentId);
            const inputTitleSpan    = document.getElementById('yasr-pro-hidden-form-visitor-title-span-'+commentId);
            const inputTitle        = document.getElementById('yasr-pro-hidden-form-visitor-title-'+commentId);
            const updateLink        = document.getElementById('yasr-pro-update-visitor-title-'+commentId);

            //Update the title
            editLink.addEventListener('click', function(event) {
                event.preventDefault();

                reviewTitle.style.display = 'none';  //hide review title
                editLink.style.display    = 'none';  //hide edit link
                inputTitleSpan.style.display  = 'inline-block'; //show input text for new title


                //On click update
                updateLink.addEventListener('click', function(event) {
                    event.preventDefault();

                    const title = inputTitle.value;

                    const data = {
                        action: 'yasr_pro_update_comment_title',
                        commentId: commentId,
                        nonce: nonce,
                        title: title
                    };

                    //Send to the Server
                    jQuery.post(yasrWindowVar.ajaxurl, data, function(response) {
                        inputTitle.style.display = 'none';

                        let responseText;
                        response     = JSON.parse(response);
                        responseText = response.text;

                        jQuery('#yasr-pro-hidden-form-visitor-title-links-'+commentId).html(responseText);
                    });

                    return false;

                }); //End update title

                //Undo update the title
                jQuery('#yasr-pro-undo-title-rating-comment-'+commentId).on('click', function() {
                    inputTitleSpan.style.display  = 'none';   //hide input text for new title
                    reviewTitle.style.display     = 'inline-block';  //show review title
                    editLink.style.display        = 'inline-block';  //show edit link
                    return false;
                });

            });


        })(i);

    }

}

function yasrURShowHideNewFieldsIfReply (elems) {

    //On click on reply hide new input
    jQuery(document).on('click', 'a.comment-reply-link', function (event) {
        document.getElementById('yasr-pro-title-comment-form-review').style.display = 'none';
        for (var i = 0; i < elems.length; i++) {
            elems[i].style.display = 'none';
        }
    });

    //On click on delete reply show new input
    jQuery(document).on('click', 'a#cancel-comment-reply-link', function (event) {
        document.getElementById('yasr-pro-title-comment-form-review').style.display = '';
        for (var i = 0; i < elems.length; i++) {
            elems[i].style.display = '';
        }
    });

}


/**
 * Print an empty five stars rating into the comment form
 * Update hidden field used when the comment is posted
 *
 * @param yasrRaterInComments
 */
function yasrURPrintEmptyFiveStars (yasrRaterInComments) {
    let starSize = parseInt(yasrRaterInComments.getAttribute('data-rater-starsize'));

    const rateCallback = function (rating, done) {
        //Just leave 1 number after the .
        rating = parseInt(rating)

        this.setRating(rating);

        //updated hidden field
        document.getElementById('yasr-pro-visitor-review-rating').value=rating;

        done();
    }

    yasrSetRaterValue (starSize, yasrRaterInComments.id, false, 1, false, false, rateCallback);

}

/**
 * Print 5 stars in comment
 *
 * @param yasrRaterInCommentsRated
 */
function yasrUR5PrintFiveStars (yasrRaterInCommentsRated) {

    //Check in the object
    for (let i = 0; i < yasrRaterInCommentsRated.length; i++) {

        (function (i) {

            let htmlId     = yasrRaterInCommentsRated.item(i).id;
            let starSize   = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-starsize');
            let readonly   = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-readonly');
            let commentId  = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-commentid');
            let nonce      = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-nonce');
            let loaderHtml = document.getElementById('yasr-pro-loader-update-review-rating-' + commentId);

            readonly = yasrTrueFalseStringConvertion(readonly);

            starSize = parseInt(starSize);

            const rateCallback = function (rating, done) {
                //parse int
                rating = parseInt(rating);

                this.setRating(rating);

                loaderHtml.innerHTML = yasrWindowVar.loaderHtml;

                //Creating an object with data to send
                const data = {
                    action: 'yasr_pro_update_comment_rating',
                    commentId: commentId,
                    nonce: nonce,
                    rating: rating
                };

                jQuery.post(yasrWindowVar.ajaxurl, data, function (response) {
                    let responseText;
                    response = JSON.parse(response);
                    responseText = response.text;
                    loaderHtml.innerHTML = responseText;
                });

            }

            yasrSetRaterValue (starSize, htmlId, false, 1, readonly, false, rateCallback);

        })(i);

    } //End for

}

/**
 *
 * @param yasrUpdateMultisetReview
 */
function yasrURUpdateMultiset(yasrUpdateMultisetReview) {
    //Check in the object
    for (let i = 0; i < yasrUpdateMultisetReview.length; i++) {

        (function (i) {

            //yasr-star-rating is the class set by rater.js : so, if already exists,
            //means that rater already run for the element
            if(yasrUpdateMultisetReview.item(i).classList.contains('yasr-star-rating') !== false) {
                return;
            }

            const elem       = yasrUpdateMultisetReview.item(i);
            const htmlId     = elem.id;
            let starSize     = elem.getAttribute('data-rater-starsize');

            if(!starSize) {
                starSize = 16;
            }

            const rateCallback = function (rating, done) {

                const commentId  = elem.getAttribute('data-rater-commentid');
                const nonce      = elem.getAttribute('data-yasr-nonce');
                const setIdField = elem.getAttribute('data-rater-set-field-id');

                //Just leave 1 number after the .
                rating = rating.toFixed(1);
                //Be sure is a number and not a string
                const vote = parseInt(rating);

                this.setRating(vote); //set the new rating

                //Creating an object with data to send
                const data = {
                    action: 'yasr_pro_update_comment_multiset_rating',
                    comment_id: commentId,
                    field_id: setIdField,
                    nonce: nonce,
                    rating: rating
                };

                jQuery.post(yasrWindowVar.ajaxurl, data, function (response) {
                    let responseText;
                    response = JSON.parse(response);
                    responseText = response.text;
                    document.getElementById('yasr-pro-loader-update-multiset-rating-' + commentId + '-' + setIdField).innerText = responseText;
                });
            };

            yasrSetRaterValue (starSize, htmlId, elem, 1, false, false, rateCallback);

        })(i);
    }
}


/******End Yasr Pro reviews in comments ******/

