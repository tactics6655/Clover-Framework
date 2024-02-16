//Selector-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

var A;

(function ($, core) {

	A = core.Selector = {
		
		first: function (elem) {
			return $(elem + ':first');
		},
		
		firstChild: function (elem) {
			return $(elem + ':first-child');
		},
		
		last: function (elem) {
			return $(elem + ':last');
		},
		
		lastChild: function (elem) {
			return $(elem + ':last-child');
		},
		
		onlyChild: function (elem) {
			return $(elem + ':only-child');
		},
		
		child: function (elem, th) {
			if ($.core.Validate.isUndefined(th)) {
				return this._first(elem);
			} else {
				return $(elem + ':nth-child(' + th + ')');
			}
		},
		
		/**
		 * Every Other
		 **/
		even: function (elem) {
			return $(elem + ':even');
		}
		
	};
	
})(jQuery, $.core);

export default A;