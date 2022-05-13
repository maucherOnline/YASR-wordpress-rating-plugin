var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

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

//wp.hooks.addFilter('yasr_add_content_bottom_topright_metabox', 'ContentBelowSidebar', ContentBelowSidebar);

var ContentBelowSidebar = function (_React$Component) {
    _inherits(ContentBelowSidebar, _React$Component);

    function ContentBelowSidebar(props) {
        _classCallCheck(this, ContentBelowSidebar);

        //by default, set to disable
        var _this = _possibleConstructorReturn(this, (ContentBelowSidebar.__proto__ || Object.getPrototypeOf(ContentBelowSidebar)).call(this, props));

        _this.yasrProLabelReviewsEnalbed = __('Reviews in comments for this post / page are disabled', 'yet-another-stars-rating');

        //get rest yasr_pro_review_enabled
        //with + convert bool to int
        var reviewEnabledForPost = +wp.data.select('core/editor').getCurrentPost().yasr_pro_review_enabled;

        if (reviewEnabledForPost === 1) {
            _this.yasrProLabelReviewsEnalbed = __('Reviews in comments for this post / page are enabled', 'yet-another-stars-rating');
        }

        _this.state = { reviewEnabled: reviewEnabledForPost };

        _this.yasrUpdatePostMetaReviewsEnabled = _this.yasrUpdatePostMetaReviewsEnabled.bind(_this);
        return _this;
    }

    _createClass(ContentBelowSidebar, [{
        key: 'yasrUpdatePostMetaReviewsEnabled',
        value: function yasrUpdatePostMetaReviewsEnabled(event) {
            var target = event.target;
            var reviewEnabled = target.type === 'checkbox' ? target.checked : target.value;

            this.setState({ reviewEnabled: reviewEnabled });

            //MUST be saved as a string
            if (reviewEnabled === true) {
                wp.data.dispatch('core/editor').editPost({ meta: { yasr_pro_reviews_in_comment_enabled: '1' } });
            } else {
                wp.data.dispatch('core/editor').editPost({ meta: { yasr_pro_reviews_in_comment_enabled: '0' } });
            }
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                null,
                React.createElement('hr', null),
                React.createElement(
                    'label',
                    null,
                    React.createElement(
                        'span',
                        null,
                        this.yasrProLabelReviewsEnalbed
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'yasr-onoffswitch-big yasr-onoffswitch-big-center', id: 'yasr-switcher-disable-comment-reviews' },
                    React.createElement('input', { type: 'checkbox',
                        name: 'yasr_comment_reviews_disabled',
                        className: 'yasr-onoffswitch-checkbox',
                        value: '1',
                        id: 'yasr-comment-reviews-disabled-switch',
                        defaultChecked: this.state.reviewEnabled,
                        onChange: this.yasrUpdatePostMetaReviewsEnabled
                    }),
                    React.createElement(
                        'label',
                        { className: 'yasr-onoffswitch-label', htmlFor: 'yasr-comment-reviews-disabled-switch' },
                        React.createElement('span', { className: 'yasr-onoffswitch-inner' }),
                        React.createElement('span', { className: 'yasr-onoffswitch-switch' })
                    )
                )
            );
        }
    }]);

    return ContentBelowSidebar;
}(React.Component);