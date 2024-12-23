enum RecorderEvent {
    ON_DATA_AVAILABLE = 'ondataavailable',
    LOADED_METADATA = 'loadedmetadata',
    ON_STOP = 'onstop',
    ON_RESUME = 'onresume',
    ON_PAUSE = 'onpause',
    ON_START = 'onstart',
    ON_ERROR = 'onerror',
    ON_FREQUENCY = 'onfrequency',
    ON_DETECT = 'ondetect'
}

enum ErrorMessages {
    NOT_IMPLEMENTED_API = 'getUserMedia is not implemented in this browser',
    NOT_INITIALIED = 'Media recorder is not initialized',
    NOT_STARTED = 'Cannot start MediaRecorder when already recording'
}

enum RecordAction {
    STOP = 'stop',
    RECORD = 'record',
    PAUSE = 'pause', 
    RESUME = 'resume'
}

enum RecordState {
    UNINITIALIZED = 'uninitialized',
    RECORDING = 'recording',
    INACTIVE = 'inactive',
    PAUSED = 'paused'
}

enum PermissionStates {
    DENIED = 'denied',
    GRANTED = 'granted',
    PROMPT = 'prompt',
}

enum Codecs {
    OPERS = 'codecs=opus',
    VP9 = 'codecs=vp9',
    H264 = 'codecs=h264'
}

enum MimeTypes {
    MP3 = 'audio/mpeg',
    WAV = 'audio/wav',
    OGG = 'audio/ogg',
    WEBM = 'audio/webm',
    MP4 = 'video/mp4',
    MPEG = 'video/mpeg'
}

interface AudioRecorderInterface {
    hasGetUserMedia() :boolean;
    useAutocorrelation(use: boolean): void;
    clearSpectrum() :void;
    setSpectrum() :void;
    clearRecordChunks() :void;
    getDeviceList() :Promise<any>;
    isDevicePermissionGranted() :Promise<any>;
    requestPermission() :Promise<any>;
    addSpectrum(spectrum: Function) :void;
    isRecording() :boolean;
    isInactive() :boolean;
    isPaused() :boolean;
    removeAllListeners() :void;
    removeListeners(event: string) :boolean;
    removeListener(event: string, callback: any) :boolean;
    addListener(event: string, callback: any) :void;
    stopTracks() :void;
    getTrack() :MediaStreamTrack[];
    addEvents() :void;
    start() :void;
    getBlob(type: MimeTypes): Blob;
    pause() :void;
    resume() :void;
    stop() :void;
}

export class AudioRecorder implements AudioRecorderInterface {

    private state: string;
    private timeslice: number;
    private parseFrequenceTimeout: number;
    private isGranted: boolean;
    private receivedChunks: Array<any>;
    private events: Array<any>;
    private maximumDuration: number;
    private spectrumCallback: any;
    private mediaRecorder: MediaRecorder | null;
    private minDecibels: number;
    private fftSize: number;
    private requestAnimationFrame: any;
    private cancelAnimationFrame: any;
    private isAutoCorrelationEnabled: boolean;
    private stream: MediaStream;
    private bps: number;

    constructor(bps = 12800) {
        this.bps = bps;
        this.setCompatibility();
        
        this.state = RecordState.INACTIVE;

        this.timeslice = 1000;
        this.parseFrequenceTimeout = 1000 / 30;

        this.isGranted = false;
        this.receivedChunks = [];

        this.events = [];

        this.maximumDuration = -1;

        this.spectrumCallback = null;

        this.mediaRecorder = null;

        this.minDecibels = -120;
        this.fftSize = 9; // 2^5 ~ 2^15

        this.requestAnimationFrame = window.requestAnimationFrame;
        this.cancelAnimationFrame = window.cancelAnimationFrame;

        this.isAutoCorrelationEnabled = false;
    }

    public hasGetUserMedia() :boolean {
        return !!(navigator.mediaDevices.getUserMedia || (navigator as any).getUserMedia || (navigator as any).webkitGetUserMedia || (navigator as any).mozGetUserMedia || (navigator as any).msGetUserMedia);
    }

    public useAutocorrelation(use: boolean) {
        this.isAutoCorrelationEnabled = use;
    }

    private fundamentalFrequence(buffer: Uint8Array, sampleRate: number) {
        var sampleNumbers = 1024;
        var correctR = 0;
        var periodInFrame = -1;

        for (var products = 8; products <= 1000; products++) {
            let nomalize = 0;
            
            // Normalization
            for (var i = 0; i < sampleNumbers; i++) {
                let current = ((buffer[i] - 127.5) / 127.5);
                let next = ((buffer[i + products] - 127.5) / 127.5);
                nomalize += current * next;
            }
            
            let R = nomalize / (sampleNumbers + products);

            if (R > correctR) {
                correctR = R;
                periodInFrame = products;
            }

            if (R > 0.9) {
                break;
            }
        }
        
        if (correctR > 0.0025) { // Get average
            return sampleRate / periodInFrame;
        }
        
        return -1;
    }

    private fftHandler(stream: MediaStream) :void {
        const self = this;

        const audioContext = new AudioContext();
        const analyserNode = audioContext.createAnalyser();
        const streamNode = audioContext.createMediaStreamSource(stream);
        streamNode.connect(analyserNode);

        analyserNode.minDecibels = this.minDecibels; // min FFT(fast Fourier transform) dB
        analyserNode.fftSize = 32 << this.fftSize;
        analyserNode.smoothingTimeConstant = 0.45;

        const arrayLength = new Uint8Array(analyserNode.frequencyBinCount);

        const trigger = () => {
            if (self.isRecording()) {
                analyserNode.getByteFrequencyData(arrayLength);

                if (arrayLength) {
                    self.dispatch(RecorderEvent.ON_FREQUENCY, arrayLength);
                }

                const buffer = new Uint8Array(analyserNode.fftSize);
                analyserNode.getByteTimeDomainData(buffer);
                const fundalmentalFreq = self.fundamentalFrequence(buffer, audioContext.sampleRate);

                if (fundalmentalFreq > -1) {
                    self.dispatch(RecorderEvent.ON_DETECT, stream);
                }
            }

            if (self.isRecording()) {
                setTimeout(() => {
                    requestAnimationFrame(trigger);
                }, self.parseFrequenceTimeout);
            }
        }

        requestAnimationFrame(trigger);
    }

    public clearSpectrum() :void {
        let targetDiv: any = document.querySelectorAll('#div');
        if (targetDiv.length > 0) {
            targetDiv = targetDiv[0];
        }

        if (!targetDiv) {
            return;
        }

        try {
            targetDiv.innerHTML = '';
            const canvas = targetDiv.appendChild(document.createElement("canvas"));
            canvas.width = targetDiv.clientWidth;
            canvas.height = 24;
            canvas.style.verticalAlign = "middle";
            const canvasContext = canvas.getContext("2d");
    
            canvasContext.clearRect(0, 0, canvas.width, canvas.height);
            canvasContext.fillStyle = 'rgba(255, 0, 0, 0)';
        } catch (error) {}
    }

    public setSpectrum() :void {
        this.useAutocorrelation(true);

        let target_div: any = document.querySelectorAll('#div');
        if (target_div.length > 0) {
            target_div = target_div[0];
        }

        target_div.innerHTML = '';
        const canvas = target_div.appendChild(document.createElement("canvas"));
        canvas.width = target_div.clientWidth;
        canvas.height = 24;
        canvas.style.verticalAlign = "middle";
        const canvasContext = canvas.getContext("2d");

        let fillColor = `rgb(255, 255, 255)`;
        let lineWidth = 2;

        this.addListener(RecorderEvent.ON_FREQUENCY, (freq: any) => {
            canvasContext.clearRect(0, 0, canvas.width, canvas.height);
            canvasContext.fillStyle = 'rgba(255, 0, 0, 0)';
            canvasContext.fillRect(0, 0, canvas.width, canvas.height);
            canvasContext.lineWidth = lineWidth;

            let x = 0;

            for (let d of freq) {
                const y = (canvas.height / canvasContext.lineWidth) - (d / 128) * canvas.height / canvasContext.lineWidth;
                canvasContext.fillStyle = fillColor;
                canvasContext.fillRect(x * 4, y, canvasContext.lineWidth, ((canvas.height / 2) - y) * canvasContext.lineWidth);

                x++;
            }
            canvasContext.beginPath();
        });
    }

    private setCompatibility() :void {
        if (!window.AudioContext) {
            window.AudioContext = (window as any).webkitAudioContext || (window as any).mozAudioContext;
        }

        if (!window.requestAnimationFrame) {
            this.requestAnimationFrame = (window as any).webkitRequestAnimationFrame || (window as any).mozRequestAnimationFrame;
        }

        if (!window.cancelAnimationFrame) {
            this.cancelAnimationFrame = (window as any).webkitCancelAnimationFrame || (window as any).mozCancelAnimationFrame;
        }
        
        if (navigator.mediaDevices === undefined) {
            (navigator as any).mediaDevices = {};
        }

        if (navigator.mediaDevices.getUserMedia === undefined) {
            navigator.mediaDevices.getUserMedia = function(constraints) {
                var getUserMedia = (navigator as any).webkitGetUserMedia || (navigator as any).mozGetUserMedia;
        
                if (!getUserMedia) {
                    return Promise.reject(new Error(ErrorMessages.NOT_IMPLEMENTED_API));
                }
                
                return new Promise(function(resolve, reject) {
                    getUserMedia.call(navigator, constraints, resolve, reject);
                });
            }
        }
    }

    public clearRecordChunks() :void {
        this.receivedChunks = [];
    }

    public async getDeviceList() :Promise<any> {
        return new Promise(async (resolve, reject) => {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();

                resolve(devices);
            } catch (error) {
                reject(error);
            }
        })
    }

    public async isDevicePermissionGranted() :Promise<any> {
        let isGranted = false;

        try {
            if (typeof navigator.permissions.query !== 'undefined') {
                const permissionName = "microphone" as PermissionName;
                const permission = await navigator.permissions.query({ name: permissionName });
            
                if (permission.state) {
                    isGranted = permission.state == PermissionStates.GRANTED;
                }
            }
        } catch (error) {
            console.error(error);
        }

        return isGranted;
    }

    public async requestPermission() :Promise<any> {
        return new Promise(async (resolve, reject) => {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({audio: true, video: false});
                
                this.setRecorder();

                if (typeof this.spectrumCallback === 'function') {
                    this.spectrumCallback(this.stream);
                }

                if (this.isAutoCorrelationEnabled) {
                    this.fftHandler(this.stream);
                }

                this.biquadFilter(this.stream);

                resolve(true);
            } catch (error) {
                reject(error);
            }
        });
    }

    private biquadFilter(stream: MediaStream) {
        var audioContext = new AudioContext();
        var inputNode = audioContext.createMediaStreamSource(stream);
        var filter = audioContext.createBiquadFilter();
    }

    public addSpectrum(spectrum: Function) :void {
        if (typeof spectrum === 'undefined') {
            this.setSpectrum();
        } else {
            this.spectrumCallback = spectrum;
        }
    }

    private getState(): RecordingState | RecordState {
        if (this.mediaRecorder == null) {
            return RecordState.UNINITIALIZED;
        }

        if (typeof this.mediaRecorder.state == 'undefined') {
            return RecordState.UNINITIALIZED;
        }

        return this.mediaRecorder.state;
    }

    public isRecording() :boolean {
        return this.getState() === RecordState.RECORDING;
    }

    public isInactive() :boolean {
        return this.getState().toLowerCase() === RecordState.INACTIVE;
    }

    public isPaused() :boolean {
        return this.getState().toLowerCase() === RecordState.PAUSED;
    }

    private getOptions(): MediaRecorderOptions {
        return {
            audioBitsPerSecond: this.bps
        }
    }

    private setRecorder() :void {
        this.mediaRecorder = new MediaRecorder(this.stream, this.getOptions());
    }

    private isRecorderPresents() :boolean {
        return typeof this.mediaRecorder !== 'undefined';
    }

    public removeAllListeners() :void {
        Object.keys(this.events).forEach(key => {
            delete this.events[key];
        })
    }

    public removeListeners(event: string) :boolean {
        if (this.events[event] === undefined) {
            return false;
        }

        delete this.events[event];

        return true;
    }

    public removeListener(event: string, callback: any) :boolean {
        if (this.events[event] === undefined) {
            return false;
        }

        this.events[event].listeners = this.events[event].listeners.filter((listener: any) => {
            return listener.toString() !== callback.toString();
        });

        return false;
    }

    public addListener(event: string, callback: any) :void {
        if (this.events[event] === undefined) {
            this.events[event] = {
                listeners: []
            }
        }
        
        this.events[event].listeners.push(callback);
    }

    private dispatch(event: string, details: any) :void {
        if (typeof this.events[event] === 'undefined') {
            return;
        }

        this.events[event].listeners.forEach((listener: Function) => {
            listener(details);
        });
    }

    public stopTracks() :void {
        this.getTrack().forEach(function(track) {
            track.stop();
        });
    }

    public getTrack() :MediaStreamTrack[] {
        return this.stream.getTracks();
    }

    private isTypeSuppported(type: string) :boolean {
        if (typeof MediaRecorder.isTypeSupported == 'function') {
            return MediaRecorder.isTypeSupported(type);
        }

        return false;
    }

    public addEvents() :void {
        const self = this;

        // An event handler called to handle the resume event, which occurs when media recording resumes after being paused.
        if (this.mediaRecorder != null) {
            this.mediaRecorder.onresume = function(event) {
                self.dispatch(RecorderEvent.ON_RESUME, event);
            };
        }

        // An event handler called to handle the pause event, which occurs when media recording is paused.
        if (this.mediaRecorder != null) {
            this.mediaRecorder.onpause = function(event) {
                self.dispatch(RecorderEvent.ON_PAUSE, event);
            };
        }

        // An event handler called to handle the start event, which occurs when media recording starts.
        if (this.mediaRecorder != null) {
            this.mediaRecorder.onstart = function(event) {
                self.dispatch(RecorderEvent.ON_START, event);
            };
        }

        // Called to handle the dataavailable event, which is periodically triggered each time timeslice milliseconds of media have been recorded (or when the entire media has been recorded, if timeslice wasn't specified). The event, of type BlobEvent, contains the recorded media in its data property. You can then collect and act upon that recorded media data using this event handler.
        if (this.mediaRecorder != null) {
            this.mediaRecorder.ondataavailable = (event) => {
                self.dispatch(RecorderEvent.ON_DATA_AVAILABLE, event);
            };
        }

        // An event handler called to handle the error event, including reporting errors that arise with media recording. These are fatal errors that stop recording. The received event is based on the MediaRecorderErrorEvent interface, whose error property contains a DOMException that describes the actual error that occurred.
        if (this.mediaRecorder != null) {
            this.mediaRecorder.onerror = (event) => {
                self.dispatch(RecorderEvent.ON_ERROR, event);
            };
        }

        // An event handler called to handle the stop event, which occurs when media recording ends, either when the MediaStream ends — or after the MediaRecorder.stop() method is called.
        if (this.mediaRecorder != null) {
            this.mediaRecorder.onstop = (event) => {
                self.dispatch(RecorderEvent.ON_STOP, event);
            };
        }

        const onLoadedMetadataEvent = (event) => {
            self.dispatch(RecorderEvent.LOADED_METADATA, event);
        };

        if (this.mediaRecorder != null) {
            this.mediaRecorder.removeEventListener('loadedmetadata', onLoadedMetadataEvent);
            this.mediaRecorder.addEventListener(RecorderEvent.LOADED_METADATA, onLoadedMetadataEvent);
        }

        this.removeListeners(RecorderEvent.ON_STOP);
        this.addListener(RecorderEvent.ON_STOP, (event: any) => {
            self.clearSpectrum();
        });

        this.removeListeners(RecorderEvent.ON_DATA_AVAILABLE);
        this.addListener(RecorderEvent.ON_DATA_AVAILABLE, (event: any) => {
            if (typeof event.data === 'undefined') {
                return;
            }

            if (event.data && event.data.size > 0) {
                self.receivedChunks.push(event.data);
            }
        });
    }

    public setMaximumDuration(duration = -1) :void {
        this.maximumDuration = duration;
    }

    private throwStateError(method: string) {
        return new Error(`Failed to execute '${method}' on 'MediaRecorder': The MediaRecorder's state is '${this.state}'.`);
    }

    public start() :void {
        if (this.mediaRecorder == null) {
            throw new Error(ErrorMessages.NOT_INITIALIED);
        }

        if (!this.isRecorderPresents()) {
            return;
        }

        if (this.isRecording()) {
            throw new Error(ErrorMessages.NOT_STARTED);
        }

        if (!this.isInactive()) {
            throw this.throwStateError(RecordAction.RECORD);
        }
        
        this.state = RecordState.RECORDING;
        this.mediaRecorder.start(this.timeslice);
    }

    public getBlob(type = MimeTypes.MP3): Blob {
        return new Blob(this.receivedChunks, {"type": type});
    }

    public pause() :void {
        if (this.mediaRecorder == null) {
            throw new Error(ErrorMessages.NOT_INITIALIED);
        }

        if (!this.isRecorderPresents()) {
            return;
        }

        if (!this.isRecording()) {
            throw this.throwStateError(RecordAction.PAUSE);
        }
        
        this.state = RecordState.PAUSED;
        this.mediaRecorder.pause();
    }

    public resume() :void {
        if (this.mediaRecorder == null) {
            throw new Error(ErrorMessages.NOT_INITIALIED);
        }

        if (!this.isRecorderPresents()) {
            return;
        }

        if (!this.isPaused()) {
            throw this.throwStateError(RecordAction.RESUME);
        }
        
        this.state = RecordState.RECORDING;
        this.mediaRecorder.resume();
    }

    public stop() :void {
        if (this.mediaRecorder == null) {
            throw new Error(ErrorMessages.NOT_INITIALIED);
        }

        if (!this.isRecorderPresents()) {
            return;
        }

        if (this.isInactive()) {
            throw this.throwStateError(RecordAction.STOP);
        }
        
        this.clearSpectrum();

        this.state = RecordState.INACTIVE;
        this.mediaRecorder.stop();

        this.stopTracks();
    }

}