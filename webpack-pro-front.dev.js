const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'reviewsInComments': [
            './yasr_pro/js/src/reviewsInComments.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
};