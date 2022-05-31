const {registerBlockType}                = wp.blocks; // Import from wp.blocks
const {PanelBody}                        = wp.components;
const {Fragment}                         = wp.element;
const {useBlockProps, InspectorControls} = wp.blockEditor;

import {YasrNoSettingsPanel} from "../yasrGutenUtils";

//Most active users
registerBlockType(
    'yet-another-stars-rating/user-rate-history', {
        edit:
            function(props) {
                const blockProps = useBlockProps( {
                    className: 'yasr-user-rate-history'
                } );

                let YasrUserRateHisotrySettings = [<YasrNoSettingsPanel key={0}/>];
                {wp.hooks.doAction('yasr_user_rate_history_settings', YasrUserRateHisotrySettings)}

                function YasrUserRateHistoryPanel (props) {
                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <div className="yasr-guten-block-panel">
                                    <div>
                                        {YasrUserRateHisotrySettings}
                                    </div>
                                </div>
                            </PanelBody>
                        </InspectorControls>
                    );
                }

                return (
                    <Fragment>
                        <div {...blockProps}>
                            [yasr_user_rate_history]
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
                    className: 'yasr-user-rate-history'
                } );
                return (
                    <div {...blockProps}>[yasr_user_rate_history]</div>
                );
            },

    }
);