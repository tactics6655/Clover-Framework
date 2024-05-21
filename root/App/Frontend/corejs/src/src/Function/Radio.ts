//Radio-related functions
//This API is available on Firefox or Firefox OS for installed or higher privileged applications.
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

var A;

(function ($, core) {

	A = core.Radio = {
		
		isSupport: function () {
			var radio = navigator.mozFMRadio;
			if (radio.antennaAvailable) {
				return true;
			}
			
			return false;
		},
		
		setFrequency: function (frequency) {
			var radio = navigator.mozFMRadio;
			radio.enable(frequency);
		}
		
	}
	
});

export default A;