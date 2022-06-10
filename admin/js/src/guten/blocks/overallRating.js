const {registerBlockType}    = wp.blocks; // Import from wp.blocks

import metadata  from '../../../../../includes/blocks/overall-rating/block.json';
import edit      from '../registerBlockTypeEdit';
import saveBlock from '../registerBlockTypeSave';


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
registerBlockType (
    metadata, {
        edit: edit,
        save: (props) => {
            return saveBlock(props, metadata);
        }
    });