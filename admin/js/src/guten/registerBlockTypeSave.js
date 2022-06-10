import {YasrBlockPostidAttribute, YasrBlockSizeAttribute, YasrSetBlockAttributes} from "yasrGutenUtils";

const {useBlockProps}        = wp.blockEditor;

const yasrSaveFunction = (props, metadata) => {
    const {attributes: {size, postId}} = props;

    const {name} = metadata;

    const {className, shortCode} = YasrSetBlockAttributes(name);

    const blockProps = useBlockProps.save( {
        className: className,
    } );

    let sizeAttribute   = YasrBlockSizeAttribute(size, 'save');
    let postIdAttribute = YasrBlockPostidAttribute(postId);

    return (
        <div {...blockProps}>[{shortCode}{sizeAttribute}{postIdAttribute}]</div>
    );

};

export default yasrSaveFunction;
