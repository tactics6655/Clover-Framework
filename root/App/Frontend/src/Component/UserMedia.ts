declare const ImageCapture;

export default class UserMedia {
    
    static getUserMedia () {
        return window.getUserMedia || 
                window.webkitGetUserMedia || 
                window.mozGetUserMedia || 
                window.msGetUserMedia;
    }
    
    static hasGetUserMedia () {
        return !!(this.getUserMedia());
    }
    
    startRecorder (onLoadCallback, onSuccessCallback, onErrorCallback) {
        if (UserMedia.hasGetUserMedia()) {
            navigator.mediaDevices.getUserMedia({audio: true}).then(function (stream) {
                if (typeof onSuccessCallback == 'function') {
                    onSuccessCallback(stream);
                }
            }).then(function (status) {
                if (typeof onSuccessCallback == 'function') {
                    return onSuccessCallback(status);
                }
            }).catch(onErrorCallback);
        }
    }
    
    startWebCam (onLoadCallback, onSuccessCallback, onErrorCallback) {
        if (UserMedia.hasGetUserMedia()) {
            UserMedia.getDeviceUserMedia({
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
    
    async doCapture () {
        var imageCapture = await this.getImageCaptureHandler();
        var blobData;
        
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
        return UserMedia.getDeviceUserMedia({video: true}).then(function(mediaStream) {
            const mediaStreamTrack = mediaStream.getVideoTracks()[0];
            const imageCapture = new ImageCapture(mediaStreamTrack);
            return imageCapture;
        }).catch(function () {
        //error => {
            return false;
        });
    }
		
}