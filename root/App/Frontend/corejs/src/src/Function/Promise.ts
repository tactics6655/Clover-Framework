//Promise-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

var A;

(function ($, core) {

	A = core.Promise = {
		
		isSupport: function () {
			if (typeof Promise !== "undefined" && Promise.toString().indexOf("[native code]") !== -1) {
				return false;
			}
			
			return true;
		},
		
		RequestHanlder: function (request) {
			return new Promise(function (resolve, reject) {
				request.onsuccess = function () {
					resolve(request.result);
				},
				request.onerror = function () {
					reject(request.error);
				};
			});
		},
		
		MakePromise: function () {
			let _resolve;
			let _reject;
			
			let promise = new Promise(function(resolve, reject) {
				_resolve = resolve;
				_reject = reject;
			});
			
			return {
				'resolve': _resolve,
				'reject': _reject,
				'promise': promise
			};
		},
		
		Promisify: function () {
			return function () {
			};
		},
		
		RequestCall: function (obj, method, args) {
			var request;
			var p: any = new Promise(function (resolve, reject) {
				request = obj[method].apply(obj, args);
				this.RequestHanlder(request).then(resolve, reject);
			});

			p.request = request;
			
			return p;
		}
		
	};
	
})(jQuery, $.core);

export default A;