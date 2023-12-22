// Create a Component

import jQuery from 'jquery'

var core;

(function ($) {
	core = core || function (element) {
		this.element = $(element);
	};
	
	// The current version of Core Javascript
	core.version = '1.0';
	
	$.core = {};
	$.core = $.core || function () {
		$.extend(this, $.core);
		
		return this;
	};
})(jQuery);

export default core;
