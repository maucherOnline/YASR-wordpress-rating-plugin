const { __ } = wp.i18n; // Import __() from wp.i18n


function YasrBlockSettingsLink() {
    return (
        <div>
            {__('Customize this ranking ', 'yet-another-stars-rating')}
            <a href={yasrConstantGutenberg.adminurl + 'admin.php?page=yasr_settings_page&tab=rankings'}>
                {__('here.', 'yet-another-stars-rating')}
            </a>
        </div>
    );
}

/**
 * Auto insert Comment Reviews
 */
class YasrCommentReviewsEnabled extends React.Component {
    yasrProLabelReviewsEnabled;

    constructor(props) {
        super(props);

        //by default, set to disable
        this.yasrProLabelReviewsEnabled = __('Reviews in comments for this post / page are disabled', 'yet-another-stars-rating');

        //get rest yasr_pro_comment_review_enabled
        //YOURSITE.COM/wp-json/wp/v2/posts/<POSTID>?_fields=yasr_pro_comment_review_enabled
        //with + convert bool to int
        let reviewEnabledForPost = + wp.data.select('core/editor').getCurrentPost().yasr_pro_comment_review_enabled;

        if (reviewEnabledForPost === 1) {
            this.yasrProLabelReviewsEnabled = __('Reviews in comments for this post / page are enabled', 'yet-another-stars-rating');
        }

        this.state = {reviewEnabled: reviewEnabledForPost};

        this.yasrUpdatePostMetaReviewsEnabled = this.yasrUpdatePostMetaReviewsEnabled.bind(this);
    }

    yasrUpdatePostMetaReviewsEnabled(event) {
        const target = event.target;
        const reviewEnabled = target.type === 'checkbox' ? target.checked : target.value;

        const multiSetinReview      = document.getElementById('yasr-pro-multiset-review-switcher');

        this.setState({reviewEnabled: reviewEnabled});

        //MUST be saved as a string
        if (reviewEnabled === true) {
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_pro_reviews_in_comment_enabled: '1' } }
            );
        } else {
            multiSetinReview.checked = false;
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_pro_reviews_in_comment_enabled: '0' } }
            );
        }
    }

    /**
     * Enable or disable comment in page
     * @returns {JSX.Element}
     */
    yasrPanelEnableReviews() {
        return(
            <div>
                <div>
                    <hr/>
                    <label><span>{this.yasrProLabelReviewsEnabled}</span></label>
                    <div className="yasr-onoffswitch-big yasr-onoffswitch-big-center" id="yasr-switcher-disable-comment-reviews">
                        <input type="checkbox"
                               name="yasr_comment_reviews_disabled"
                               className="yasr-onoffswitch-checkbox"
                               value="1"
                               id="yasr-comment-reviews-disabled-switch"
                               defaultChecked={this.state.reviewEnabled}
                               onChange={this.yasrUpdatePostMetaReviewsEnabled}
                        />

                        <label className="yasr-onoffswitch-label" htmlFor="yasr-comment-reviews-disabled-switch">
                            <span className="yasr-onoffswitch-inner yasr-onoffswitch-onoff-inner" />
                            <span className="yasr-onoffswitch-switch" />
                        </label>
                    </div>
                    <div className="yasr-metabox-doc-link">
                        <a href="https://yetanotherstarsrating.com/yasr-pro/reviews-in-comments/?utm_source=wp-plugin&utm_medium=tinymce-popup&utm_campaign=yasr_top_right"
                           target="_blank"
                        >
                            {__('Help', 'yet-another-stars-rating')}
                        </a>
                    </div>
                </div>
            </div>
        )
    }

    selectMultiset () {
        return (
            <div>
                Insert multiset here
            </div>
        )
    }

    render () {
        //cast to bool
        let reviewEnabled = yasrTrueFalseStringConvertion(this.state.reviewEnabled);
        return (
            <div>
                {this.yasrPanelEnableReviews()}
                {reviewEnabled === true && this.selectMultiset()}
            </div>
        );
    }
}

/**
 * Fake Ratings
 */
class YasrFakeRatings extends React.Component {

    constructor(props) {
        super(props);

        this.state = {numberFakeRatings: 0};
        this.state = {fakeRating: 5};

        this.state = {ajaxResponse: ''};

        this.yasrNumberFakeRatings = this.yasrNumberFakeRatings.bind(this);
        this.yasrFakeRating        = this.yasrFakeRating.bind(this);
        this.yasrSaveFakeRatings   = this.yasrSaveFakeRatings.bind(this);
    }

    yasrNumberFakeRatings(event) {
        this.setState({numberFakeRatings: event.target.value});
    }

    yasrFakeRating(event) {
        this.setState({fakeRating: event.target.value});
    }

    yasrSaveFakeRatings(event) {

        const currentPostId = wp.data.select("core/editor").getCurrentPostId();

        if(this.state.numberFakeRatings > 0 && this.state.numberFakeRatings < 201) {
            this.setState({ajaxResponse: JSON.parse(yasrWindowVar.textLoadRanking)});

            let rating          = this.state.fakeRating;
            let number_of_votes = this.state.numberFakeRatings

            //if select doesn't change value is undefined
            if(rating == null) {
                rating = 5;
            }

            //declaring function to set response
            let self = this;
            function setResponse(response) {
                if(response === 'OK') {
                    self.setState({
                        ajaxResponse: __('Done!', 'yet-another-stars-rating'),
                    });
                } else {
                    self.setState({
                        ajaxResponse: __('Error', 'yet-another-stars-rating'),
                    });
                }
            }

            let data = {
                action: 'yasr_adds_fake_ratings',
                yasr_pro_nonce_fake_ratings: yasrConstantGutenberg.yasr_pro_nonce_fake_ratings,
                yasr_pro_fake_number_of_votes: number_of_votes,
                yasr_pro_fake_ratings: rating,
                post_id: currentPostId
            };

            //Send value to the Server
            jQuery.post(ajaxurl, data, function (response) {
                setResponse(response);
            }).fail(
                function(e, x, settings, exception) {
                    setResponse('KO');
                }
            );

        }

    }

    render () {
        let selectDisabled = true;

        if(JSON.parse(yasrConstantGutenberg.proVersion) === true) {
            selectDisabled = false;
        }

        return (
            <div>
                <hr/>
                <strong>{__('Add fake ratings', 'yet-another-stars-rating')}</strong>
                <p />
                <div>
                    {__('Number of votes',  'yet-another-stars-rating')}
                    <br />
                    <div>
                        <label htmlFor="yasr-pro-fake-number-of-votes">
                            <select name="yasr-pro-fake-number-of-votes"
                                    id="yasr-pro-fake-number-of-votes"
                                    onChange={this.yasrNumberFakeRatings}
                                    disabled={selectDisabled}
                            >
                                <option value="none" defaultValue>0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                            </select>
                        </label>
                    </div>
                </div>
                {__('Rating:', 'yet-another-stars-rating')}
                <div>
                    <label htmlFor="yasr-pro-fake-ratings">
                        <select name="yasr-pro-fake-ratings"
                                id="yasr-pro-fake-ratings"
                                value={this.state.fakeRating}
                                onChange={this.yasrFakeRating}
                                disabled={selectDisabled}
                        >
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </label>
                    <p />
                    <div>
                        {__(
                            'This will add ratings for yasr_visitor_votes shortcode.',
                            'yet-another-stars-rating')
                        }
                    </div>
                </div>
                <div>
                    <br />
                    <button onClick={this.yasrSaveFakeRatings} disabled={selectDisabled}>Save</button>
                    <br />
                    {this.state.ajaxResponse}
                </div>
                <div className="yasr-metabox-doc-link">
                    <a href="https://yetanotherstarsrating.com/yasr_fake_ratings/?utm_source=wp-plugin&utm_medium=tinymce-popup&utm_campaign=yasr_top_right"
                       target="_blank"
                    >
                        {__('Help', 'yet-another-stars-rating')}
                    </a>
                </div>
            </div>
        );
    }
}

wp.hooks.addAction('yasr_below_panel', 'yet-another-stars-rating', function(arr){
    arr[0] = <YasrCommentReviewsEnabled key={0} />;
    arr[1] = <YasrFakeRatings key={1} />;
} );


wp.hooks.addAction('yasr_overall_rating_rankings', 'yet-another-stars-rating', function(arr){
    arr[0] = <YasrBlockSettingsLink key={0} />;
} );

wp.hooks.addAction('yasr_visitor_votes_rankings', 'yet-another-stars-rating', function(arr){
    arr[0] = <YasrBlockSettingsLink key={0} />;
} );

wp.hooks.addAction('yasr_top_reviewers_setting', 'yet-another-stars-rating', function(arr){
    arr[0] = <YasrBlockSettingsLink key={0} />;
} );

wp.hooks.addAction('yasr_top_visitor_setting', 'yet-another-stars-rating', function(arr){
    arr[0] = <YasrBlockSettingsLink key={0} />;
} );
