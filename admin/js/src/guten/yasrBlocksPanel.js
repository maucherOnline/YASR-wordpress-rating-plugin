/**
 * This is the panel that shows when a block is selected
 */

const {PanelBody}                        = wp.components;
const {InspectorControls}                = wp.blockEditor;

import {
    yasrLabelSelectSize,
    yasrLeaveThisBlankText,
    yasrOptionalText,
    yasrOverallDescription,
    yasrVisitorVotesDescription,
    YasrDivRatingOverall,
    YasrPrintInputId,
    YasrPrintSelectSize
} from "yasrGutenUtils";

/**
 * This is the panel that for blocks that use size and postid attributes
 *
 * @param props
 * @return {JSX.Element}
 */
export const YasrBlocksPanel = (props) => {
    const name = props.block;

    let bottomDesc;
    let overallRating;
    let blockSettings;
    if (name === 'yet-another-stars-rating/visitor-votes') {
        blockSettings = true;
        bottomDesc    = yasrVisitorVotesDescription;
    }
    if (name === 'yet-another-stars-rating/overall-rating') {
        overallRating = true;
        blockSettings = true;
        bottomDesc = yasrOverallDescription;
    }

    return (
        <InspectorControls>
            {
                //If the block selected is overall rating, call YasrDivRatingOverall
                overallRating === true && <YasrDivRatingOverall />
            }
            <PanelBody title='Settings'>
                {
                    //Return block settings if needed
                    blockSettings === true && <YasrPanelSizeAndId {...props} />
                }
                <div className="yasr-guten-block-panel">
                    {bottomDesc}
                </div>
            </PanelBody>
        </InspectorControls>
    );
}

/**
 * Return select size and input id
 *
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const YasrPanelSizeAndId = (props) => {
    const {block: size, setAttributes, postId} = props;

    const blockAttributes = {
        size:          size,
        postId:        postId,
        setAttributes: setAttributes
    }

    return (
        <>
            <h3>{yasrOptionalText}</h3>
            <YasrSelectSizeDiv   {...blockAttributes} />
            <YasrPrintInputIdDiv {...blockAttributes} />
        </>
    );
}

/**
 * Return the div to select the size of the block
 *
 * @param props
 * @returns {JSX.Element}
 */
const YasrSelectSizeDiv = (props) => {
    return (
        <div className="yasr-guten-block-panel">
            <label>{yasrLabelSelectSize}</label>
            <div>
                <YasrPrintSelectSize size={props.size} setAttributes={props.setAttributes} />
            </div>
        </div>
    )
}

/**
 *
 * @param props
 * @returns {JSX.Element}
 */
const YasrPrintInputIdDiv = (props) => {
    return (
        <div className="yasr-guten-block-panel">
            <label>Post ID</label>
            <YasrPrintInputId postId={props.postId} setAttributes={props.setAttributes} />
            <div className="yasr-guten-block-explain">
                Use return (&#8629;) to save.
            </div>
            <p>
                {yasrLeaveThisBlankText}
            </p>
        </div>
    )
}