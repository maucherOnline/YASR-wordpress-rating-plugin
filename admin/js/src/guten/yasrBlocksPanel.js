/**
 * This is the panel that shows when a block is selected
 */
const {PanelBody}                        = wp.components;
const {InspectorControls}                = wp.blockEditor;

import {
    yasrLabelSelectSize,
    yasrLeaveThisBlankText,
    yasrOptionalText,
    YasrDivRatingOverall,
    YasrPrintInputId,
    YasrPrintSelectSize,
    YasrNoSettingsPanel,
    YasrSetBlockAttributes
} from "./yasrGutenUtils";

/**
 * This is the panel that for blocks that use size and postid attributes
 *
 * @param props
 * @return {JSX.Element}
 */
export const YasrBlocksPanel = (props) => {
    const {block: name, hookName, sizeAndId} = props;

    const {overallRating, panelSettings, bottomDesc} = YasrSetBlockAttributes(name);

    //Create an empty element to hook into
    let hookedDiv = <></>;

    //if an hook name exists, wp.hooks.doAction
    if(hookName !== false) {
        hookedDiv = [<YasrNoSettingsPanel key={0}/>];
        {wp.hooks.doAction(hookName, hookedDiv)}
    }

    //if there is no hook and settings are not true, return an empty element
    if(panelSettings !== true && hookName === false) {
        return <></>;
    }

    return (
        <InspectorControls>
            {
                //If the block selected is overall rating, call YasrDivRatingOverall
                overallRating && <YasrDivRatingOverall />
            }
            <PanelBody title='Settings'>
                {hookedDiv}
                {
                    //Return block settings if needed
                    panelSettings && sizeAndId && <YasrPanelSizeAndId {...props} />
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