const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'rankings': [
            './includes/js/src/shortcodes/ranking.js',
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
        extensions: ['*', '.js', '.css']
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/shortcodes/')
    }
};