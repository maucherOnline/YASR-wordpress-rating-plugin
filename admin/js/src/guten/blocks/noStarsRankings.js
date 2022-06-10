const {registerBlockType} = wp.blocks; // Import from wp.blocks

import metadataUsers     from '../../../../../includes/blocks/ranking-users/block.json';
import metadataReviewers from '../../../../../includes/blocks/ranking-reviewers/block.json';

import edit      from '../registerBlockTypeEdit';
import saveBlock from '../registerBlockTypeSave';

//Most active users
registerBlockType(
    metadataUsers, {
        edit: edit,
        save: (props) => {
            return saveBlock(props, metadataUsers);
        }
    }
);

//Most Active reviewers
registerBlockType(
    metadataReviewers, {
        edit: edit,
        save: (props) => {
            return saveBlock(props, metadataReviewers);
        }
    }
);