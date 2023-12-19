//Screen-related functions
'use strict';

(function ($, core) {

	var A = core.Screen = {
		
		getResolution: function () {
		},
		
		getDepth: function () {
		},
		
		
		/**
		 * Cancel Full Screen
		 * @param {element}         : element
		 **/
		cancelFullScreen: function (element) {
			if (this.isFullScreen(element)) {
				if (element) {
					try {
						var requestMethod;
						if (element.exitFullscreen) {
							requestMethod = element.exitFullscreen();
						} else if (element.cancelFullScreen) {
							requestMethod = element.cancelFullScreen();
						} else if (element.mozCancelFullScreen || this.hasMozNativeFullScreen) {
							requestMethod = element.mozCancelFullScreen();
						} else if (element.webkitExitFullScreen || element.webkitCancelFullScreen || this.hasWebkitNativeFullScreen) {
							requestMethod = element.webkitExitFullScreen() || element.webkitCancelFullScreen();
						} else if (element.msExitFullscreen || this.hasMsNativeFullScreen) {
							requestMethod = element.msExitFullscreen();
						}
					} catch (e) {
						var requestMethod;
						//var requestMethod = element.webkitExitFullScreen || element.cancelFullScreen || element.webkitCancelFullScreen || element.msExitFullscreen || element.mozCancelFullScreen || element.msCancelFullScreen || element.exitFullscreen;
						if (document.exitFullscreen) {
							requestMethod = document.exitFullscreen();
						} else if (document.mozCancelFullScreen) {
							requestMethod = document.mozCancelFullScreen();
						} else if (document.webkitCancelFullScreen) {
							requestMethod = document.webkitCancelFullScreen();
						} else if (document.msExitFullscreen) {
							requestMethod = document.msExitFullscreen();
						}
					}
				} else {
					var requestMethod;
					if (document.exitFullscreen) {
						requestMethod = document.exitFullscreen();
					} else if (document.mozCancelFullScreen) {
						requestMethod = document.mozCancelFullScreen();
					} else if (document.webkitCancelFullScreen) {
						requestMethod = document.webkitCancelFullScreen();
					} else if (document.msExitFullscreen) {
						requestMethod = document.msExitFullscreen();
					}
				}
				
				try{
					if (requestMethod) {
						requestMethod.call(element);
					}
				} catch(e) {}
				return false;
			}
		},
		
		hasPip: function () {
			if (document.pictureInPictureElement) {
				return true;
			}
			
			return false;
		},
		
		setPip: function (video) {
			if (!this.hasPip()) {
			video.requestPictureInPicture().catch(function () {
				//error => {
					return false
				});
				
				return true;
			}
		},
		
		exitPip: function () {
			if (this.hasPip()) {
				document.exitPictureInPicture().catch(function () {
				//error => {
					return false
				});
				
				return true;
			}
		},
		
		getHTML5Handler: function () {
			var video;
			this.length = html5Elements.length;
			for (var i = 0; i < this.length; i++) {
				video = $.core.Element.create(html5Elements[i]);
			}
			return video;
		},
		
		hasTrueNativeFullScreen: function () {
			var video = this.getHTML5Handler();
			if (typeof video.msRequestFullscreen !== 'undefined') {
				return true;
			}
			return false;
		},
		
		hasMsNativeFullScreen: function () {
			var video = this.getHTML5Handler();
			if (
				typeof video.webkitRequestFullScreen !== 'undefined' || 
				typeof video.mozRequestFullScreen !== 'undefined' || 
				typeof video.msRequestFullscreen !== 'undefined'
			) {
				return true;
			}
			
			return false;
		},
		
		hasMozNativeFullScreen: function () {
			var video = this.getHTML5Handler();
			if (typeof video.mozRequestFullScreen !== 'undefined') {
				return true;
			}
			
			return false;
		},
		
		hasWebkitNativeFullScreen: function () {
			var video = this.getHTML5Handler();
			if (typeof video.webkitRequestFullScreen !== 'undefined') {
				return true;
			}
			
			return false;
		},
		
		hasNativeFullscreen: function () {
			var video = this.getHTML5Handler();
			if (typeof video.requestFullscreen !== 'undefined') {
				return true;
			}
			
			return false;
		},
		
		hasSemiNativeFullScreen: function () {
			var video = this.getHTML5Handler();
			if (typeof video.webkitEnterFullscreen !== 'undefined') {
				return true;
			}
			
			return false;
		},
		
		/**
		 * Request Full Screen
		 * @param {element}         : element
		 **/
		requestFullScreen: function (element) {
			if (!this.isFullScreen(element)) {
				try {
					if (element.requestFullscreen) {
						var requestMethod = element.requestFullscreen();
					} else {
						var requestMethod = element.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
					}
					
					if (element.mozRequestFullScreen || element.mozRequestFullScreen) {
						var requestMethod = element.mozRequestFullScreen();
					} else if (element.webkitRequestFullScreen || this.hasWebkitNativeFullScreen) {
						var requestMethod = element.webkitRequestFullScreen();
					} else if (element.mozRequestFullScreen || this.hasMozNativeFullScreen) {
						var requestMethod = element.mozRequestFullScreen();
					} else if (element.msRequestFullscreen || this.hasMsNativeFullScreen) {
						var requestMethod = element.msRequestFullscreen();
					} else {
						var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) || element.mozRequestFullScreen || element.msRequestFullScreen;
					}
				} catch(e) {
					console.log(e);
				}
				
				if (requestMethod) {
					requestMethod.call(element);
				}
				
				return false;
			}
		},
		
		/**
		 * Toggle Full Screen
		 * @param {element}         : element
		 **/
		toggleFullScreen: function (element) {
			if (this.isFullScreen(element)) {
				return this.cancelFullScreen(element);
			} else {
				return this.requestFullScreen(element);
			}
			
			return false;
		},
		
		
		getScreenColorDepth: function () {
			return window.screen.colorDepth;
		},
		
		/**
		 * Check Browser Support Full Screen
		 * @param {element}     : element
		 **/
		isFullScreen: function (element) {
			if( (screen.availHeight || screen.height-30) <= window.innerHeight) {
				return true;
			}
			
			return false;
			
			$.core.Base.resetWinCache();
			if (element) {
				if (this.hasMozNativeFullScreen) {
					var isFull = element.mozFullScreen;
				} else if (this.hasWebkitNativeFullScreen) {
					var isFull = element.webkitIsFullScreen;
				} else if (this.hasMsNativeFullScreen) {
					var isFull = element.msFullscreenElement;
				} else {
					var isFull = element.fullscreenElement || element.mozFullScreenElement || element.webkitFullscreenElement || element.msFullscreenElement;
				}
			} else {
				var isFull = document.fullScreen || document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement || document.fullscreenElement;
			}
			
			var isFull = document.fullScreen || document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement || document.fullscreenElement;
			
			if (isFull || Math.abs(screen.width - _cWin.innerWidth) < 10) {
				return true;
			}
			
			return false;
		}
		
	}
	
})(jQuery, $.core);
