const path = require('path');
//const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const BrotliPlugin = require('brotli-webpack-plugin');
const WebpackObfuscator = require('webpack-obfuscator');
const webpack = require('webpack')

module.exports = {
	plugins: [
		new CompressionPlugin({
		}),
		new webpack.ids.DeterministicChunkIdsPlugin({
			maxLength: 5,
		}),
		/*new BrotliPlugin({
			threshold: 10240,
			minRatio: 0.8
		}),*/
		/*new WebpackObfuscator ({
            "rotateStringArray": true
        })*/
	],
	mode: "production",
	entry: [
		'./index.js'
	],
	output: {
		filename: './../../../html/common/assets/js/coreJS/dist/coreJS.js',
		path: path.resolve(__dirname, 'dist'),
		libraryTarget: "umd"
	},
	target: "web",
	optimization: {
		mangleExports: true,
		minimize: true,
		minimizer: [
			new TerserPlugin({
				cache: true,
				sourceMap: true
			}), 
			/*new UglifyJsPlugin({
				include: /\.js$/,
				uglifyOptions: {
					parallel: true,
					mangle: true,
					minimize: true,
					compress: true,
					toplevel: true,
                    screw_ie8: true
				}
			}),*/
		],
		nodeEnv: 'production',
		removeEmptyChunks: true,
		mergeDuplicateChunks: true,
		flagIncludedChunks: true,
		concatenateModules: true,
		sideEffects: true,
		//moduleIds: 'hashed',
		usedExports: true,
		providedExports: true,
		chunkIds: 'total-size',
		moduleIds: 'size',
		splitChunks: {
			chunks: 'async',
			minSize: 30000,
			maxSize: 0,
			minChunks: 1,
			maxAsyncRequests: 5,
			maxInitialRequests: 3,
			automaticNameDelimiter: '~',
			//name: true,
			cacheGroups: {
				vendors: {
					test: /[\\/]node_modules[\\/]/,
					priority: -10
				}, 
				default: {
					minChunks: 2,
					priority: -20,
					reuseExistingChunk: true
				}
			}
		}
	},
	name: 'coreJS',
	cache: true,
	cache: {
		type: 'filesystem',
		allowCollectingMemory: true,
		compression: 'gzip',
		store: 'pack',
	},
	amd: {
		jQuery: true
	},
	target: "web",
	performance: {
		hints: 'error',
		maxEntrypointSize: 4000000,
		maxAssetSize: 1000000
	},
	externals: ['jquery'],
	module: {
		rules: [{
			test: /\.js$/,
			exclude: /node_modules/,
			use: {
				loader: 'babel-loader',
				options: {
					presets: ['@babel/preset-env']
					//,plugins: ['@babel/plugin-transform-runtime']
				}
			}
		}, {
			test: /\.css$/,
			use: ['style-loader', 'css-loader']
		}],
		exprContextCritical: false,
		exprContextRecursive: true,
		exprContextRegExp: true,
		exprContextRequest: '.',
		unknownContextCritical: false,
		unknownContextRecursive: false,
		unknownContextRegExp: false,
		unknownContextRequest: '.',
		wrappedContextCritical: true,
		wrappedContextRecursive: true,
		wrappedContextRegExp: /.*/,
		strictExportPresence: true // since webpack 2.3.0
	}
};
