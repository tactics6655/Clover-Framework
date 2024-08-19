const path = require('path');
//const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const BrotliPlugin = require('brotli-webpack-plugin');
const WebpackObfuscator = require('webpack-obfuscator');
const webpack = require('webpack')
const ForkTsCheckerWebpackPlugin = require('fork-ts-checker-webpack-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

module.exports = {
	resolve: {
		extensions: [".ts", ".tsx", ".js"]
	},
	plugins: [
		new WebpackManifestPlugin({
		  publicPath: '/',
		}),
		new MiniCssExtractPlugin({
		  filename: '[name].[contenthash].css',
		}),
		new ForkTsCheckerWebpackPlugin(),
		/*new CompressionPlugin({
		}),*/
		new webpack.ids.DeterministicChunkIdsPlugin({
			maxLength: 5,
		}),
		/*new BrotliPlugin({
			threshold: 10240,
			minRatio: 0.8
		}),
		new WebpackObfuscator ({
			"rotateStringArray": true
		})*/
	],
	mode: "production",
	entry: [
		'./index.tsx'
	],
	output: {
		filename: '[name].bundle.js',
		path: path.resolve(__dirname, './../compiled'),
		libraryTarget: "umd",
    	library: 'coreJS',
		clean: true
	},
	target: "web",
	optimization: {
		mangleExports: true,
		minimize: true,
		minimizer: [
			new CssMinimizerPlugin(),
			new TerserPlugin({
				cache: true,
				sourceMap: false,
				extractComments: false
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
			maxSize: 1500000,
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
				},
				commons: {
				  test: /[\\/]node_modules[\\/]/,
				  name: 'vendors',
				  chunks: 'all',
				},
			}
		}
	},
	name: 'coreJS',
	cache: true,
	cache: {
		type: 'filesystem',
		allowCollectingMemory: true,
		//compression: 'gzip',
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
	entry: {
	  coreJS: ['react', 'react-dom', './index.tsx']
	},
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
			use: [
				MiniCssExtractPlugin.loader,
				'css-loader'
			],
		},
		{
			test: /\.[jt](s|sx)$/,
			exclude: /(node_modules)/,
			use: [
				{
					loader: 'babel-loader',
					options: {
						presets: [
							"@babel/preset-env", 
							["@babel/preset-react", {"runtime": "automatic"}],
							"@babel/preset-typescript"
						]
					},
				}
			],
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
