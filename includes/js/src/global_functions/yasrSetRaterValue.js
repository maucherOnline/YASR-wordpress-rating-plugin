//this is the function that print the overall rating shortcode, get overall rating and starsize

/**
 *
 * @param {number} starSize
 * @param {string} htmlId
 * @param {boolean | HTMLElement} element
 * @param {number} step
 * @param {boolean} readonly
 * @param {boolean | number} rating
 * @param {boolean | callback} rateCallback
 */
window.yasrSetRaterValue = function (starSize,
                                   htmlId,
                                   element=false,
                                   step=0.1,
                                   readonly=true,
                                   rating=false,
                                   rateCallback=false
                                   ) {
    let domElement;
    if(element) {
        domElement = element;
    } else {
        domElement = document.getElementById(htmlId)
    }

    //convert to be a number
    starSize = parseInt(starSize);

    raterJs({
        starSize: starSize,
        showToolTip: false,
        element: domElement,
        step: step,
        readOnly: readonly,
        rating: rating,
        rateCallback: rateCallback
    });

}