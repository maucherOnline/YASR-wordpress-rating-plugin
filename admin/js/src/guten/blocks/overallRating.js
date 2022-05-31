const {registerBlockType}    = wp.blocks; // Import from wp.blocks
const {Fragment}             = wp.element;
const {useBlockProps}        = wp.blockEditor;

import {
    YasrBlocksPanel,
    YasrPrintSelectSize,
    YasrBlockSizeAttribute,
    YasrBlockPostidAttribute
} from "yasrGutenUtils";

/**
 * Register: a Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

registerBlockType(
    'yet-another-stars-rating/overall-rating', {
        edit:
            function(props) {
                const blockProps = useBlockProps( {
                    className: 'yasr-overall-block',
                } );

                const {attributes: {size, postId}, setAttributes, isSelected} = props;

                const panelAttributes = {
                    block: 'overall',
                    size  : size,
                    postId: postId,
                    setAttributes: setAttributes
                }

                let sizeAttribute   = YasrBlockSizeAttribute(size);
                let postIdAttribute = YasrBlockPostidAttribute(postId);

                return (
                    <Fragment>
                        {isSelected && <YasrBlocksPanel {...panelAttributes} /> }
                        <div { ...blockProps }>
                            [yasr_overall_rating{sizeAttribute}{postIdAttribute}]
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
            function(props) {
                const blockProps = useBlockProps.save( {
                    className: 'yasr-overall-block',
                } );

                const {attributes: {size, postId}} = props;

                let yasrOverallAttributes = '';
                let post_id = postId;

                if (size) {
                    yasrOverallAttributes += 'size="' +size+ '"';
                }
                if (postId) {
                    yasrOverallAttributes += ' postid="'+post_id+'"';
                }

                return (
                    <div {...blockProps}>[yasr_overall_rating {yasrOverallAttributes}]</div>
                );
            },

    });