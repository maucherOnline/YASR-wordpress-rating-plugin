var __ = wp.i18n.__; // Import __() from wp.i18n

var registerPlugin = wp.plugins.registerPlugin;
var _wp$editPost = wp.editPost,
    PluginSidebar = _wp$editPost.PluginSidebar,
    PluginSidebarMoreMenuItem = _wp$editPost.PluginSidebarMoreMenuItem;
var _wp$components = wp.components,
    TextControl = _wp$components.TextControl,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow;
var _wp$editor = wp.editor,
    BlockControls = _wp$editor.BlockControls,
    InspectorControls = _wp$editor.InspectorControls;


var yasrProLabelSelectRows = __('How many rows do you want to display?', 'yet-another-stars-rating');
var yasrProLabelSelectText = __('Show text before or after the stars?', 'yet-another-stars-rating');
var yasrProLabelCptText = __('Do you want to specify a custom post type? ', 'yet-another-stars-rating');

/*registerBlockType(

    'yet-another-stars-rating/overall-rating-ranking-pro', {

        title: __('Yasr: Top 10 Highest rated Pro', 'yet-another-stars-rating'),
        description: __(
            'This ranking shows the highest rated posts through the overall_rating shortcode.',
            'yet-another-stars-rating'
        ),
        icon: 'star-filled',
        category: 'yet-another-stars-rating',
        keywords: [
            __('ranking', 'yet-another-stars-rating'),
            __('highest', 'yet-another-stars-rating'),
            __('chart', 'yet-another-stars-rating')
        ],
        attributes: {
            //name of the attribute
            rows: {
                type: 'number',
                default: '10'
            },
            size: {
                type: 'string',
                default: '--'
            },
            text_position: {
                type: 'string',
                default: 'no'
            },
            text: {
                type: 'string',
                default: 'Rating:'
            },
            category: {
                type: 'number',
                default: '--'
            },
            custom_post: {
                type: 'string',
                default: '--'
            },
        },

        edit:

            function(props) {

                const {attributes: {rows, size, text_position, text, category, custom_post}, setAttributes, isSelected} = props;

                let sizeAttribute = null;
                let rowsAttribute = null;
                let textPositionAttribute = null;
                let textAttribute = null;
                let customPostAttribute = null;
                let yasrProShowInputTextRankings = false
                let yasrProCustomPost = false;
                let textPlaceholder = __('Rating:', 'yet-another-stars-rating');
                let isNumRows = false;
                let isNumCat = false;

                isNumRows = /^\d+$/.test(rows);
                isNumCat = /^\d+$/.test(category);

                if (size !== '--') {
                    sizeAttribute = ' size="' + size + '"';
                } else {
                    sizeAttribute = '';
                }

                if (rows && isNumRows === true) {
                    rowsAttribute = ' rows="' +rows + '"';
                }

                if(text_position && text_position !== 'no') {
                    textPositionAttribute = ' text_position="' +text_position + '"';
                    yasrProShowInputTextRankings = true;

                    if (text !== '') {
                        textAttribute = ' text ="' + text + '"';
                        textPlaceholder = text;
                    }
                }

                if (custom_post === '--') {
                    customPostAttribute = '';
                }

                function YasrPrintSelectRows () {

                    let optionValueRows = [];

                    for (let i=2; i<=30; i++) {
                        optionValueRows.push(<option value={i}>{i}</option>);
                    }
                    console.log(optionValueRows);
                    return(
                        <form>
                            <select value={rows} onChange={yasrSetRowsNumber}>
                                {optionValueRows}
                            </select>
                        </form>
                    )

                }

                function yasrSetRowsNumber(event) {
                    const selected = event.target.querySelector('option:checked');
                    setAttributes({rows: selected.value});
                    event.preventDefault();
                }

                function YasrPrintSelectSize () {
                    return (
                         <form>
                             <select value={size} onChange={yasrSetStarsSize}>
                                 <option value="--">{yasrSelectSizeChoose}</option>
                                 <option value="small">{yasrSelectSizeSmall}</option>
                                 <option value="medium">{yasrSelectSizeMedium}</option>
                                 <option value="large">{yasrSelectSizeLarge}</option>
                             </select>
                         </form>
                     );
                }

                function yasrSetStarsSize(event) {
                    const selected = event.target.querySelector('option:checked');
                    setAttributes( { size: selected.value } );
                    event.preventDefault();
                }

                function YasrPrintTextPosition () {
                    return (
                        <form>
                            <select value={text_position} onChange={yasrSetTextPosition}>
                                <option value="no">No</option>
                                <option value="before">Before</option>
                                <option value="after">After</option>
                            </select>
                            {yasrProShowInputTextRankings && (
                                <input type="text" maxLength="30" placeholder={textPlaceholder}
                                       onKeyPress={yasrProSetInputTextRankings}/>
                            )}
                        </form>
                    );
                }

                function yasrSetTextPosition(event) {
                    const selectedTextPosition = event.target.querySelector('option:checked');
                    setAttributes({text_position: selectedTextPosition.value});
                    if (selectedTextPosition.value === 'no') {
                        yasrProShowInputTextRankings = false
                    } else {
                        yasrProShowInputTextRankings = true;
                    }
                    event.preventDefault();
                }

                function yasrProSetInputTextRankings(event) {
                    if (event.key === 'Enter') {
                        setAttributes({text: event.target.value})
                    }
                }

                function YasrPrintCPTOverall (props) {

                    let optionValueRows = [];

                    apiFetch({path: '/yet-another-stars-rating/v1/list-posts-types/'})
                        .then(checkResponse)
                        .then(function (response) {
                            if(Object.keys(response).length) {
                                for (let key in response) {
                                    optionValueRows.push(<option value={key}>{key}</option>);
                                }
                            } else {
                                optionValueRows.push(<option value={response}>{response}</option>);
                            }
                        })
                        .catch(function (err) {
                            console.log('Error with ajax call', err);
                        });

                    return (
                        <form>
                            <select>
                                {optionValueRows}
                                {console.log(optionValueRows)}
                            </select>
                            {yasrProCustomPost && (
                                CIAO
                            )}
                        </form>
                    );
                }

                function yasrSetCustomPost(event) {
                    const selectedCustomPost = event.target.querySelector('option:checked');
                    setAttributes({custom_post: selectedCustomPost.value});
                    if (custom_post === 'no') {
                        yasrProCustomPost = false
                    } else {
                        yasrProCustomPost = true;
                    }
                    event.preventDefault();
                }

                function YasrTopOverallPanel(props) {

                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <h3>{yasrOptionalText}</h3>

                                <div className="yasr-guten-block-panel">
                                    <label>{yasrProLabelSelectRows}</label>
                                    <div>
                                        <YasrPrintSelectRows />
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    <label>{yasrLabelSelectSize}</label>
                                    <div>
                                        <YasrPrintSelectSize />
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    <label>{yasrProLabelSelectText}</label>
                                    <div>
                                        <YasrPrintTextPosition />
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    <label>{yasrProLabelCptText}</label>
                                    <div>
                                        <YasrPrintCPTOverall />
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    {yasrVisitorVotesDescription}
                                </div>
                            </PanelBody>
                        </InspectorControls>
                    );

                }

                return (
                    <Fragment>
                        <YasrTopOverallPanel />
                        <div className={ props.className }>
                            [yasr_pro_overall_rating_chart{rowsAttribute}{sizeAttribute}{textPositionAttribute}{textAttribute}{customPostAttribute}]
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
/*save:
    function(props) {
        const {attributes: {rows, size, text_position, text}} = props;
         let yasrTopOverallAttributes = '';
         if (rows) {
            yasrTopOverallAttributes += ' rows="' +rows+ '"';
        }
        if (size && size !== '--') {
            yasrTopOverallAttributes += ' size="'+size+'"';
        }
        if (text_position) {
            yasrTopOverallAttributes += ' text_position="'+text_position+'"';
             if (text !== '') {
                yasrTopOverallAttributes += ' text="'+text+'"';
            }
        }
         return (
            <p>[yasr_pro_overall_rating_chart{yasrTopOverallAttributes}]</p>
        );
    },
});*/