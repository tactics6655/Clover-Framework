//WebSocket-related functions

import $ from 'jquery';
import jQuery from 'jquery';

declare let HandlerWebSocket;
declare const _cWin;

var A;

(function ($, core) {

	A = core.WebSocket = {
		
		isSupport: function () {
			if ("WebSocket" in _cWin) {
				return true;
			}
			
			return false;
		},
		
		Open: function (host, options) {
			HandlerWebSocket = new WebSocket(host, options);
			
			return HandlerWebSocket;
		},
		
		Send: function (packet) {
			HandlerWebSocket.send(packet);
		}
		
	};
	
})(jQuery, $.core);

export default A;