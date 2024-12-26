//Base-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare let _cNavi;
declare let _cWin;

var A;

(function ($, core) {

	A = core.Base = {
		
		resetNaviCache: function () {
			_cNavi = navigator;
		},
		
		resetWinCache: function () {
			_cWin = window;
		}
		
	};
	
})(jQuery, $.core);

export default A;