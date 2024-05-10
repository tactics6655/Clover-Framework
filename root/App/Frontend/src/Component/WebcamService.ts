import {UserMediaService} from './UserMediaService';

export class WebcamService {

    private recorder: MediaRecorder;

    constructor () {
        this.recorder = null;
    }
    
    stopCaptureAudio () {
        this.recorder.stop();
    }
    
    captureAudio () {
        var onRecordingReady = function (e) {
            var audio: any = document.getElementById('audio');
            audio.src = URL.createObjectURL(e.data);
        };
        
        UserMediaService.startRecorder(function (stream) {
            this.recorder = new MediaRecorder(stream);
            this.recorder.addEventListener('dataavailable', onRecordingReady);
        }, function () {

        });
    }
    
    captureVideo (selector, onSuccessCallback, onErrorCallback) {
        const video = document.querySelector(selector);
        
        UserMediaService.startWebCam(function (stream) {
            video.srcObject = stream;
            return video.play(); 
        }, function(args) {
            if (typeof onSuccessCallback == 'function') {
                onSuccessCallback(args);
            }
        }, function(args) {
            if (typeof onErrorCallback == 'function') {
                onErrorCallback(args);
            }
        });
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
    
    downloadSnapshot (blob, filename) {
        let a = document.createElement('a'); 
        a.href = URL.createObjectURL(blob);
        a.download = filename;
        document.body.appendChild(a);
        a.click();
    }
    
}