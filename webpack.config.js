const webpack = require('webpack');

module.exports = {
  entry: './app/js/map.js',
  output: {
    path: './public/s',
    filename: 'map.js'
  },
  module: {
    loaders: [{
      test: /\.js$/,
      exclude: /node_modules/,
      loader: 'babel-loader'
    }]
  },
  plugins: [
    /*
    new webpack.optimize.UglifyJsPlugin({
      compress: {
        warnings: true,
      },
      output: {
        comments: true,
      },
    }),
    */
  ]
};
