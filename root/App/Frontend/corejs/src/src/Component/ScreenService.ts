export class ScreenService {

    public static cancelFullScreen (element) {
        if (!this.isFullScreen(element)) {
            return false;
        }

        let requestMethod: any;

        if (element) {
            try {
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

    public static hasPip () {
        if (document.pictureInPictureElement) {
            return true;
        }
        
        return false;
    }

    public static setPip (video) {
        if (!this.hasPip()) {
            return true;
        }
        
        return video.requestPictureInPicture();
    }

    public static exitPip () {
        if (this.hasPip()) {
            return false;
        }

        return document.exitPictureInPicture();
    }

    public static getHTML5Handler (element) {
        let video: any;
        let length = element.length;

        for (let i = 0; i < length; i++) {
            video = document.createElement(element[i]);
        }

        return video;
    }

    public static hasTrueNativeFullScreen (element) {
        var video = this.getHTML5Handler(element);

        if (typeof video.msRequestFullscreen !== 'undefined') {
            return true;
        }

        return false;
    }

    public static hasMsNativeFullScreen (element) {
        var video = this.getHTML5Handler(element);

        if (
            typeof video.webkitRequestFullScreen !== 'undefined' || 
            typeof video.mozRequestFullScreen !== 'undefined' || 
            typeof video.msRequestFullscreen !== 'undefined'
        ) {
            return true;
        }
        
        return false;
    }

    public static hasMozNativeFullScreen (element) {
        let video = this.getHTML5Handler(element);

        if (typeof video.mozRequestFullScreen !== 'undefined') {
            return true;
        }
        
        return false;
    }

    public static hasWebkitNativeFullScreen (element) {
        let video = this.getHTML5Handler(element);

        if (typeof video.webkitRequestFullScreen !== 'undefined') {
            return true;
        }
        
        return false;
    }
    
    public static hasNativeFullscreen (element) {
        let video = this.getHTML5Handler(element);

        if (typeof video.requestFullscreen !== 'undefined') {
            return true;
        }
        
        return false;
    }
    
    public static hasSemiNativeFullScreen (element) {
        let video = this.getHTML5Handler(element);

        if (typeof video.webkitEnterFullscreen !== 'undefined') {
            return true;
        }
        
        return false;
    }

    public static requestFullScreen (element) {
        if (this.isFullScreen(element)) {
            return false;
        }

        let requestMethod: any;

        try {
            if (element.requestFullscreen) {
                requestMethod = element.requestFullscreen();
            } else {
                requestMethod = element.webkitRequestFullScreen(); // Element.ALLOW_KEYBOARD_INPUT
            }
            
            if (element.mozRequestFullScreen || element.mozRequestFullScreen) {
                requestMethod = element.mozRequestFullScreen();
            } else if (element.webkitRequestFullScreen || this.hasWebkitNativeFullScreen) {
                requestMethod = element.webkitRequestFullScreen();
            } else if (element.mozRequestFullScreen || this.hasMozNativeFullScreen) {
                requestMethod = element.mozRequestFullScreen();
            } else if (element.msRequestFullscreen || this.hasMsNativeFullScreen) {
                requestMethod = element.msRequestFullscreen();
            } else {
                requestMethod = element.requestFullScreen || element.webkitRequestFullScreen() || element.mozRequestFullScreen || element.msRequestFullScreen;
            }
        } catch(e) {
            return false;
        }
        
        if (requestMethod) {
            requestMethod.call(element);
        }
        
        return true;
    }

    public static toggleFullScreen (element) {
        if (this.isFullScreen(element)) {
            return this.cancelFullScreen(element);
        } 

        return this.requestFullScreen(element);
    }

    public static getScreenColorDepth () {
        return window.screen.colorDepth;
    }

    public static isFullScreen (element) {
        let isFull = false;

        if (element) {
            if (this.hasMozNativeFullScreen) {
                isFull = element.mozFullScreen;
            } else if (this.hasWebkitNativeFullScreen) {
                isFull = element.webkitIsFullScreen;
            } else if (this.hasMsNativeFullScreen) {
                isFull = element.msFullscreenElement;
            } else {
                isFull = element.fullscreenElement || element.mozFullScreenElement || element.webkitFullscreenElement || element.msFullscreenElement;
            }
        } else {
            isFull = document.fullScreen || document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement || document.fullscreenElement;
        }
        
        if (isFull || Math.abs(screen.width - window.innerWidth) < 10) {
            return true;
        }
        
        return false;
    }
}