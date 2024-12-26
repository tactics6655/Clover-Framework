//Browser-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const _cWin;

var A;

(function ($, core) {

	A = core.RTC = {
		
		getRTCPeerConnection: function () {
			_cWin.RTCPeerConnection = _cWin.RTCPeerConnection || 
									  _cWin.webkitRTCPeerConnection || 
									  _cWin.mozRTCPeerConnection;
									  
			return _cWin.RTCPeerConnection;
		},
		
		hasRTCPeerConnection: function () {
			return !!(this.getRTCPeerConnection());
		},
		
    }

});

export default A;