const {Fragment}             = wp.element;
const {useBlockProps}        = wp.blockEditor;

import {
    YasrBlockPostidAttribute,
    YasrBlockSizeAttribute,
    YasrPrintSelectSize,
    YasrSetBlockAttributes
} from "./yasrGutenUtils";

import {YasrBlocksPanel} from "./yasrBlocksPanel";

/**
 * Return the edit Function to be used in registerBlockType
 *
 * @param props
 * @returns {JSX.Element}
 */
const yasrEditFunction = (props) => {
    const {attributes: {size, postId}, name, isSelected, setAttributes} = props;

    const {className, shortCode, hookName, sizeAndId} = YasrSetBlockAttributes(name);

    const panelAttributes = {
        block:         name,
        size:          size,
        postId:        postId,
        setAttributes: setAttributes,
        hookName:      hookName,
        sizeAndId:     sizeAndId
    }

    const blockProps = useBlockProps( {
        className: className,
        name:      name
    } );

    let sizeAttribute   = YasrBlockSizeAttribute(size, 'edit');
    let postIdAttribute = YasrBlockPostidAttribute(postId);

    return (
        <Fragment>
            {isSelected && <YasrBlocksPanel {...panelAttributes} /> }
            <div {...blockProps}>
                [{shortCode}{sizeAttribute}{postIdAttribute}]
                {isSelected && sizeAndId && <YasrPrintSelectSize size={size} setAttributes={setAttributes} />}
            </div>
        </Fragment>
    );
};

export default yasrEditFunction;
