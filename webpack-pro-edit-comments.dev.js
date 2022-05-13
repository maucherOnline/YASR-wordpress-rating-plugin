const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-pro-edit-comments': [
            './yasr_pro/js/src/yasr-pro-edit-comments.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
};