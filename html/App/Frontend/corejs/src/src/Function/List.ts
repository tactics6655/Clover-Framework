//List-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

var A;

(function ($, core) {

	A = $.core.List = {
		
		addItem: function (id, firstname) {
			var node = document.createElement("LI");
			var text = document.createTextNode(firstname);
			
			node.appendChild(text);
			document.getElementById(id).appendChild(node);
		},
		
		setChildrenDraggable: function (rootEl) {
			[].slice.call(rootEl.children).forEach(function (elem) {
				elem.draggable = true;
			});
		}
		
	};
	
})(jQuery, $.core);

export default A;