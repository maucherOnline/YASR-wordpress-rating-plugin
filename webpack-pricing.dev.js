const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-pricing-page': [
            './admin/js/src/yasr-pricing-page.js',
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
    },
};