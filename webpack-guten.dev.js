//see here for help https://stackoverflow.com/questions/37656592/define-global-variable-with-webpack

const path    = require('path');
const webpack = require('webpack');

module.exports = {
    mode: 'development',
    entry: {
        './guten/blocks/shortcodes' : [
            './admin/js/src/guten/yasrGutenUtils.js',
            './admin/js/src/guten/yasrRegisterBlockType.js'
        ],
        './guten/yasr-guten-misc' : [
            './admin/js/src/guten/blocks/deprecated/deprecated_blocks.js',
            './admin/js/src/guten/yasrSidebar.js'
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
        path: path.resolve('admin/js/')
    }
};