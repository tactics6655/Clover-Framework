//GEO-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const _cNavi;

var A;

(function ($, core) {

	A = core.GEO = {
		
		isSupport: function () {
			return (navigator.geolocation) ? true : false;
		},
		
		Get: function () {
			if (!this.isSupported ()) return;
			
			var options = {
				enableHighAccuracy: true,
				timeout: 5000,
				maximumAge: 0
			};
			
			return new Promise(function (resolve, reject) {
				_cNavi.geolocation.getCurrentPosition(resolve, reject, options);
			});
		}
		
	};
	
})(jQuery, $.core);

export default A;