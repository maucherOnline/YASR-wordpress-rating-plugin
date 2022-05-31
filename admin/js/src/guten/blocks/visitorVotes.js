const {registerBlockType}          = wp.blocks; // Import from wp.blocks
const {Fragment}                   = wp.element;
const {useBlockProps}              = wp.blockEditor;

import {
    YasrBlocksPanel,
    YasrPrintSelectSize,
    YasrBlockSizeAttribute,
    YasrBlockPostidAttribute
} from "yasrGutenUtils";

import metadata from '../../../../../includes/blocks/visitor-votes/block.json';

registerBlockType(
    metadata, {
        edit:
            function( props ) {
                const blockProps = useBlockProps({
                    className: 'yasr-vv-block',
                });

                const { attributes: { size, postId }, setAttributes, isSelected } = props;

                const panelAttributes = {
                    block: 'visitors',
                    size  : size,
                    postId: postId,
                    setAttributes: setAttributes
                }

                let sizeAttribute   = YasrBlockSizeAttribute(size);
                let postIdAttribute = YasrBlockPostidAttribute(postId);

                return (
                    <Fragment>
                        <YasrBlocksPanel {...panelAttributes} />
                        <div {...blockProps}>
                            [yasr_visitor_votes{sizeAttribute}{postIdAttribute}]
                            {isSelected && <YasrPrintSelectSize size={size} setAttributes={setAttributes}/>}
                        </div>
                    </Fragment>
                );

            },

        /**
         * The save function defines the way in which the different attributes should be combined
         * into the final markup, which is then serialized by Gutenberg into post_content.
         *
         * The "save" property must be specified and must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */
        save:
            function( props ) {
                const blockProps = useBlockProps.save( {
                    className: 'yasr-vv-block',
                } );
                const { attributes: {size, postId} } = props;

                let yasrVVAttributes = '';

                if (size) {
                    yasrVVAttributes += 'size="' +size+ '"';
                }
                if (postId) {
                    yasrVVAttributes += ' postid="'+postId+'"';
                }

                return (
                    <div {...blockProps}>[yasr_visitor_votes {yasrVVAttributes}]</div>
                );
            },

    });