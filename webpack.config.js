// webpack.config.js
const Encore = require('@symfony/webpack-encore');
const path = require('path');
// const fs = require('fs');
// const MiniCssExtractPlugin = require('mini-css-extract-plugin');

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath('public/build/')
  // the public path used by the web server to access the previous directory
  .setPublicPath('/build')
  // will create public/build/app.js and public/build/app.css
  .addEntry('app', ['./assets/app.js'])
  //.splitEntryChunks()
  .enableSingleRuntimeChunk()

  // allow legacy applications to use $/jQuery as a global variable
  //.autoProvidejQuery()
  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()
  // show OS notifications when builds finish/fail:
  // .enableBuildNotifications()
  // create hashed filenames (e.g. app.abc123.css)
  .enableVersioning()
  .configureMiniCssExtractPlugin(() => {}, (options) => {
    options.filename = '[name].[contenthash].css';
    options.chunkFilename = '[id].[contenthash].css';
})
.enableLessLoader((options) => {
      options.lessOptions = {
          javascriptEnabled: true
      };
  })

  // React specific build
  .enableReactPreset()

  .configureBabel(function (babelConfig) {
    babelConfig.plugins.push('@babel/plugin-proposal-class-properties');
    babelConfig.plugins.push('@babel/plugin-transform-runtime');
    babelConfig.plugins.push(["import", {
        "libraryName": "antd",
        "style": false,
    }, 'antd-import']);
    babelConfig.plugins.push(["import", {
        "libraryName": "lodash",
        libraryDirector: '',
        "camel2DashComponentName": false,
    }, 'lodash-import']);
    babelConfig.plugins.push('babel-plugin-styled-components');
    babelConfig.plugins.push('@babel/plugin-transform-template-literals');

    const preset = babelConfig.presets.find(([name]) => name === "@babel/preset-env");
    if (preset !== undefined) {
        preset[1].useBuiltIns = "usage";
        preset[1].corejs = '3.0.0';
    }
})
;

const config = Encore.getWebpackConfig();

config.devtool = Encore.isProduction() && false || 'eval-source-map';

// config.watchOptions = Object.assign({}, config.watchOptions, {
//     aggregateTimeout: 200,
//     ignored: /node_modules/
// });

// if (!Encore.isProduction()) {
//
//     const threadOptions = {
//         loader: 'thread-loader',
//         options: {
//             // additional node.js arguments
//             workerNodeArgs: ['--max-old-space-size=1024'],
//
//             // Allow to respawn a dead worker pool
//             // respawning slows down the entire compilation
//             // and should be set to false for development
//             poolRespawn: false,
//
//             // timeout for killing the worker processes when idle
//             // defaults to 500 (ms)
//             // can be set to Infinity for watching builds to keep workers alive
//             poolTimeout: 2000,
//
//             // number of jobs the poll distributes to the workers
//             // defaults to 200
//             // decrease of less efficient but more fair distribution
//             poolParallelJobs: 50,
//
//
//             // name of the pool
//             // can be used to create different pools with elsewise identical options
//             name: "my-pool"
//         }
//     };
//
//     config.module.rules[0].use.unshift('cache-loader');
//     config.module.rules[4].use.splice(1, 0, 'cache-loader');
//
//     config.module.rules[0].use.unshift(threadOptions);
// //config.module.rules[4].use.splice(1, 0, threadOptions);
//
//     const threadLoader = require('thread-loader');
//
//     threadLoader.warmup({
//         // additional node.js arguments
//         workerNodeArgs: ['--max-old-space-size=1024'],
//
//         // Allow to respawn a dead worker pool
//         // respawning slows down the entire compilation
//         // and should be set to false for development
//         poolRespawn: false,
//
//         // timeout for killing the worker processes when idle
//         // defaults to 500 (ms)
//         // can be set to Infinity for watching builds to keep workers alive
//         poolTimeout: 2000,
//
//         // number of jobs the poll distributes to the workers
//         // defaults to 200
//         // decrease of less efficient but more fair distribution
//         poolParallelJobs: 50,
//
//         // name of the pool
//         // can be used to create different pools with elsewise identical options
//         name: "my-pool"
//     }, [
//         // modules to load
//         // can be any module, i. e.
//         'babel-loader',
//         'style-loader',
//         'less-loader',
//         'fast-css-loader',
//         'resolve-url-loader',
//     ]);
//
// }

config.module.rules[0].include = [path.resolve(__dirname + '/assets'), path.resolve(__dirname + '/node_modules/@coala')];
delete config.module.rules[0].exclude;
delete config.module.rules[4].exclude;
delete config.module.rules[1];
delete config.module.rules[2];
config.module.rules = Object.values(config.module.rules);

config.optimization.splitChunks = {
    chunks: 'all',
    maxInitialRequests: Infinity,
    minSize: 0,
    cacheGroups: {
        vendor: {
            test: /[\\/]node_modules[\\/]/,
            name(module) {
                // get the name. E.g. node_modules/packageName/not/this/part.js
                // or node_modules/packageName

                const match = module.context.match(/[\\/]node_modules[\\/](.*?)([\\/]|$)/);

                if (!match) {
                    return null;
                }

                const packageName = match[1];

                // npm package names are URL-safe, but some servers don't like @ symbols
                return `npm.${packageName.replace('@', '')}`;
            },
        },
    },
};

config.output.pathinfo = false;

config.watchOptions =   {
    ignored: /node_modules/
};

module.exports = config;
