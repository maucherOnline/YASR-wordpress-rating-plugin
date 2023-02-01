const {Fragment}             = wp.element;
const {useBlockProps}        = wp.blockEditor;

import {
    YasrBlockPostidAttribute,
    YasrBlockSizeAttribute,
    YasrSetBlockAttributes
} from "./yasrGutenUtils";

import {YasrPrintSelectSize} from "./printReusableBlocksElements";

import {YasrBlocksPanel}     from "./yasrBlocksPanel";

/**
 * Return the edit Function to be used in registerBlockType
 *
 * @param props
 * @returns {JSX.Element}
 */
const yasrEditFunction = (props) => {
    const {attributes: {size, postId}, name, isSelected, setAttributes} = props;

    const {className, shortCode, hookName, sizeAndId, orderPosts} = YasrSetBlockAttributes(name);

    const postType = wp.data.select('core/editor').getCurrentPostType();

    const panelAttributes = {
        block:         name,
        size:          size,
        postId:        postId,
        setAttributes: setAttributes,
        hookName:      hookName,
        sizeAndId:     sizeAndId,
        orderPosts:    orderPosts
    }

    const blockProps = useBlockProps( {
        className: className,
        name:      name
    } );

    let sizeAttribute   = YasrBlockSizeAttribute(size, 'edit');
    let postIdAttribute = YasrBlockPostidAttribute(postId);

    //do de string only if values are not falsy
    let shortcodeString = `[${shortCode || ''}${sizeAttribute || ''}${postIdAttribute || ''}]`;

    //if shortcode is yasr_display_posts and postType is not page, change the string
    if(shortCode === 'yasr_display_posts' && postType !== 'page') {
        shortcodeString = 'This shortcode can be used only on pages';
    }

    return (
        <Fragment>
            {isSelected && <YasrBlocksPanel {...panelAttributes} /> }
            <div {...blockProps}>
                {shortcodeString}
                {isSelected && sizeAndId && <YasrPrintSelectSize size={size} setAttributes={setAttributes} />}
            </div>
        </Fragment>
    );
};



export default yasrEditFunction;
