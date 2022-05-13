const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'visitorVotes': [
            './includes/js/src/shortcodes/visitorVotes.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('includes/js/shortcodes/')
    }
};