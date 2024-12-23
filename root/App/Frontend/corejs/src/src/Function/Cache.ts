//Cache-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const _cWin;

var A;

(function ($, core) {

	A = core.Cache = {
		
		isSupport: function () {
			if ('caches' in _cWin) {
				return true;
			}
			return false;
		},
		
		open: function (key, val, callback) {
			if (this.isSupported ()) {
				caches.open(key).then(function (cache) {
					cache.match(val).then(function (matchedResponse) {
						if ($.core.Validate.isFunc(callback)) {
							callback(matchedResponse);
						}
					});
				});
			}
		},
		
		del: function (key, callback) {
			if (this.isSupported ()) {
				caches.delete(key).then(function () {
					if ($.core.Validate.isFunc(callback)) {
						callback();
					}
					return true;
				});
				return false;
			}
		},
		
		add: function (key, value, request) {
			if (this.isSupported ()) {
				if (request) {
					caches.open(key).then(function (cache) {
						cache.add(new Request(value, request));
					});
				} else {
					caches.open(key).then(function (cache) {
						cache.add(value);
					});
				}
			}
		}
		
	};
	
})(jQuery, $.core);

export default A;