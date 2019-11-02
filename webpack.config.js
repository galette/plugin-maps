const path = require('path')
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
  entry: './maps.js',
  mode: 'none',
  output: {
    filename: "maps.bundle.js",
    path: path.join(__dirname, 'webroot', 'js')
  },
  externals: {
    // shows how we can rely on browser globals instead of bundling these dependencies,
    // in case we want to access jQuery from a CDN or if we want an easy way to
    // avoid loading all moment locales: https://github.com/moment/moment/issues/1435
    jquery: 'jQuery',
    L: 'L',
  },
  devtool: 'sourcemap',
  resolve: {
    extensions: [ '.js' ],
  }
}
