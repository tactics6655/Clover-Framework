//ChromeExtend-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const chrome;
declare const _cWin;

var A;

(function ($, core) {

	A = core.ChromeExtend = {
		
		appCreate: function (html, id, maxWidth, maxHeight, minWidth, minHeight) {
			chrome.app.window.create(
				html, {
					id: id,
					innerBounds: {
						maxWidth: maxWidth || 600,
						maxHeight: maxHeight || 300,
						minWidth: minWidth || 600,
						minHeight: minHeight || 300
					},
		
					frame: 'none'
				}
			);
		},
		
		getAppLaunchedEvent: function (callback) {
			chrome.app.runtime.onLaunched.addListener(function () {
				callback();
			});
		},
		
		setWallpaper: function (img_url, filename) {
			chrome.wallpaper.setWallpaper(
				{
					url:img_url,
					layout:"CENTER_CROPPED",
					filename:filename
				},
		
				function (thumbnail) {}
			);
		},
		
		isSupport: function () {
			if (navigator.userAgent.toLowerCase().indexOf("chrome") != -1 && $.core.Browser.getChromeVersion() >= 42) {
				return true;
			} else {
				return false;
			}
		},
		
		getAPP: function (APP_ID) {
			_cWin.open("https://chrome.google.com/webstore/detail/" + APP_ID);
		},
		
		sendMessage: function (APP_ID, callback) {
			if (this.isSupport()) {
				chrome.runtime.sendMessage(APP_ID, function (response) {
					callback(response);
				});
			}
		}
		
	};
	
})(jQuery, $.core);

export default A;