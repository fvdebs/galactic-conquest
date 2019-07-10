const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyPlugin = require('copy-webpack-plugin');

module.exports = {
    entry: './frontend/main.js',
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: 'assets/script.js'
    },
    module: {
        rules: [
            {
                test: /\.(scss|sass)$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader'
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            },
            {
                test: /\.(png|jpe?g|gif)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[path][name].[ext]',
                            context: path.resolve(__dirname, "frontend"),
                            publicPath: '../'
                        },
                    },
                ],
            },
            {
                test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            publicPath: 'fonts',
                            outputPath: 'assets/fonts'
                        }
                    }
                ]
            }
       ]
    },
    plugins: [
        new CopyPlugin([
            { from: 'frontend/assets/images', to: 'assets/images' }
        ]),
        new MiniCssExtractPlugin({
            filename: 'assets/style.css'
        }),
    ]
};