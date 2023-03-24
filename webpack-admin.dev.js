/**
 * This file will compile the .js assets in dev mode, admin side
 */

const path = require('path');

var config = {
    module: {},
};

var yasrAdmin       = Object.assign({}, config, {
    mode: 'development',
    entry: {
        'admin/js/yasr-admin': [
            './admin/js/src/yasr-admin-functions.js',
            './includes/js/src/yasr-log-users.js'
        ],
        'admin/js/yasr-editor-screen': './admin/js/src/yasr-editor-screen.js',

        //Pro Version
        'yasr_pro/js/yasr-pro-settings': [
            './yasr_pro/js/src/yasr-pro-settings-cr-1.js',
            './yasr_pro/js/src/yasr-pro-settings-cr-2.js',
            './yasr_pro/js/src/yasr-pro-settings-ur-1.js',
            './yasr_pro/js/src/yasr-pro-export-page.js'
        ],
        'yasr_pro/js/yasr-pro-edit-comments': './yasr_pro/js/src/yasr-pro-edit-comments.js',
    },
    output: {
        filename: '[name].js',
        path: path.resolve('./')
    },
});

var yasrAdminBabel  = Object.assign({}, config, {
    mode: 'development',
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
            './admin/js/src/yasr-settings-rankings.js',
            './admin/js/src/yasr-stats-page.js'
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
});

module.exports   = [yasrAdmin, yasrAdminBabel];