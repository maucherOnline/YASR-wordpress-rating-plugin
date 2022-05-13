const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-pro-settings': [
            './yasr_pro/js/src/yasr-pro-settings-cr-1.js',
            './yasr_pro/js/src/yasr-pro-settings-cr-2.js',
            './yasr_pro/js/src/yasr-pro-settings-ur-1.js'
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('yasr_pro/js/')
    },
};