const {useBlockProps}        = wp.blockEditor;

import {YasrBlockPostidAttribute, YasrBlockSizeAttribute, YasrSetBlockAttributes} from "./yasrGutenUtils";

/**
 * The Save function to use into registerBlockTypeSave
 *
 * @param props
 * @param metadata
 * @returns {JSX.Element}
 */
const yasrSaveFunction = (props, metadata) => {

    //get attributes size and postId
    const {attributes: {size, postId}} = props;

    //get attributes name from metadata
    const {name} = metadata;

    //get className and shortcode name
    const {className, shortCode} = YasrSetBlockAttributes(name);

    const blockProps = useBlockProps.save( {
        className: className,
    } );

    let sizeAttribute   = YasrBlockSizeAttribute(size, 'save');
    let postIdAttribute = YasrBlockPostidAttribute(postId);

    return (
        //must no use spaces within vars here
        <div {...blockProps}>[{shortCode}{sizeAttribute}{postIdAttribute}]</div>
    );

};

export default yasrSaveFunction;
