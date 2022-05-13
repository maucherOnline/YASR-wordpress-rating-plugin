const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-admin': [
            './admin/js/src/yasr-admin-functions.js',
            './admin/js/src/yasr-admin-dashboard.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('admin/js/')
    },
};