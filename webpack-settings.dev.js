const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-settings': [
            './admin/js/src/yasr-settings-page.js',
            './admin/js/src/yasr-settings-rankings.js'
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
        path: path.resolve('admin/js/')
    },
};