//Scroll-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

var A;

(function ($, core) {

	A = core.Scroll = {
		
		/**
		 * Document Scroll to Top
		 **/
		Top: function () {
			return $(document).scrollTop(0);
		},
		
		/**
		 * Document Scroll to Bottom
		 **/
		Bottom: function () {
			return $.core.Effect.FocusAnimate($(window).height());
		}
		
	};
	
})(jQuery, $.core);

export default A;