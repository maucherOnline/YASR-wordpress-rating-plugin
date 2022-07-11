const path          = require('path');
const TerserPlugin  = require('terser-webpack-plugin');

var config = {
    module: {},
};

var yasrAdmin    = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'admin/js/yasr-admin': [
            './admin/js/src/yasr-admin-functions.js',
            './admin/js/src/yasr-admin-dashboard.js',
        ],
        'admin/js/yasr-editor-screen': './admin/js/src/yasr-editor-screen.js',

        //Pro Version
        'yasr_pro/js/yasr-pro-settings': [
            './yasr_pro/js/src/yasr-pro-settings-cr-1.js',
            './yasr_pro/js/src/yasr-pro-settings-cr-2.js',
            './yasr_pro/js/src/yasr-pro-settings-ur-1.js'
        ],
        'yasr_pro/js/yasr-pro-edit-comments': './yasr_pro/js/src/yasr-pro-edit-comments.js',
    },
    optimization: {
        minimizer: [new TerserPlugin({
            extractComments: false,
        })],
    },
    output: {
        filename: '[name].js',
        path: path.resolve('./')
    },
});

var yasrAdminBabel    = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'admin/js/guten/blocks/shortcodes' : [
            './admin/js/src/guten/yasrGutenUtils.js',
            './admin/js/src/guten/yasrRegisterBlockType.js'
        ],
        'admin/js/guten/yasr-guten-misc' : [
            './admin/js/src/guten/blocks/deprecated/deprecated_blocks.js',
            './admin/js/src/guten/yasrSidebar.js'
        ],
        'admin/js/yasr-settings': [
            './admin/js/src/yasr-settings-page.js',
            './admin/js/src/yasr-settings-rankings.js'
        ],
        'admin/js/yasr-pricing-page': './admin/js/src/yasr-pricing-page.js',

        'yasr_pro/js/yasr-pro-gutenberg': [
            './yasr_pro/js/src/guten/yasr-pro-guten-panel.js',
        ]
    },
    module: {
        rules: [
            {
                test: /\.(js)$/,
                exclude: /node_modules/,
                use: ['babel-loader']
            }
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('./')
    },
    optimization: {
        minimizer: [new TerserPlugin({
            extractComments: false,
        })],
    }
});

var yasrFront    = Object.assign({}, config, {
    mode: 'production',
    entry: {
        'includes/js/yasr-globals': [
            './includes/js/src/global_functions/yasrTrueFalseStringConvertion.js',
            './includes/js/src/global_functions/yasrValidJson.js',
            './includes/js/src/global_functions/rater-js-src.js',
            './includes/js/src/global_functions/yasrSetRaterValue.js'
        ],
        'includes/js/shortcodes/overall-multiset': './includes/js/src/shortcodes/overall-multiset.js',
        'includes/js/shortcodes/visitorVotes': './includes/js/src/shortcodes/visitorVotes.js',
        'includes/js/shortcodes/yasr-log-users-frontend': './includes/js/src/shortcodes/yasr-log-users-frontend.js',

        'yasr_pro/js/reviewsInComments': [
            './yasr_pro/js/src/reviewsInComments.js',
        ]
    },
    optimization: {
        minimizer: [new TerserPlugin({
            extractComments: false,
        })],
    },
    output: {
        filename: '[name].js',
        path: path.resolve('./')
    }
});

var yasrFrontBabel  = Object.assign({}, config, {
    mode: 'production',
    entry: {
        './shortcodes/rankings': './includes/js/src/shortcodes/ranking.js',
        './catch-inifite-scroll': './includes/js/src/catch-inifite-scroll.js',
    },
    module: {
        rules: [
            {
                test: /\.(js)$/,
                exclude: /node_modules/,
                use: ['babel-loader']
            }
        ]
    },
    resolve: {
        extensions: ['*', '.js', '.css']
    },
    optimization: {
        minimizer: [new TerserPlugin({
            extractComments: false,
        })],
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/')
    }
});

module.exports   = [yasrAdmin, yasrAdminBabel, yasrFront, yasrFrontBabel];