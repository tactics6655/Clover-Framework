//Generator-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

var A;

(function ($, core) {

	A = core.Generator = {
		
		uniqueRandIterators: function* (min, max, length) {
			var str = $.core.Str.getUniqueRand(min, max, length);
			var idx = 0;
			
			while (idx < str.length) {
				yield str[idx++];
			}
		},
		
		uniqueStrIterators: function* (length) {
			var str = $.core.Str.randomStr(length);
			var idx = 0;
			
			while (idx < str.length) {
				yield str[idx++];
			}
		},
		
		getUniqueNum: function (min, max, length) {
			this.IteratorsTemp = this.uniqueRandIterators(min, max, length);
		},
		
		getUniqueStr: function (length) {
			this.IteratorsTemp = this.uniqueStrIterators(length);
		},
		
		getNext: function (IteratorsTemp) {
			return IteratorsTemp.next().value;
		}
		
	};
	
})(jQuery, $.core);

export default A;