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
    YasrPrintSelectSize, YasrNoSettingsPanel
} from "yasrGutenUtils";

/**
 * This is the panel that for blocks that use size and postid attributes
 *
 * @param props
 * @return {JSX.Element}
 */
export const YasrBlocksPanel = (props) => {
    const {block: name, hookName} = props;

    const {overallRating, blockSettings, bottomDesc} = YasrPanelAttributes(name);

    //Create an empty element to hook into
    let hookedDiv = <></>;

    //if an hook name exists, wp.hooks.doAction
    if(hookName !== false) {
        hookedDiv = [<YasrNoSettingsPanel key={0}/>];
        {wp.hooks.doAction(hookName, hookedDiv)}
    }

    return (
        <InspectorControls>
            {
                //If the block selected is overall rating, call YasrDivRatingOverall
                overallRating === true && <YasrDivRatingOverall />
            }
            <PanelBody title='Settings'>
                {hookedDiv}
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
 * Based on the name of the block return an object with the Attributes of the panel
 *
 * @param name
 * @returns {{bottomDesc: boolean, blockSettings: boolean, overallRating: boolean}}
 * @constructor
 */
const YasrPanelAttributes = (name) => {

    let panelAttributes = {
        bottomDesc: false,
        overallRating: false,
        blockSettings: false
    }

    if (name === 'yet-another-stars-rating/visitor-votes') {
        panelAttributes.blockSettings = true;
        panelAttributes.bottomDesc    = yasrVisitorVotesDescription;
    }
    if (name === 'yet-another-stars-rating/overall-rating') {
        panelAttributes.overallRating = true;
        panelAttributes.blockSettings = true;
        panelAttributes.bottomDesc = yasrOverallDescription;
    }

    return panelAttributes;
}

/**
 * Return select size and input id
 *
 * @param props
 * @returns {JSX.Element}
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