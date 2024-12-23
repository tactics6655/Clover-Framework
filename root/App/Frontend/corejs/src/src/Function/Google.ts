//Google-related functions
'use strict';

import $ from 'jquery';
import jQuery from 'jquery';

declare const ga;

var A;

(function ($, core) {

	A = core.Google = {
		
		/**
		 * Check Website has Google Adsence
		 *
		 * @return {boolean}
		 **/
		hasAdsence: function () {
			if ($('ins.adsbygoogle').length) {
				return true;
			}
			
			return false;
		},
		
		/**
		 * Check Website has Google Analytics
		 *
		 * @return {boolean}
		 **/
		hasAnalytics: function () {
			if (window.ga) {
				return true;
			}
			
			return false;
		},
		
		/**
		 * Send Authentication Key to Google Adsence
		 * @param {key}	: Authentication key
		 **/
		sendAdsence: function (key) {
			var adsbygoogle;
			
			(adsbygoogle = window.adsbygoogle || []).push({});
			(adsbygoogle = window.adsbygoogle || []).push({
				google_ad_client: key,
				enable_page_level_ads: true
			});
		},
		
		/**
		 * Send Authentication Key to Google Analytics
		 * @param {key}	: Authentication key
		 **/
		sendAnalytics: function (key) {
			(function (i: any, s: any, o: any, g: any, r: any, a, m) {
				i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * Number(new Date()); a = s.createElement(o),
				m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
			})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

			ga('create', key, 'auto');
			ga('send', 'pageview');
		}
		
	};
	
})(jQuery, $.core);

export default A;