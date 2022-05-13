const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        'yasr-editor-screen': [
            './admin/js/src/yasr-editor-screen.js',
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve('admin/js/')
    },
};