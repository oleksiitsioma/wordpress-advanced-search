const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    plugins: [
      new MiniCssExtractPlugin({
        filename: "[name]-style.css",
      }),
    ],
    entry:{
      bundle: "./src/index.ts",
      frontend: "./src/frontend.ts"
    },    
    output: {
        path: path.resolve( __dirname + '/build/' ),
        filename: '[name]-script.js',
        clean: true
    },
    module: {
        rules: [
            {
                test: /\.(sa|sc|c)ss$/i,
                use: [
                  MiniCssExtractPlugin.loader,
                  "css-loader",
                  "sass-loader",
                ],
            }
        ]
    }
}