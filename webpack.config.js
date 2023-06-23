const
//  CopyWebpackPlugin = require('copy-webpack-plugin'),
  Encore = require('@symfony/webpack-encore'),
//  UglifyJsPlugin = require('uglifyjs-webpack-plugin'),
  ESLintPlugin = require("eslint-webpack-plugin"),
  webpack = require('webpack');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureWatchOptions(function(watchOptions) {
    // enable polling and check for changes every 250ms
    // polling is useful when running Encore inside a Virtual Machine
    watchOptions.poll = 250;
  })
  // not needed because babel.config.js is present
//  .configureBabelPresetEnv((config) => {
//    config.useBuiltIns = 'usage';
//    config.corejs = 3;
//  })
  .addEntry('app', './assets/js/app.js')
//  .addEntry('admin', './assets/js/admin.js')
//  .addEntry('common', './assets/js/common.js')
//  .addPlugin(new CopyWebpackPlugin([
//   ...
//  ]))
  .enableSassLoader()
  .enableVueLoader()
  .autoProvideVariables({
    '$': 'jquery',
    'jQuery': 'jquery',
    'window.$': 'jquery',
    'window.jQuery': 'jquery',
  })
//  .addLoader({
//    test: /\.js$/,
//    enforce: 'pre',
//    loader: 'eslint-loader',
//    exclude: /node_modules/,
//    options: {
//      fix: true
//    }
//  })
  .configureLoaderRule('eslint', loaderRule => {
    loaderRule.test = /\.(jsx?|vue)$/;
  })
  .enableSourceMaps(!Encore.isProduction())
  .enableSingleRuntimeChunk()
;

const config = Encore.getWebpackConfig();

//config.plugins = config.plugins.filter(
//  (plugin) => !(plugin instanceof webpack.optimize.UglifyJsPlugin)
//);

//const eslintLoader = config.module.rules.find(rule => rule.loader === 'eslint-loader');
//eslintLoader.test = /\.(js?|vue)$/;

if (Encore.isProduction()) {
  config.plugins.push(
//    new webpack.DefinePlugin({
//      'process.env.NODE_ENV': JSON.stringify('production'),
//    }),
//    new webpack.DefinePlugin({
//      'process.env' : {
//        NODE_ENV: JSON.stringify('production')
//      }
//    }),
//    new webpack.DefinePlugin({
//      "process.env.PRODUCTION": JSON.stringify(PRODUCTION),
//      "process.env.DEVELOPMENT": JSON.stringify(DEVELOPMENT)
//    }),
//    new UglifyJsPlugin()
  );
}

//config.presets = [
//  '@babel/preset-env'
//];

config.resolve.alias = {
  handlebars: 'handlebars/dist/handlebars.min.js',
  vue: 'vue/dist/vue.js',
};

module.exports = config;