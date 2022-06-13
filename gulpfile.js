const gulp = require('gulp');

const { series, parallel } = require('gulp');
const del = require('del');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const merge = require('merge-stream');
const replace = require('gulp-replace');
const cleancss = require('gulp-clean-css');

const plugin = {
  'public': './webroot'
}

const main_styles = [
  './node_modules/leaflet/dist/leaflet.css',
  './node_modules/leaflet.fullscreen/Control.FullScreen.css',
  './node_modules/leaflet-gesture-handling/dist/leaflet-gesture-handling.css',
  './node_modules/leaflet.markercluster/dist/MarkerCluster.css',
  './node_modules/leaflet.markercluster/dist/MarkerCluster.Default.css',
  './node_modules/leaflet-control-geocoder/dist/Control.Geocoder.css',
  './node_modules/leaflet-legend/leaflet-legend.css'
];

const main_scripts = [
  './node_modules/leaflet/dist/leaflet.js',
  './node_modules/leaflet.fullscreen/Control.FullScreen.js',
  './node_modules/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.js',
  './node_modules/leaflet.markercluster/dist/leaflet.markercluster.js',
  './node_modules/leaflet-control-geocoder/dist/Control.Geocoder.js',
  './node_modules/leaflet-legend/leaflet-legend.js'
];

const main_assets = [
  {
    'src': './node_modules/leaflet/dist/images/*',
    'dest': '/images/'
  },
  {
    'src': './node_modules/leaflet.locatecontrol/*.svg',
    'dest': '/images/'
  },
  {
    'src': './node_modules/leaflet.fullscreen/*.svg',
    'dest': '/images/'
  }
];

function clean(cb) {
  assets = [
    plugin.public + '/**',
    '!' + plugin.public,
    '!' + plugin.public + '/galette_maps.css',
    plugin.public + '/images/**',
    '!' + plugin.public + '/images',
    '!' + plugin.public + '/images/marker-galette.png',
    '!' + plugin.public + '/images/marker-galette-pro.png',
  ];
  return del(assets, cb);
};

function styles() {
  main = gulp.src(main_styles)
    .pipe(replace('icon-fullscreen.svg', './images/icon-fullscreen.svg'))
    .pipe(cleancss())
    .pipe(concat('maps-main.bundle.min.css'))
    .pipe(gulp.dest(plugin.public));

  locate = gulp.src([
      './node_modules/leaflet.locatecontrol/dist/L.Control.Locate.min.css'
    ])
    .pipe(replace('../location-arrow-solid.svg', './images/location-arrow-solid.svg'))
    .pipe(replace('../spinner-solid.svg', './images/spinner-solid.svg'))
    .pipe(cleancss())
    .pipe(concat('maps-locate.bundle.min.css'))
    .pipe(gulp.dest(plugin.public));

  return merge(main, locate);
};

function scripts() {
  main = gulp.src(main_scripts)
    .pipe(concat('maps-main.bundle.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(plugin.public));

  locate = gulp.src([
      './node_modules/leaflet.locatecontrol/dist/L.Control.Locate.min.js'
    ])
    .pipe(concat('maps-locate.bundle.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(plugin.public));

  return merge(main, locate);
};

function assets() {
  main = main_assets.map(function (asset) {
    return gulp.src(asset.src)
      .pipe(gulp.dest(plugin.public + asset.dest));
    }
  );

  return merge(main);
};

exports.clean = clean;

exports.styles = styles;
exports.scripts = scripts;
exports.assets = assets;

exports.build = series(styles, scripts, assets);
exports.default = exports.build;
