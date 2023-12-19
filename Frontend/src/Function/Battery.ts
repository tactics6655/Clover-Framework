//Battery-related functions
'use strict';

(function ($, core) {

	var A = core.Battery = {
		
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