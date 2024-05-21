//Notify-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const _cWin;

var A;

(function ($, core) {

	A = core.Notify = {
		constructor: function () {
			this.notificationHandler = null;
		},
		
		isSupport: function () {
			if (!("Notification" in window)) {
				return false;
			}
			
			try {
				var notify = _cWin.Notification || _cWin.webkitNotifications || navigator.mozNotification;// || (_cWin.external && _cWin.external.msIsSiteMode() !== undefined);
				if (notify) {
					return true;
				}
			} catch (e) {
			}
			
			return false;
		},
		
		/**
		 * Get Permission
		 **/
		getPermit: function () {
			if (_cWin.Notification.permission !== 'denied') {
				_cWin.Notification.requestPermission(function (permission) {});
			} else if (_cWin.webkitNotifications && _cWin.webkitNotifications.checkPermission) {
				_cWin.webkitNotifications.requestPermission();
			}
		},
		
		/**
		 * Check Permission
		 **/
		getPermitLevel: function () {
			var permit;

			if (_cWin.Notification && _cWin.Notification.permissionLevel) {
				permit = _cWin.Notification.permissionLevel;
			} else if (_cWin.webkitNotifications && _cWin.webkitNotifications.checkPermission) {
				permit = Permissions[_cWin.webkitNotifications.checkPermission()];
			} else if (_cWin.Notification && _cWin.Notification.permission) {
				permit = _cWin.Notification.permission;
			} else if (navigator.mozNotification) {
				permit = Notification.permission;
			} else if (_cWin.external && _cWin.external.msIsSiteMode() !== undefined) {
				permit = _cWin.external.msIsSiteMode() ? 'granted' : 'default';
			}
			
			return permit;
		},
		
		Close: function () {
			this.notificationHandler.close();
		},
		
		/**
		 * Show Notification
		 * @param {message}         : message
		 * options : body, icon, sound, vibrate: [200, 100, 200], timestamp, silent [bool], requireInteraction: shouldRequireInteraction, lang, dir: 'rtl', data
		 **/
		Show: function (title, message, icon, body, options) {
			if (this.getPermitLevel() != 'denied') {
				var notification = null;

				if (_cWin.Notification) {
					if (!options) {
						options = {}
					}
					
					this.notificationHandler = new Notification(message, options);
				} else if (_cWin.webkitNotifications) {
					this.notificationHandler = _cWin.webkitNotifications.createNotification(icon, title, body);
					notification.show();
				} else if (navigator.mozNotification) {
					this.notificationHandler = navigator.mozNotification.createNotification(title, body, icon);
					notification.show();
				} else if (_cWin.external && _cWin.external.msIsSiteMode()) {
					_cWin.external.msSiteModeClearIconOverlay();
					_cWin.external.msSiteModeSetIconOverlay(icon, title);
					_cWin.external.msSiteModeActivate();
					notification = {};
				}
			} else {
				this.getPermit();
			}
		}
		
	};
	
})(jQuery, $.core);

export default A;