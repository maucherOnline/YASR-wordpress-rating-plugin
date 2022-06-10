const {registerBlockType}                = wp.blocks; // Import from wp.blocks
const {PanelBody}                        = wp.components;
const {Fragment}                         = wp.element;
const {useBlockProps, InspectorControls} = wp.blockEditor;

import {YasrNoSettingsPanel} from "yasrGutenUtils";
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
        edit:
            function(props) {
                const blockProps = useBlockProps( {
                    className: 'yasr-reviewers-block',
                } );

                let YasrTopReviewersSettings = [<YasrNoSettingsPanel key={0}/>];
                {wp.hooks.doAction('yasr_top_reviewers_setting', YasrTopReviewersSettings)}

                function YasrTopReviewersPanel (props) {
                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <div className="yasr-guten-block-panel">
                                    <div>
                                        {YasrTopReviewersSettings}
                                    </div>
                                </div>
                            </PanelBody>
                        </InspectorControls>
                    );
                }

                return (
                    <Fragment>
                        <YasrTopReviewersPanel />
                        <div {...blockProps}>
                            [yasr_top_reviewers]
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
                    className: 'yasr-reviewers-block',
                } );
                return (
                    <div  {...blockProps}>[yasr_top_reviewers]</div>
                );
            },

    }
);