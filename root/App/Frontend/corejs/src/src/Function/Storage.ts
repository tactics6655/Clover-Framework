//Storage-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

export class QUOTA_EXCEEDED_ERR extends Error {
    /**
     * Message explaining the error.
     */
    message: string;
    /**
     * Creates a specific `Error` object for **Quota Exceeded Errors**.
     * @param message Message explaining the error.
     */
}

declare const _cWin;
declare const $cache;

var A;

(function ($, core) {

	A = core.Storage = {
		
		/**
		 * Check Storage is Support
		 **/
		isSupport: function () {
			if (!window.localStorage) {
				return false;
			}
			
			this._isSupport = true;
			
			if ($.core.Validate.isUndefined($cache['isLocalStorageSupport'])) {
				var _prefix = 'test';
				var _data = 'tmp';
				
				if (typeof localStorage == 'object') {
					try {
						localStorage.setItem(_prefix, _data);
						var storageData = localStorage.getItem(_prefix);
						if (storageData === _data) {
							localStorage.removeItem(_prefix);
						} else {
							this.isSupport = false;
						}
					} catch(e) {
						this.isSupport = false;
					}
					
					$cache['isLocalStorageSupport'] = this.isSupport;
				}
			} else {
				return $cache['isLocalStorageSupport'];
			}
		},
		
		/**
		 * Get Storage
		 * @param {name}         : name
		 **/
		getItem: function (name) {
			if (this.isSupported () == true) {
				var dataStorage = _cWin.localStorage.getItem(name);
				
				if (!dataStorage) {
					return false;
				}
				
				dataStorage = $.core.JSON.autoDecode(dataStorage);
				return dataStorage;
			}
		},
		
		isEmpty: function (name) {
			if (this.getItem(name) === null) {
				return true;
			}
			
			return false;
		},
		
		/**
		 * Set Storage
		 * @param {name}         : name
		 * @param {value}        : value
		 **/
		setItem: function (name, value) {
			if (this.isSupported () == true) {
				try {
					_cWin.localStorage.setItem(name, value);
					$.core.Base.resetWinCache();
				} catch (Exception) {
					if (Exception == QUOTA_EXCEEDED_ERR) {
						return false;
					}
				}
				
				return true;
			}
		},
		
		/**
		 * Empty Storage
		 * @param {name}        : name
		 **/
		setEmpty: function (name) {
			if (this.isSupported () == true && this.getItem(name)) {
				try {
					_cWin.localStorage.removeItem(name);
					$.core.Base.resetWinCache();
				} catch (e) {
					$.log('Failed Empty ' + name + ' local Storage');
				}
			}
		}
		
	};
	
})(jQuery, $.core);

export default A;