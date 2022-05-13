const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-pro-front': [
            './yasr_pro/js/src/yasr-pro-front.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
};