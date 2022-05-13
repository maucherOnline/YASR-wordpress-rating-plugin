const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'overall-multiset': [
            './includes/js/src/shortcodes/overall-multiset.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/shortcodes/')
    }
};