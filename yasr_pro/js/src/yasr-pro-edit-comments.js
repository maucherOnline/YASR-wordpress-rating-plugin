yasrPrintStarsEditComment();

/**
 * Print the stars for the comment edit list (wp-admin/edit-comments.php)
 *
 * @return void;
 */
function yasrPrintStarsEditComment() {

    //Convert string to number
    let yasrCommentRatinginDom = document.getElementsByClassName('yasr-rater-star-comment');


    //Check in the object
    for (let i = 0; i < yasrCommentRatinginDom.length; i++) {
        let htmlId = yasrCommentRatinginDom.item(i).id;

        yasrSetRaterValue(
            16,
            htmlId,
            false,
            0.1,
            true
        )
    }
}