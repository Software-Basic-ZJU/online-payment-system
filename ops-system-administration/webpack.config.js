var path = require('path');

module.exports = {
    entry: './views/index',
    output: {
        path: path.join(__dirname, '/public'),
        filename: 'bundle.js'
    },
    resolve: {
        extensions: ['', '.js', '.jsx']
    },
    module: {
        loaders: [
            { test: /\.jsx?$/, loaders: ['babel'] }
        ]
    }
}