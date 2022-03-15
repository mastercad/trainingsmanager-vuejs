const { join } = require("path");
const Encore = require("@symfony/webpack-encore");
const ESLintPlugin = require("eslint-webpack-plugin");

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

/*
Encore.configureWatchOptions(function(watchOptions) {
    // enable polling and check for changes every 250ms
    // polling is useful when running Encore inside a Virtual Machine
    watchOptions.poll = 250;
});
*/
/*
Encore.configureLoaderRule('eslint', loaderRule => {
    loaderRule.test = /\.(jsx?|vue)$/
});
*/

Encore
  // directory where compiled assets will be stored
  .setOutputPath("public/build/")
  // public path used by the web server to access the output path
  .setPublicPath("/build")
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')

  .configureBabel(
    (babelConfig) => {
      babelConfig.plugins.push("@babel/plugin-transform-runtime");
    },
    {
      useBuiltIns: "usage",
      corejs: 3,
    }
  )

  .enableVueLoader()

  /*
   * ENTRY CONFIG
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
   */
  .addEntry("app", "./assets/vue/app.js")

// enable ESLint
/*
  .addLoader({
    enforce: 'pre',
    test: /\.(js|vue)$/,
    loader: 'eslint-webpack-plugin',
    exclude: /node_modules/,
    options: {
      eslintPath: 'eslint',
      fix: true,
      emitError: true,
      emitWarning: true,
    },
  })
*/

// When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
//    .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  //    .enableSingleRuntimeChunk()
  .disableSingleRuntimeChunk()

/*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
//    .cleanupOutputBeforeBuild()

  .enableBuildNotifications()

  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())

  .configureBabel((config) => {
    config.plugins.push("@babel/plugin-proposal-class-properties");
  })

  // enables @babel/preset-env polyfills
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = "usage";
    config.corejs = 3;
  })

// enables Sass/SCSS support
  .enableSassLoader()

// uncomment if you use TypeScript
//.enableTypeScriptLoader()

// uncomment if you use React
//.enableReactPreset()

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

if (Encore.isDevServer()) {
  Encore.enableBuildNotifications(true, (options) => {
    options.skipFirstNotification = true;
  })

    .enableBuildCache({ config: [__filename] }, (cache) => {
      cache.version = `${process.env.GIT_REV}`;
      cache.name = `${process.env.target}`;
    })

    .configureDevServerOptions((options) => {
      // https://github.com/symfony/webpack-encore/issues/1017#issuecomment-887264214
      // delete options.client.host

      /**
       * Normalize "options.static" property to an array
       */
      if (!options.static) {
        options.static = [];
      } else if (!Array.isArray(options.static)) {
        options.static = [options.static];
      }

      /**
       * Enable live reload and watch view directories
       */
      options.liveReload = true;

      options.static.push({
        directory: join(__dirname, "./app/templates"),
        watch: true,
      });

      options.static.push({
        directory: join(__dirname, "assets"),
        staticOptions: {
          index: "views/index.html",
        },
      });

      // fixes cors header issues
      options.headers = {
        "Access-Control-Allow-Origin": "*",
      };

      options.open = {
        target: ["/", "http://127.0.0.1:8000/"],
        app: {
          name: "chrome",
        },
      };
    });
}

if (Encore.isDev()) {
  const linterConfig = {
    files: "assets/js/",
    fix: true,
    cache: true,
    cacheLocation: "./.cache/",
    threads: true,
  };

  Encore.addPlugin(new ESLintPlugin(linterConfig));
} else {
  Encore

    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    .enableIntegrityHashes();

  //      .addPlugin(new GenerateSW({
  // these options encourage the ServiceWorkers to get in there fast
  // and not allow any straggling "old" SWs to hang around
  //        clientsClaim: true,
  //        skipWaiting: true
  //      }))

  //      .addPlugin(new WebpackObfuscator({
  //        rotateStringArray: true
  //      }))
}

module.exports = Encore.getWebpackConfig();
/*
module.exports = {
    plugins: [
       new ESLintPlugin({
            eslintPath: 'eslint',
            fix: true,
            emitError: true,
            emitWarning: true,
        }),
        Encore.getWebpackConfig(),
    ]
};
*/
