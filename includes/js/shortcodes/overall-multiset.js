/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./includes/js/src/shortcodes/overall-multiset.js":
/*!********************************************************!*\
  !*** ./includes/js/src/shortcodes/overall-multiset.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"yasrSearchStarsDom\": () => (/* binding */ yasrSearchStarsDom)\n/* harmony export */ });\nconst arrayClasses = ['yasr-rater-stars', 'yasr-multiset-visitors-rater'];\n\n/*** Constant used by yasr\n yasrWindowVar (ajaxurl,  isrtl)\n***/\n\nfor (let i=0; i<arrayClasses.length; i++) {\n    //Search and set all div with class yasr-multiset-visitors-rater\n    yasrSearchStarsDom(arrayClasses[i]);\n}\n\n/**\n * Search for divs with defined classname\n */\nfunction yasrSearchStarsDom (starsClass) {\n    //At pageload, check if there is some shortcode with class yasr-rater-stars\n    const yasrRaterInDom = document.getElementsByClassName(starsClass);\n    //If so, call the function to set the rating\n    if (yasrRaterInDom.length > 0) {\n        //stars class for most shortcodes\n        if(starsClass === 'yasr-rater-stars') {\n            yasrSetRating(yasrRaterInDom);\n        }\n\n        if (starsClass === 'yasr-multiset-visitors-rater') {\n            yasrRaterVisitorsMultiSet(yasrRaterInDom)\n        }\n    }\n}\n\nfunction yasrSetRating (yasrRatingsInDom) {\n\n    //Check in the object\n    for (let i = 0; i < yasrRatingsInDom.length; i++) {\n        //yasr-star-rating is the class set by rater.js : so, if already exists,\n        //means that rater already run for the element\n        if(yasrRatingsInDom.item(i).classList.contains('yasr-star-rating') === false) {\n            const element  = yasrRatingsInDom.item(i);\n            const htmlId   = element.id;\n            const starSize = element.getAttribute('data-rater-starsize');\n            yasrSetRaterValue(starSize, htmlId, element);\n        }\n    }\n\n}\n\nfunction yasrRaterVisitorsMultiSet (yasrMultiSetVisitorInDom) {\n    const visitorMultiSubmitButtons = document.querySelectorAll('.yasr-send-visitor-multiset');\n\n    //will have field id and vote\n    var ratingObject = \"\";\n\n    //an array with all the ratings objects\n    var ratingArray = [];\n\n    const hiddenFieldMultiReview = document.getElementById('yasr-pro-multiset-review-rating');\n\n    //Check in the object\n    for (let i = 0; i < yasrMultiSetVisitorInDom.length; i++) {\n        (function (i) {\n            //yasr-star-rating is the class set by rater.js : so, if already exists,\n            //means that rater already run for the element\n            if(yasrMultiSetVisitorInDom.item(i).classList.contains('yasr-star-rating') !== false) {\n                return;\n            }\n\n            let elem       = yasrMultiSetVisitorInDom.item(i);\n            let htmlId     = elem.id;\n            let readonly   = elem.getAttribute('data-rater-readonly');\n            let starSize   = elem.getAttribute('data-rater-starsize');\n            if(!starSize) {\n                starSize = 16;\n            }\n\n            readonly = yasrTrueFalseStringConvertion(readonly);\n\n            const rateCallback = function (rating, done) {\n                const postId     = elem.getAttribute('data-rater-postid');\n                const setId      = elem.getAttribute('data-rater-setid');\n                const setIdField = elem.getAttribute('data-rater-set-field-id');\n\n                //Just leave 1 number after the .\n                rating = rating.toFixed(1);\n                //Be sure is a number and not a string\n                const vote = parseInt(rating);\n\n                this.setRating(vote); //set the new rating\n\n                ratingObject = {\n                    postid: postId,\n                    setid: setId,\n                    field: setIdField,\n                    rating: vote\n                };\n\n                //creating rating array\n                ratingArray.push(ratingObject);\n\n                if(hiddenFieldMultiReview) {\n                    hiddenFieldMultiReview.value = JSON.stringify(ratingArray);\n                }\n\n                done();\n\n            }\n\n            yasrSetRaterValue (starSize, htmlId, elem, 1, readonly, false, rateCallback);\n\n        })(i);\n\n    }\n\n    //add an event listener for each submit button\n    visitorMultiSubmitButtons.forEach(function(element) {\n        element.addEventListener('click', function () {\n\n            const multiSetPostId = this.getAttribute('data-postid');\n            const multiSetId     = this.getAttribute('data-setid');\n            const nonce          = this.getAttribute('data-nonce');\n            const submitButton   = document.getElementById(`yasr-send-visitor-multiset-${multiSetPostId}-${multiSetId}`);\n            const loader         = document.getElementById(`yasr-loader-multiset-visitor-${multiSetPostId}-${multiSetId}`)\n\n            submitButton.style.display = 'none';\n            loader.style.display       = 'block';\n\n            const isUserLoggedIn = JSON.parse(yasrWindowVar.isUserLoggedIn);\n\n            const data = {\n                action: 'yasr_visitor_multiset_field_vote',\n                post_id: multiSetPostId,\n                rating: ratingArray,\n                set_id: multiSetId\n            };\n\n            if (isUserLoggedIn === true) {\n                Object.assign(data, {nonce: nonce});\n            }\n\n            //Send value to the Server\n            jQuery.post(yasrWindowVar.ajaxurl, data).done(\n                function (response) {\n                    let responseText;\n                    response = JSON.parse(response);\n                    responseText = response.text\n\n                    loader.innerText=responseText;\n                }).fail(\n                function (e, x, settings, exception) {\n                    console.error('YASR ajax call failed. Can\\'t save data');\n                    console.log(e);\n                });\n\n        })\n    });\n    \n} //End function\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/js/src/shortcodes/overall-multiset.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./includes/js/src/shortcodes/overall-multiset.js"](0, __webpack_exports__, __webpack_require__);
/******/ 	
/******/ })()
;