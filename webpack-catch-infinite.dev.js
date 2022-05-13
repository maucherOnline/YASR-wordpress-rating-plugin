const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'catch-inifite-scroll': [
            './includes/js/src/catch-inifite-scroll.js',
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
        extensions: ['.js']
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/')
    }
};