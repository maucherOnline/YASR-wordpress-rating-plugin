const {useBlockProps}        = wp.blockEditor;

import {
    YasrBlockOrderbyAttribute,
    YasrBlockPostidAttribute,
    YasrBlockSizeAttribute,
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

    const postType = wp.data.select('core/editor').getCurrentPostType();

    //if shortcode is yasr_display_posts and postType is not page, change the string
    if(shortCode === 'yasr_display_posts' && postType !== 'page') {
        return;
    }

    let sizeAttribute   = YasrBlockSizeAttribute(size, 'save');
    let postIdAttribute = YasrBlockPostidAttribute(postId);

    //do de string only if values are not falsy
    let shortcodeString = `[${shortCode || ''}${sizeAttribute || ''}${postIdAttribute || ''}`;

    if(shortCode === 'yasr_display_posts') {
        let orderByAttribute = YasrBlockOrderbyAttribute(orderby);
        shortcodeString += `${orderByAttribute || ''}`;
    }

    shortcodeString += ']';

    return (
        //must no use spaces within vars here
        <div {...blockProps}>{shortcodeString}</div>
    );

};

export default yasrSaveFunction;
