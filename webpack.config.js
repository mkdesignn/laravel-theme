var webpack = require("webpack");
var path = require("path");
// var projectRoot = process.env.PWD; // Absolute path to the project root
// var resolveRoot = path.join("vue-from2", 'node_modules'); // project root/node_modules
module.exports = {

    //entry: ['./storage/app/public/app.js'],
    //output:{
    //    path: path.resolve(__dirname, 'public/admin'),
    //    publicPath: "public/",
    //    filename:  'app.js'
    //},
    // resolveLoader: {
    //     root: path.join(__dirname, 'node_modules')
    // },
    // resolve: {
    //     modules: [
    //       path.join(__dirname, "src"),
    //       "node_modules"
    //     ]
    // },
    module: {
        rules:[
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            },
            {
                test: /\.scss$/,
                loader: ['style-loader', 'css-loader', 'sass-loader']
            },{
                test: /\.css$/,
                use:['style-loader', 'css-loader']
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.(png|jpg|gif|svg|ttf|woff|eot|woff2)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {}
                    }
                ]
            }
        ]
    }
}