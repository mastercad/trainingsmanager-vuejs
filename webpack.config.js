const
  Encore = require('@symfony/webpack-encore'),
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
  .addEntry('app', './assets/js/app.js')
  .enableSassLoader()
  .enableVueLoader()
  .autoProvideVariables({
    '$': 'jquery',
    'jQuery': 'jquery',
    'window.$': 'jquery',
    'window.jQuery': 'jquery',
  })
  .configureLoaderRule('eslint', loaderRule => {
    loaderRule.test = /\.(jsx?|vue)$/;
  })
  .enableSourceMaps(!Encore.isProduction())
  .enableSingleRuntimeChunk()
;

const config = Encore.getWebpackConfig();

config.resolve.alias = {
  handlebars: 'handlebars/dist/handlebars.min.js',
  vue: 'vue/dist/vue.js',
};

module.exports = config;