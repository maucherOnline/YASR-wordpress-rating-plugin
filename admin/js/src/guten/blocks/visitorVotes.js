const {registerBlockType}          = wp.blocks; // Import from wp.blocks
const {useBlockProps}              = wp.blockEditor;

import edit     from '../registerBlockTypeEdit';
import metadata from '../../../../../includes/blocks/visitor-votes/block.json';

registerBlockType(
    metadata, {
        edit: edit,

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