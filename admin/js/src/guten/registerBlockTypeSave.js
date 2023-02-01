const {useBlockProps}        = wp.blockEditor;

import {
    YasrBlockOrderbyAttribute,
    YasrBlockPostidAttribute,
    YasrBlockSizeAttribute, YasrReturnShortcodeString,
    YasrSetBlockAttributes
} from "./yasrGutenUtils";

/**
 * The Save function to use into registerBlockTypeSave
 *
 * @param props
 * @param metadata
 * @returns void | {JSX.Element}
 */
const yasrSaveFunction = (props, metadata) => {

    //get attributes size and postId
    const {attributes: {size, postId, orderby}} = props;

    //get attributes name from metadata
    const {name} = metadata;

    //get className and shortcode name
    const {className, shortCode} = YasrSetBlockAttributes(name);

    const blockProps = useBlockProps.save( {
        className: className,
    } );

    const shortcodeString = YasrReturnShortcodeString(size, 'save', postId, shortCode, orderby);

    return (
        <div {...blockProps}>{shortcodeString}</div>
    );

};

export default yasrSaveFunction;
