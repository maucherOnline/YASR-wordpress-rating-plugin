const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-globals': [
            './includes/js/src/global_functions/rater-js-src.js',
            './includes/js/src/global_functions/yasrSetRaterValue.js',
            './includes/js/src/global_functions/yasrTrueFalseStringConvertion.js',
            './includes/js/src/global_functions/yasrValidJson.js'
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/')
    }
};