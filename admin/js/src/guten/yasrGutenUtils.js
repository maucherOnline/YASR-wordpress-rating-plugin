//setting costants
const { __ }                             = wp.i18n; // Import __() from wp.i18n
const {PanelBody}                        = wp.components;
const {InspectorControls}                = wp.blockEditor;

export const yasrOptionalText            = __('All these settings are optional', 'yet-another-stars-rating');
export const yasrLabelSelectSize         = __('Choose Size', 'yet-another-stars-rating');

export const yasrSelectSizeChoose        = __('Choose stars size', 'yet-another-stars-rating');
export const yasrSelectSizeSmall         = __('Small', 'yet-another-stars-rating');
export const yasrSelectSizeMedium        = __('Medium', 'yet-another-stars-rating');
export const yasrSelectSizeLarge         = __('Large', 'yet-another-stars-rating');

export const yasrLeaveThisBlankText      = __('Leave this blank if you don\'t know what you\'re doing.', 'yet-another-stars-rating');

export const yasrOverallDescription      = __('Remember: only the post author can rate here.', 'yet-another-stars-rating');
export const yasrVisitorVotesDescription = __('This is the star set where your users will be able to vote', 'yet-another-stars-rating');


export const YasrPrintSelectSize = (props) => {
    return (
        <form>
            <select value={props.size} onChange={(e) => yasrSetStarsSize(props.setAttributes, e)}>
                <option value="--">{yasrSelectSizeChoose}    </option>
                <option value="small">{yasrSelectSizeSmall}  </option>
                <option value="medium">{yasrSelectSizeMedium}</option>
                <option value="large">{yasrSelectSizeLarge}  </option>
            </select>
        </form>
    );
}

function yasrSetStarsSize(setAttributes, event) {
    const selected = event.target.querySelector( 'option:checked' );
    setAttributes( { size: selected.value } );
    event.preventDefault();
}

export const YasrPrintInputId = (props) => {
    let postId;
    if(props.postId !== false) {
        postId = props.postId;
    }
    return (
        <div>
            <input
                type="text"
                size="4"
                defaultValue={postId}
                onKeyPress={(e) => yasrSetPostId(props.setAttributes, e)} />
        </div>
    );
}

function yasrSetPostId (setAttributes, event) {
    if (event.key === 'Enter') {
        const postIdValue = event.target.value;

        //postID is always a string, here I check if this string is made only by digits
        let isNum = /^\d+$/.test(postIdValue);

        if (isNum === true || postIdValue === '') {
            setAttributes({postId: postIdValue})
        }
        event.preventDefault();
    }
}

export const YasrProText = () => {

    const YasrProText1 =  __('To be able to customize this ranking, you need', 'yet-another-stars-rating');
    const YasrProText2 =  __('You can buy the plugin, including support, updates and upgrades, on',
        'yet-another-stars-rating');

    return (
        <h3>
            {YasrProText1}
            &nbsp;
            <a href="admin/js/src/guten/yasrGutenUtils?utm_source=wp-plugin&utm_medium=gutenberg_panel&utm_campaign=yasr_editor_screen&utm_content=rankings#yasr-pro">
                Yasr Pro.
            </a><br />
            {YasrProText2}
            &nbsp;
            <a href="admin/js/src/guten/yasrGutenUtils?utm_source=wp-plugin&utm_medium=gutenberg_panel&utm_campaign=yasr_editor_screen&utm_content=rankings">
                yetanotherstarsrating.com
            </a>
        </h3>
    )

}

export const YasrNoSettingsPanel = () => {
    return (
        <div>
            <YasrProText/>
        </div>
    );
}

/**
 * This is the panel that for blocks that use size and postid attributes
 *
 * @param props
 * @return {JSX.Element}
 */
export const YasrBlocksPanel =  (props) => {

    const {block: name, size, setAttributes, postId} = props;

    let bottomDesc;
    if(name === 'yet-another-stars-rating/visitor-votes') {
        bottomDesc = yasrVisitorVotesDescription;
    }
    if(name === 'yet-another-stars-rating/overall-rating') {
        bottomDesc = yasrOverallDescription;
    }

    return (
        <InspectorControls>
            {name === 'yet-another-stars-rating/overall-rating' && <YasrDivRatingOverall />}
            <PanelBody title='Settings'>
                <h3>{yasrOptionalText}</h3>

                <div className="yasr-guten-block-panel">
                    <label>{yasrLabelSelectSize}</label>
                    <div>
                        <YasrPrintSelectSize size={size} setAttributes={setAttributes}/>
                    </div>
                </div>

                <div className="yasr-guten-block-panel">
                    <label>Post ID</label>
                    <YasrPrintInputId postId={postId} setAttributes={setAttributes}/>
                    <div className="yasr-guten-block-explain">
                        Use return (&#8629;) to save.
                    </div>
                    <p>
                        {yasrLeaveThisBlankText}
                    </p>
                </div>

                <div className="yasr-guten-block-panel">
                    {bottomDesc}
                </div>
            </PanelBody>
        </InspectorControls>
    );

}

/**
 * Return a div with the stars in order to vote for overall rating
 *
 * @param props
 * @returns {JSX.Element}
 */
export const YasrDivRatingOverall = (props) => {

    if(JSON.parse(yasrConstantGutenberg.isFseElement) === true) {
        return (
            <div className="yasr-guten-block-panel yasr-guten-block-panel-center">
                <div>
                    {__('This is a template file, you can\'t rate here. You need to insert the rating inside the single post or page',
                        'yet-another-stars-rating')}
                </div>
                <br />
            </div>
        );
    }

    //Outside the editor page (e.g. widgets.php) wp.data.select('core/editor') is null
    //So, in such cases, rating in overall widget must be disabled
    if(wp.data.select('core/editor') === null) {
        return (
            <>
            </>
        )
    }

    const yasrOverallRateThis = __("Rate this article / item", 'yet-another-stars-rating');
    let overallRating = wp.data.select('core/editor').getCurrentPost().meta.yasr_overall_rating;

    const rateCallback =  function (rating, done) {
        rating = rating.toFixed(1);
        rating = parseFloat(rating);

        wp.data.dispatch('core/editor').editPost(
            { meta: { yasr_overall_rating: rating } }
        );

        this.setRating(rating);
        done();
    };
    return (
        <div className="yasr-guten-block-panel yasr-guten-block-panel-center">
            {yasrOverallRateThis}
            <div id={'overall-rater'} ref={() =>
                yasrSetRaterValue (
                    32,
                    'overall-rater',
                    false,
                    0.1,
                    false,
                    overallRating,
                    rateCallback
                )
            } />
        </div>
    );
}


/**
 * Return null if size === large, or a string otherwise
 *
 * @param size
 * @returns {(null | string)}
 */
export const YasrBlockSizeAttribute = (size) => {
    let sizeString = null;
    if (size !== 'large') {
        sizeString =  ` size="${size}"`
    }
    return (sizeString);
};

/**
 *
 * @param postId
 * @returns {(null | string)}
 */
export const YasrBlockPostidAttribute = (postId) => {
    let isNum;
    let postIdAttribute = null;

    isNum = /^\d+$/.test(postId);

    if (isNum === true) {
        postIdAttribute = ' postid="' +postId + '"';
    }

    return postIdAttribute;
};


/**
 * Return an object with block attributes
 *
 * @param blockName
 * @returns {object}
 */
export const YasrSetBlockAttributes = (blockName) => {
    let blockAttributes = {
        className: null, //class name for the main div
        shortCode: null, //shortcode
    }

    if(blockName === 'yet-another-stars-rating/overall-rating') {
        blockAttributes.className =  'yasr-overall-block';
        blockAttributes.shortCode =  'yasr_overall_rating';
    }

    if(blockName === 'yet-another-stars-rating/visitor-votes') {
        blockAttributes.className =  'yasr-vv-block';
        blockAttributes.shortCode =  'yasr_visitor_votes';
    }

    return blockAttributes;
}