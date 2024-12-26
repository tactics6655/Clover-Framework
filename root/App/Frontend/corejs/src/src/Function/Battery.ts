//Battery-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const _cNavi;

var A;

(function ($, core) {

	core.Battery = {
		
		get: function () {
			if (!_cNavi.getBattery) {
				return false;
			}
			
			_cNavi.getBattery().then(function (battery) {
				return battery;
			}).catch(function () {
				return false;
			});
		},
		
		charingTime: function () {
			var battery;
			if (battery = this.isGet()) {
				return battery.chargingTime;
			}
		},
		
		dischargingTime: function () {
			var battery;
			if (battery = this.isGet()) {
				return battery.dischargingTime;
			}
		},
		
		level: function () {
			var battery;
			
			if (battery = this.isGet()) {
				return battery.level;
			}
		}
		
	};
	
})(jQuery, $.core);

export default A;