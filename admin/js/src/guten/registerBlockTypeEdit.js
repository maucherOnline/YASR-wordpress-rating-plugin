const {Fragment}             = wp.element;
const {useBlockProps}        = wp.blockEditor;

import {
    YasrBlockOrderbyAttribute,
    YasrBlockPostidAttribute,
    YasrBlockSizeAttribute, YasrPrintSelectSize, YasrReturnShortcodeString,
    YasrSetBlockAttributes
} from "./yasrGutenUtils";

import {YasrBlocksPanel}     from "./yasrBlocksPanel";

/**
 * Return the edit Function to be used in registerBlockType
 *
 * @param props
 * @returns {JSX.Element}
 */
const yasrEditFunction = (props) => {
    const {attributes: {size, postId, orderby, sort, post_per_page}, name, isSelected, setAttributes} = props;

    const {className, shortCode, hookName, sizeAndId, orderPosts} = YasrSetBlockAttributes(name);

    const panelAttributes = {
        block:         name,
        size:          size,
        postId:        postId,
        orderBy:       orderby,
        sort:          sort,
        postPerPage:   post_per_page,
        setAttributes: setAttributes,
        hookName:      hookName,
        sizeAndId:     sizeAndId,
        orderPosts:    orderPosts
    }

    const blockProps = useBlockProps( {
        className: className,
        name:      name
    } );

    const shortcodeString = YasrReturnShortcodeString(size, 'edit', postId, shortCode, orderby);

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
