//Popup-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const _cWin;

var A;

(function ($, core) {

	A = core.Popup = {
		
		constructor: function () {
			this.popupHandler = null
		},
		
		/**
		 * Open Popup Windows
		 * @param {url} 	  : Request URL
		 * @param {width}  : Windows Width
		 * @param {height} : Windows Height
		 * @param {p_top}  : Top Position
		 * @param {p_left} : Left Position
		 **/
		Open: function (url, width, height, p_top, p_left) {
			if (this.Enabled()) {
				this.popupHandler = _cWin.open(url, 'popup', 'top=' + p_top + ',left=' + p_left + ',width=' + width + 'px,height=' + height + 'px,status=no,scrollbars=no,toolbar=no,resizable=no,scrollbars=no,location=no');
			}
		},
		
		openLink: function (link) {
			if (link) {
				var url = link.getAttribute("href");
				if (url) {
					return window.open(url);
				}
			}
		},
		
		/**
		 * Open Circle Popup Windows
		 * @param {href}        : Request URL
		 * @param {circleSize}  : Circle Windows Size
		 **/
		openCircle: function (href, circleSize) {
			$.core.Popup.Open(href, circleSize, circleSize, parseInt(String(($.core.Element.getInnerHeight() / 2) - parseInt(String(circleSize / 2))), 10), parseInt(String(($.core.Element.getInnerWidth() / 2) - parseInt(String(circleSize / 2))), 10));
		},
		
		/**
		 * Return Popup is Enabled
		 */
		Enabled: function () {
			var vPopup = _cWin.open('about:blank', 'win', 'width=1, height=1, scrollbars=yes, resizable=yes');
			var isDisabled = !vPopup || vPopup.closed || typeof vPopup.closed == 'undefined';
			
			if (!isDisabled) {
				vPopup.close();
			}
			
			return isDisabled ? false : true;
		},
		
		closeSelf: function (location) {
			open(location, '_self').close();
		},
		
		/**
		 * Close Popup
		 */
		Close: function () {
			if ($.core.Validate.isObject(this.popupHandler)) {
				this.popupHandler.close();
			}
		}
		
	};
	
	A.constructor();
	
})(jQuery, $.core);

export default A;