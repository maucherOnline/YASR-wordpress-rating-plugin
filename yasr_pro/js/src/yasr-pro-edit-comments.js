yasrPrintStarsEditComment();

/**
 * Print the stars for the comment edit list
 *
 * @return void;
 */
function yasrPrintStarsEditComment() {

    //Convert string to number
    let yasrCommentRatinginDom = document.getElementsByClassName('yasr-rater-star-comment');


    //Check in the object
    for (let i = 0; i < yasrCommentRatinginDom.length; i++) {
        let htmlId = yasrCommentRatinginDom.item(i).id;

        raterJs({
            starSize: 16,
            step: 0.1,
            showToolTip: false,
            readOnly: true,
            element: document.getElementById(htmlId),
        });
    }
}