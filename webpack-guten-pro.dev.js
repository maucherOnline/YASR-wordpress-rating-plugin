const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-pro-gutenberg': [
            './yasr_pro/js/src/guten/yasr-pro-guten-blocks.js',
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
    resolve: {
        extensions: ['*', '.js']
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
};