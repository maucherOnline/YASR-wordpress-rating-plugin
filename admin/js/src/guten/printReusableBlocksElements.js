/**
 * This file contains all the html elements that can be used in both panel or inside the block itself
 */

import {
    yasrSelectSizeChoose,
    yasrSelectSizeLarge,
    yasrSelectSizeMedium,
    yasrSelectSizeSmall
} from "./yasrGutenUtils";

/**
 * Print the text field to insert the input id, and manage the event
 *
 * @param props
 * @returns {JSX.Element}
 */
export const YasrPrintInputId = (props) => {
    let postId;
    if (props.postId !== false) {
        postId = props.postId;
    }

    const yasrSetPostId = (setAttributes, event) => {
        if (event.key === 'Enter') {
            const postIdValue = event.target.value;

            //postID is always a string, here I check if this string is made only by digits
            let isNum = /^\d+$/.test(postIdValue);

            if (isNum === true || postIdValue === '') {
                setAttributes({postId: postIdValue})
            }
            event.preventDefault();
        }
    }

    return (
        <div>
            <input
                type="text"
                size="4"
                defaultValue={postId}
                onKeyPress={(e) => yasrSetPostId(props.setAttributes, e)}/>
        </div>
    );
}
/**
 * This is just the select, used both in blocks panel and block itself
 *
 * @param props
 * @returns {JSX.Element}
 */
export const YasrPrintSelectSize = (props) => {
    const yasrSetStarsSize = (setAttributes, event) => {
        const selected = event.target.querySelector('option:checked');
        setAttributes({size: selected.value});
        event.preventDefault();
    }

    return (
        <form>
            <select value={props.size} onChange={(e) => yasrSetStarsSize(props.setAttributes, e)}>
                <option value="--">{yasrSelectSizeChoose}    </option>
                <option value="small">{yasrSelectSizeSmall}  </option>
                <option value="medium">{yasrSelectSizeMedium}</option>
                <option value="large">{yasrSelectSizeLarge}  </option>
            </select>
        </form>
    );
}
