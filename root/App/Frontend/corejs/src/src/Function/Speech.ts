//Speech-related functions

import $ from 'jquery';
import jQuery from 'jquery';

declare const _cWin;

var A;

(function ($, core) {

	A = $.core.Speech = {
		
		Get: function () {
			return _cWin.speechSynthesis;
		},
		
		getSpeechRecognition: function () {
			return _cWin.SpeechRecognition || _cWin.webkitSpeechRecognition;
		},
		
		getVoices: function () {
			var synth = this.Get();
			return synth.getVoices();
		},
		
		getVoicesLength: function () {
			var voices = this.getVoices();
			return voices.length;
		},
		
		getPopularVoiceList: function () {
			var voiceList = [];
			var synth = this.Get();
			var voices = synth.getVoices();
			for (var i = 0; i < voices.length; i++) {
				voiceList.push(voices[i]);
			}
			
			return voiceList;
		},
		
		/*
			Microsoft Heami Desktop - Korean
			Microsoft Zira Desktop - English (United States)
		*/
		Speech: function (word, speecher, pitch, rate) {
			var i;
			var synth = this.Get();
			
			if ($.core.Validate.isArray(word)) {
				var word = $.core.Arr.getRandom(word);
			}
			
			var utterThis = new SpeechSynthesisUtterance(word);
			var voices = this.getVoices();
			
			for (i = 0; i < voices.length; i++) {
				if (voices[i].name === speecher) {
					utterThis.voice = voices[i];
				}
			}
			
			utterThis.pitch = pitch;
			utterThis.rate = rate;
			synth.speak(utterThis);
		}
		
	};
	
})(jQuery, $.core);

export default A;