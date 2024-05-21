import {UserMediaService} from './UserMediaService';

export default class WebCam {

    private recorder;

    constructor() {

    }

    startWebCam (onLoadCallback, onSuccessCallback, onErrorCallback) {
        if (UserMediaService.hasGetUserMedia()) {
            UserMediaService.getDeviceUserMedia({
                video: true
            }).then(function (mediaStream) {
                if (typeof onLoadCallback == 'function') {
                    return onLoadCallback(mediaStream);
                }
            }).then(function (status) {
                if (typeof onSuccessCallback == 'function') {
                    return onSuccessCallback(status);
                }
            }).catch(onErrorCallback);
        }
    }

    takeSnapshot (id, filetype, vid) {
        if (!filetype) {
            filetype = 'image/jpeg';
        }
        
        const canvas = document.createElement(id);
        const ctx = canvas.getContext('2d');
        canvas.width = vid.videoWidth;
        canvas.height = vid.videoHeight;
        ctx.drawImage(vid, 0,0);
        
        return new Promise(function(res, rej) {
            canvas.toBlob(res, filetype);
        });
    }
    
    captureVideo (selector, onSuccessCallback, onErrorCallback) {
        const vid = document.querySelector(selector);
        
        this.startWebCam(function (stream) {
            vid.srcObject = stream;
            return vid.play(); 
        }, function(args) {
            if (typeof onSuccessCallback !== 'function') {
                return;
            }

            onSuccessCallback(args);
        }, function(args) {
            if (typeof onErrorCallback !== 'function') {
                return;
            }
            
            onErrorCallback(args);
        });
    }
}