const path = require('path')
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
  entry: {
    'maps.bundle.js': './maps.js',
    'maps.bundle.css': [
      path.resolve(__dirname, 'node_modules/leaflet/dist/leaflet.css'),
      path.resolve(__dirname, 'node_modules/leaflet-control-geocoder/dist/Control.Geocoder.css'),
      path.resolve(__dirname, 'node_modules/leaflet.fullscreen/Control.FullScreen.css')
    ]
  },
  mode: 'none',
  output: {
    filename: '[name]',
    path: path.join(__dirname, 'webroot', 'js')
  },
  plugins: [
    new CopyWebpackPlugin([
      {
        from: path.resolve(__dirname, 'node_modules/leaflet/dist/leaflet.css'),
        to: path.resolve(__dirname, 'webroot/js/leaflet.css')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet/dist/images/'),
        to: path.resolve(__dirname, 'webroot/js/images')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet-control-geocoder/dist/Control.Geocoder.css'),
        to: path.resolve(__dirname, 'webroot/js/Control.Geocoder.css')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet-control-geocoder/images/'),
        to: path.resolve(__dirname, 'webroot/js/images')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet.fullscreen/Control.FullScreen.css'),
        to: path.resolve(__dirname, 'webroot/js/Control.FullScreen.css')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet.fullscreen/icon-fullscreen.png'),
        to: path.resolve(__dirname, 'webroot/js/icon-fullscreen.png')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet.markercluster/dist/MarkerCluster.css'),
        to: path.resolve(__dirname, 'webroot/js/MarkerCluster.css')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet.markercluster/dist/MarkerCluster.Default.css'),
        to: path.resolve(__dirname, 'webroot/js/MarkerCluster.Default.css')
      }, {
        from: path.resolve(__dirname, 'node_modules/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css'),
        to: path.resolve(__dirname, 'webroot/js/leaflet-gesture-handling.min.css')
      }
    ])
  ],
  externals: {
    // shows how we can rely on browser globals instead of bundling these dependencies,
    // in case we want to access jQuery from a CDN or if we want an easy way to
    // avoid loading all moment locales: https://github.com/moment/moment/issues/1435
    jquery: 'jQuery',
    L: 'L',
  },
  resolve: {
  },
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader'],
      },
      {
        test: /\.(png|jpe?g|gif)$/i,
        use: [
          {
            loader: 'file-loader',
            options:{
              name:'[name].[ext]',
              outputPath:'assets/images/'
            }
          },
        ],
      },
    ],
  }
}
