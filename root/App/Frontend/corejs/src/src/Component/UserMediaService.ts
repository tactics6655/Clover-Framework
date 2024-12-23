declare const ImageCapture;

import * as ErrorMessages from './../Variables/ErrorMessages';

export class UserMediaService {
    
    static getUserMedia () {
        if (navigator.mediaDevices.getUserMedia === undefined) {
            navigator.mediaDevices.getUserMedia = function(constraints) {
                var getUserMedia = (navigator as any).webkitGetUserMedia || (navigator as any).mozGetUserMedia;
        
                if (!getUserMedia) {
                    return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
                }
                
                return new Promise(function(resolve, reject) {
                    getUserMedia.call(navigator, constraints, resolve, reject);
                });
            }
        }

        return window.getUserMedia || 
                window.webkitGetUserMedia || 
                window.mozGetUserMedia || 
                window.msGetUserMedia ||
                navigator.mediaDevices.getUserMedia;
    }
    
    static hasGetUserMedia() :boolean {
        return !!(navigator.mediaDevices.getUserMedia || (navigator as any).getUserMedia || (navigator as any).webkitGetUserMedia || (navigator as any).mozGetUserMedia || (navigator as any).msGetUserMedia);
    }
    
    static startRecorder (onSuccessCallback, onErrorCallback) {
        if (!UserMediaService.hasGetUserMedia()) {
            throw new Error(ErrorMessages.USER_MEDIA_IS_NOT_SUPPORTED);
        }

        navigator.mediaDevices.getUserMedia({audio: true}).then(function (stream) {
            if (typeof onSuccessCallback !== 'function') {
                return;
            }

            onSuccessCallback(stream);
        }).then(function (status) {
            if (typeof onSuccessCallback == 'function') {
                return;
            }
            
            onSuccessCallback(status);
        }).catch(onErrorCallback);
    }
    
    static startWebCam (onLoadCallback, onSuccessCallback, onErrorCallback) {
        if (!UserMediaService.hasGetUserMedia()) {
            throw new Error(ErrorMessages.USER_MEDIA_IS_NOT_SUPPORTED);
        }

        UserMediaService.getDeviceUserMedia({
            video: {
                facingMode: {
                    extract: "environment"
                },
                width: {min: 100, ideal: 4096, max:4096},
                height: {min: 100, ideal: 4096, max:4096},
                frameRate: {ideal: 30}
            },
            audio: true
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
    
    async doCapture () {
        let imageCapture = await this.getImageCaptureHandler();
        let blobData = null;
        
        imageCapture.takePhoto().then(function (blob) {
            blobData = blob;
        }).catch(function () {
            return false;
        });
        
        return blobData;
    }
    
    newImageCapture (mediaStream) {
        var mediaStreamTrack = mediaStream.getVideoTracks()[0];
        
        //if ($.core.Validate.isObject(mediaStreamTrack)) {
        //    return new ImageCapture(mediaStreamTrack);
        //}
    }
    
    static getDeviceUserMedia (params) {
        return window.navigator.mediaDevices.getUserMedia(params);
    }
    
    getImageCaptureHandler () {
        return UserMediaService.getDeviceUserMedia({video: true}).then(function(mediaStream) {
            const mediaStreamTrack = mediaStream.getVideoTracks()[0];
            const imageCapture = new ImageCapture(mediaStreamTrack);
            return imageCapture;
        }).catch(function (error) {
            return false;
        });
    }
		
}