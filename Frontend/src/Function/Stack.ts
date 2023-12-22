//Stack-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const Canvas2D;

var A;

(function ($, core) {
	
	A = core.Stack = {
		initialize: function (canvasID) {
			return new Canvas2D(canvasID);
		},
		
		push: function(stack) {
		},
		
		pull: function() {
		}
	};
	
})(jQuery, $.core);

export default A;