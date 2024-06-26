interface MediaPlayerInterface {
    setSpectrum(selector: string): void;
    isPlaying(): boolean;
    isPaused(): boolean;
    isEnded(): boolean;
    isFullScreen(): boolean;
    seekBackward(seconds: number): void;
    seekForward(seconds: number): void;
    setCurrentTime(time: number): void;
    setBiquadFilterGainValue(value: number): void;
    setBiquadFilterDetuneValue(value: number): void;
    setBiquadFilterFrequencyValue(value: number): void;
    setEqualPowerPannerModel(): void;
    setHRTFPannerModel(): void;
    setPanningModel(modelName: PanningModelType): void;
    setGainValue(value: number): void;
    setParseFrequencyTimeout(timeout: number): void;
    setSource(source: string): void;
    setDelayValue(value: number): void;
    getOfflineAudioContext(options: OfflineAudioContextOptions): OfflineAudioContext;
    getAudioContext(): AudioContext;
    getCurrentTime(): number;
    getSampleRate(): number;
    getSource(): string;
    createAudioContext(): void;
    connectPanEffector(): void;
    connectAnalyser(): void;
    connectToAudioContext(): void;
    connectScriptProcessorFilter(bufferSize?: number, numberOfInputChannels?: number, numberOfOutputChannels?: number): void;
    connectIIRFilter(feedforward: number[], feedback: number[]): void;
    connectDynamicsCompressorFilterFilter(): void;
    connectBiquadFilter(): void;
    connectChannelMergerEffector(): void;
    connectDelayEffector(): void;
    connectGainEffector(): void;
    cancelFullScreen(): boolean;
    requestFullScreen(): boolean;
    play(): void;
    pause(): void;
    hasGetUserMedia(): boolean;
    requestAnimationFrame(callback: FrameRequestCallback): number;
    getUserMedia(): any;
    setUserMediaConstraints(constraints?: MediaStreamConstraints): Promise<MediaStream> | Promise<unknown>;
    setContext(mediaContext: any): void;
}

interface MediaSessionInterface {
    isMediaSessionSupported(): boolean;
    setPlayHandler(handler: MediaSessionActionHandler): void;
    setPauseHandler(handler: MediaSessionActionHandler): void;
    setSeekbackwardHandler(handler: MediaSessionActionHandler): void;
    setSeekforwardHandler(handler: MediaSessionActionHandler): void;
    setPrevioustrackHandler(handler: MediaSessionActionHandler): void;
    setNextrackHandler(handler: MediaSessionActionHandler): void;
    setPositionState(state?: MediaPositionState): void;
    setMetadata(init?: MediaMetadataInit): void;
    setPlaybackState(state: MediaSessionPlaybackState): void;
    setStateToPlaying(): void;
    setStateToPaused(): void;
    setActionHandler(action: MediaSessionAction, handler: MediaSessionActionHandler): void;
}

interface PictureInPictureInterface {
    hasPip(): boolean;
    setPipMode(): Promise<PictureInPictureWindow> | boolean;
    exitPip(): Promise<void> | boolean;
}

interface EventListenerInterface {
    removeListeners(event: string): boolean;
    removeListener(event: string, callback: Function): boolean;
    addListener(event: string, callback: Function): void;
}

enum ErrorMessages {
    NOT_IMPLEMENTED_API = 'getUserMedia is not implemented in this browser',
    NOT_INITIALIED = 'Media recorder is not initialized',
    NOT_STARTED = 'Cannot start MediaRecorder when already recording'
}

enum AudioEvent {
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

enum VisualizerStyle {
    CLASSIC = 'classic',
    WAVEFORM = 'waveform',
    CIRCULAR = 'circular',
    CIRCLE = 'circle',
    DONUT = 'donut',
    SLIDE_WAVEFORM = 'slide_waveform',
    ROTATE_CIRCLE = 'rorate_circle',
    RAINBOW = 'rainbow',
}

enum PlayState {
    ERROR = 'error',
    ON_STATUS_CHANGED = 'onstatuschanged',
    ENDED = 'ended',
    PAUSE = 'pause',
    PLAY = 'play',
    PLAYING = 'playing',
    LOADED_METADATA = 'loadedmetadata',
    LOADED_DATA = 'loadeddata',
    CAN_PLAY = 'canplay',
    CAN_PLAY_THROUGH = 'canplaythrough',
    VOLUME_CHANGE = 'volumechange',
    RATE_CHANGE = 'ratechange',
    DURATION_CHANGE = 'durationchange',
    STALLED = 'stalled',
    EMPTIED = 'emptied',
    SUSPEND = 'suspend',
    SEEKING = 'seeking',
    WAITING = 'waiting'
}

enum MimeTypes {
    MP3 = 'audio/mpeg',
    WAV = 'audio/wav',
    OGG = 'audio/ogg',
    WEBM = 'audio/webm',
    MP4 = 'video/mp4',
    MPEG = 'video/mpeg'
}

class Visualizer {

    private selector;
    private context: CanvasRenderingContext2D;
    private canvas: HTMLCanvasElement;
    private backgroundFillColor = '';
    private spectrumFillColor = '';
    private space = 2;

    constructor(selector) {
        this.selector = selector;
    }

    public setSpectrumFillColor(color): void {
        this.spectrumFillColor = color;
    }

    public setBackgroundFillColor(color) {
        this.backgroundFillColor = color;
    }

    public circles(cx, cy, rad, dashLength) {
        var n = rad / dashLength;
        var alpha = Math.PI * 2 / n;
        var points = [];
        var i = -1;

        while (i < n) {
            var theta = alpha * i;
            var theta2 = alpha * (i + 1);

            points.push({
                x: (Math.cos(theta) * rad) + cx,
                y: (Math.sin(theta) * rad) + cy,
                ex: (Math.cos(theta2) * rad) + cx,
                ey: (Math.sin(theta2) * rad) + cy
            });

            i++;
        }

        return points;
    }

    private rotation  = 0;
    private dataIndex = 0;
    private previousDataArray = null;
    private currentRadius = 0;
    private targetRadius = 0;

    public draw(frequencies, binaryCount, lineWidth: number = 1, type: VisualizerStyle) {
        const canvasContext = this.context;
        const canvas = this.canvas;
        const samples = (canvas.height);

        if (this.previousDataArray == null) {
            this.previousDataArray = new Uint8Array(binaryCount);
        }

        let radius = 0;
        let current = 0;
        let x = 0;
        let y = 0;
        let width = 0;
        let height = 0;

        if (VisualizerStyle.SLIDE_WAVEFORM != type && type != VisualizerStyle.ROTATE_CIRCLE) {
            canvasContext.save();
            canvasContext.clearRect(0, 0, canvas.width, canvas.height);
            canvasContext.fillStyle = this.backgroundFillColor;
            canvasContext.fillRect(0, 0, canvas.width, canvas.height);
            canvasContext.lineWidth = lineWidth;
        }

        const ctx = canvasContext;
        const bufferLength = binaryCount;
        const dataArray = frequencies;


        switch (type) {
            case VisualizerStyle.RAINBOW:
                const sliceWidth = canvas.width / bufferLength;
                ctx.fillStyle = this.backgroundFillColor;
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                let x = 0;
                for (let i = 0; i < bufferLength; i++) {
                    const barHeight = (dataArray[i] / 255) * canvas.height;
                    const hue = i / bufferLength * 360;
                    ctx.fillStyle = 'hsl(' + hue + ', 100%, 60%)';
                    ctx.fillRect(x, canvas.height - barHeight, sliceWidth, barHeight);
                    x += sliceWidth;
                }
                canvasContext.stroke();

                break;
            case VisualizerStyle.ROTATE_CIRCLE:
                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;
                const maxRadius = Math.min(centerX, centerY) - 20;
          
                const lowFrequencyRange = 50;
                let lowFrequencySum = 0;
                let lowFrequencyAverage = 0;
                for (let i = 0; i < lowFrequencyRange; i++) {
                  lowFrequencySum += dataArray[i];
                }
                lowFrequencyAverage = lowFrequencySum / lowFrequencyRange;
          
                this.targetRadius = maxRadius;

                for (let prev in this.previousDataArray){
                    if (lowFrequencyAverage > 100 && lowFrequencyAverage > (prev as any) * 1.2) {
                        this.targetRadius = this.targetRadius * 1.1; 
                    } else if (this.targetRadius > maxRadius){
                        this.targetRadius = this.targetRadius * 0.9; 
                    }
                }
          
                const self = this;

                function animateRadius() {
                    self.currentRadius += (self.targetRadius - self.currentRadius) * 0.5;
          
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
          
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, self.currentRadius, 0, 2 * Math.PI);
                    ctx.fillStyle = self.spectrumFillColor;
                    ctx.fill();
          
                    const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, self.currentRadius);
                    gradient.addColorStop(0, 'rgba(255, 255, 255, 1)');
                    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
          
                    ctx.fillStyle = gradient;
                    for (let i = 0; i < bufferLength; i++) {
                      const noise = dataArray[i] / 255;
                      const r = ((self.currentRadius * 0.1) * (noise * 10)) * noise;
                      ctx.beginPath();
                      ctx.arc(centerX, centerY, r, 0, 2 * Math.PI);
                      ctx.fill();
                      continue;
                    }
                }

                animateRadius();
          
                this.previousDataArray.set(dataArray);

                break;
            case VisualizerStyle.SLIDE_WAVEFORM:
                const imageData = canvasContext.getImageData(1, 0, canvas.width - 1, canvas.height);
                canvasContext.fillStyle = this.backgroundFillColor;
                canvasContext.fillRect(0, 0, canvas.width, canvas.height);
                canvasContext.putImageData(imageData, 0, 0);

                canvasContext.fillStyle = this.spectrumFillColor;
                const barHeight = (frequencies[this.dataIndex] / 255) * canvas.height;
                canvasContext.fillRect((canvas.width - 1), canvas.height - barHeight, 1, barHeight);

                this.dataIndex = (this.dataIndex + 1) % binaryCount;
                canvasContext.stroke();

                current++;
                break;
            case VisualizerStyle.WAVEFORM:
                for (let frequency of frequencies) {
                    x = (current) * lineWidth;
                    y = samples - (frequency / 128) * (samples / 2);
                    width = canvasContext.lineWidth;
                    height = ((canvas.height / 2) - y) * 2;

                    canvasContext.fillStyle = this.spectrumFillColor;
                    canvasContext.fillRect(x * this.space, y, width, height);

                    current++;
                }
                break;
            case VisualizerStyle.CLASSIC:
                for (let frequency of frequencies) {
                    x = (current) * this.space;
                    y = samples - (frequency / 128) * (samples / 2);

                    if (current == 0) {
                        canvasContext.strokeStyle = this.spectrumFillColor;
                        canvasContext.beginPath();
                        canvasContext.moveTo(x, y);
                    } else {
                        canvasContext.lineTo(x, y);
                    }

                    current++;
                }

                break;
            case VisualizerStyle.CIRCLE:
                const circles = this.circles(50, 50, 50, 1);

                for (let circle of circles) {
                    const centerX = canvas.width / 3;
                    const centerY = canvas.height / 3;

                    canvasContext.beginPath();
                    let frequency = frequencies[current];
                    frequency = 50 * (frequency / 256);

                    canvasContext.strokeStyle = this.spectrumFillColor;
                    canvasContext.lineWidth = frequency;
                    canvasContext.lineCap = "butt";
                    canvasContext.moveTo(centerX + circle.x, centerY + circle.y);
                    canvasContext.lineTo(centerX + circle.ex, centerY + circle.ey);
                    canvasContext.stroke();
                    canvasContext.closePath();
                    
                    current++;
                }

                break;
            case VisualizerStyle.CIRCULAR:
                const bars = 100;

                for (let frequency of frequencies) {
                    canvasContext.beginPath();

                    radius = canvas.width / 5;
                    const centerX = canvas.width / 2;
                    const centerY = canvas.height / 2;
                    const rads = (Math.PI * 2) / bars;
                
                    const x = centerX + Math.cos(rads * current) * (radius + lineWidth);
                    const y = centerY + Math.sin(rads * current) * (radius + lineWidth);
                    const endX = centerX + Math.cos(rads * current) * (radius + (frequency * 0.2));
                    const endY = centerY + Math.sin(rads * current) * (radius + (frequency * 0.2));

                    const gradient = canvasContext.createLinearGradient(0, 0, 170, 0);
                    gradient.addColorStop(0, "cyan");
                    gradient.addColorStop(1, "green");
                    canvasContext.fillStyle = gradient;

                    canvasContext.strokeStyle = this.spectrumFillColor;
                    canvasContext.lineWidth = lineWidth;
                    canvasContext.lineCap = "round";
                    canvasContext.shadowBlur = 1;
                    canvasContext.moveTo(x, y);
                    canvasContext.lineTo(endX, endY);
                    canvasContext.stroke();
            
                    current++;
                }
                break;
            case VisualizerStyle.DONUT:
                const halfWidth = canvas.width / 2;
                const halfHeight = canvas.height / 2;
                radius = canvas.width / 5;
                const maximumBarSize = Math.floor(360 * Math.PI) / 7;
                const framePerFrequency = Math.floor(frequencies.length / maximumBarSize);
                const minumiumHeight = 10;

                for (let i = 0; i < maximumBarSize; i++) {
                    const frequency = frequencies[i * framePerFrequency];
                    const alfa = (current * 2 * Math.PI) / maximumBarSize;
                    const degree = (180) * Math.PI / 180;

                    x = 0;
                    y = radius - (frequency / 12);
                    width = 2;
                    height = frequency / 6 + minumiumHeight;

                    canvasContext.save();
                    canvasContext.translate(halfWidth + 7, halfHeight + 7);
                    canvasContext.rotate(alfa - degree);
                    const gradient = canvasContext.createLinearGradient(0, 0, 170, 0);
                    gradient.addColorStop(0, "cyan");
                    gradient.addColorStop(1, "green");
                    canvasContext.fillStyle = gradient;
                    canvasContext.fillRect(x, y, width, height);
                    canvasContext.restore();

                    current++;
                }
                break;
        }

        switch (type) {
            case VisualizerStyle.WAVEFORM:
                canvasContext.beginPath();
            case VisualizerStyle.CLASSIC:
            case VisualizerStyle.CIRCLE:
                canvasContext.stroke();
        }
    }

    public setCanvas(width = -1, height = -1) {
        let canvasBackground: Element = null;
        const targetElement: NodeListOf<Element> = document.querySelectorAll(this.selector);

        if (targetElement.length > 0) {
            canvasBackground = targetElement[0];
        }

        canvasBackground.innerHTML = '';
        const newCanvas = document.createElement("canvas");

        this.canvas = canvasBackground.appendChild(newCanvas);
        this.canvas.width = width == -1 ? canvasBackground.clientWidth : width;
        this.canvas.height = height == -1 ? canvasBackground.clientHeight : height;
        this.canvas.style.verticalAlign = "middle";

        this.context = this.canvas.getContext("2d");
    }

}

class EventListener implements EventListenerInterface {

    public events: Array<any>;

    constructor() {
        this.events = [];
    }

    public removeListeners(event: string): boolean {
        if (this.events[event] === undefined) {
            return false;
        }

        delete this.events[event];

        return true;
    }

    public removeListener(event: string, callback: Function): boolean {
        if (this.events[event] === undefined) {
            return false;
        }

        this.events[event].listeners = this.events[event].listeners.filter(listener => {
            return listener.toString() !== callback.toString();
        })

        return true;
    }

    public addListener(event: string, callback: Function): void {
        if (this.events[event] === undefined) {
            this.events[event] = {
                listeners: []
            }
        }

        this.events[event].listeners.push(callback);
    }

    public dispatch(event: string, details: any) {
        if (this.events[event] === undefined) {
            return;
        }

        this.events[event].listeners.forEach((listener) => {
            listener(details);
        })
    }

}

class PictureInPicture implements PictureInPictureInterface {

    private mediaContext: HTMLVideoElement;

    constructor(context: HTMLVideoElement) {
        this.mediaContext = context;
    }

    public exitPip(): Promise<void> | boolean {
        if (!this.hasPip()) {
            return false;
        }

        return document.exitPictureInPicture();
    }

    public setPipMode(): Promise<PictureInPictureWindow> | boolean {
        if (!this.hasPip()) {
            return false;
        }

        if (!(this.mediaContext instanceof HTMLVideoElement)) {
            return false;
        }

        return this.mediaContext.requestPictureInPicture();
    }

    public hasPip(): boolean {
        return document.pictureInPictureElement !== undefined;
    }

}

class MediaSession implements MediaSessionInterface {
    public setPlayHandler(handler: MediaSessionActionHandler): void {
        this.setActionHandler('play', handler);
    }

    public setPauseHandler(handler: MediaSessionActionHandler): void {
        this.setActionHandler('pause', handler);
    }

    public setSeekbackwardHandler(handler: MediaSessionActionHandler): void {
        this.setActionHandler('seekbackward', handler);
    }

    public setSeekforwardHandler(handler: MediaSessionActionHandler): void {
        this.setActionHandler('seekforward', handler);
    }

    public setPrevioustrackHandler(handler: MediaSessionActionHandler): void {
        this.setActionHandler('previoustrack', handler);
    }

    public setNextrackHandler(handler: MediaSessionActionHandler): void {
        this.setActionHandler('nexttrack', handler);
    }

    public setPositionState(state?: MediaPositionState): void {
        navigator.mediaSession.setPositionState(state);
    }

    public setMetadata(init?: MediaMetadataInit): void {
        navigator.mediaSession.metadata = new MediaMetadata(init);
    }

    public setStateToPlaying(): void {
        this.setPlaybackState("playing");
    }

    public setStateToPaused(): void {
        this.setPlaybackState("paused");
    }

    public setPlaybackState(state: MediaSessionPlaybackState): void {
        navigator.mediaSession.playbackState = state;
    }

    public setActionHandler(action: MediaSessionAction, handler: MediaSessionActionHandler): void {
        if (!this.isMediaSessionSupported()) {
            return;
        }

        navigator.mediaSession.setActionHandler(action, handler);
    }

    public isMediaSessionSupported(): boolean {
        if ('MediaSession' in navigator) {
            return true;
        }

        return false;
    }
}

export class MediaPlayer implements MediaPlayerInterface {
    private mediaContext: HTMLMediaElement | HTMLVideoElement | HTMLAudioElement;
    private audioContext: AudioContext;
    private parseFrequencyTimeout: number = 1000 / 35;
    private mediaType: string;
    private isAudioContextConnected: boolean = false;
    private mediaElementSource: MediaElementAudioSourceNode;
    private isSpectrumEnabled = false;
    private analyser: AnalyserNode;
    private pannerEffector: PannerNode;
    private gainEffector: GainNode;
    private delayEffector: DelayNode;
    private biquadFilter: BiquadFilterNode;
    private dynamicsCompressorFilter: DynamicsCompressorNode;
    private iirFilter: IIRFilterNode;
    private scriptProcessorFilter: ScriptProcessorNode;
    private channelMergerFilter: ChannelMergerNode;
    private spectrumAnalyser: AnalyserNode;
    private spectrumBackgroundFillColor = 'rgba(255, 255, 255, 255)';
    private spectrumFillColor = `rgb(0, 130, 61)`;
    private spectrumCanvasWidth = -1;
    private spectrumCanvasHeight = -1;
    private spectrumVisualizerStyle: VisualizerStyle = VisualizerStyle.CLASSIC;
    private mediaSessionHandler: MediaSession;
    private eventListener: EventListener;
    private visualizerLineWidth: number = 1;

    constructor() {
        this.mediaSessionHandler = new MediaSession();
        this.eventListener = new EventListener();
    }

    public setContext(mediaContext: any): void {
        this.mediaContext = typeof mediaContext === 'string' ? document.querySelector(mediaContext) : mediaContext;
        this.mediaType = this.mediaContext.getAttribute('mime-type') ?? MimeTypes.MP4;
    }

    public setUserMediaConstraints(constraints?: MediaStreamConstraints): Promise<MediaStream> | Promise<unknown> {
        if (typeof navigator.mediaDevices.getUserMedia === 'function') {
            return navigator.mediaDevices.getUserMedia(constraints);
        }

        const getUserMedia = function (constraints?: MediaStreamConstraints) {
            const getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

            if (!getUserMedia) {
                return Promise.reject(new Error(ErrorMessages.NOT_IMPLEMENTED_API));
            }

            return new Promise(function (resolve, reject) {
                getUserMedia.call(navigator, constraints, resolve, reject);
            });
        };

        return getUserMedia(constraints);
    }

    public getUserMedia(): any {
        return window.getUserMedia ||
            window.webkitGetUserMedia ||
            window.mozGetUserMedia ||
            window.msGetUserMedia ||
            navigator.mediaDevices.getUserMedia;
    }

    public hasGetUserMedia(): boolean {
        return !!(navigator.mediaDevices.getUserMedia || (navigator as any).getUserMedia || (navigator as any).webkitGetUserMedia || (navigator as any).mozGetUserMedia || (navigator as any).msGetUserMedia);
    }

    public getOfflineAudioContext(options: OfflineAudioContextOptions): OfflineAudioContext {
        let context = window.OfflineAudioContext;

        return new (context)(options);
    }

    public getAudioContext(): AudioContext {
        let context = window.AudioContext;

        if (window.hasOwnProperty('webkitAudioContext') && !window.hasOwnProperty('AudioContext')) {
            context = window.webkitAudioContext || window.mozAudioContext || window.msAudioContext;
        }

        return new (context)();
    }

    public createAudioContext(): void {
        this.audioContext = this.getAudioContext();
    }

    public connectToAudioContext(): void {
        if (this.audioContext == null) {
            this.createAudioContext();
        }

        this.mediaElementSource = this.audioContext.createMediaElementSource(this.mediaContext);
        this.mediaElementSource.connect(this.audioContext.destination);

        this.isAudioContextConnected = true;
    }

    public setEqualPowerPannerModel(): void {
        this.setPanningModel("equalpower");
    }

    public setHRTFPannerModel(): void {
        this.setPanningModel("HRTF");
    }

    public setPanningModel(modelName: PanningModelType = "equalpower"): void {
        if (this.pannerEffector == null) {
            throw new Error('Effecor is not initialized');
        }

        this.pannerEffector.panningModel = modelName;
    }

    public setGainValue(value: number = 1): void {
        if (this.gainEffector == null) {
            throw new Error('Effecor is not initialized');
        }

        this.gainEffector.gain.value = value;
    }

    public getSampleRate(): number {
        return this.audioContext.sampleRate;
    }

    public setDelayValue(value: number): void {
        if (this.gainEffector == null) {
            throw new Error('Effecor is not initialized');
        }

        this.delayEffector.delayTime.value = value;
    }

    public setBiquadFilterGainValue(value: number = 1): void {
        this.biquadFilter.gain.value = value;
    }

    public setBiquadFilterDetuneValue(value: number = 1): void {
        this.biquadFilter.detune.value = value;
    }

    public setBiquadFilterFrequencyValue(value: number = 1): void {
        this.biquadFilter.frequency.value = value;
    }

    public connectPanEffector(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.pannerEffector = this.audioContext.createPanner();
        this.mediaElementSource.connect(this.pannerEffector);
        this.pannerEffector.connect(this.audioContext.destination);
    }

    public connectAnalyser(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.analyser = this.audioContext.createAnalyser();
        this.mediaElementSource.connect(this.analyser);
        this.analyser.connect(this.audioContext.destination);
    }

    public connectScriptProcessorFilter(bufferSize?: number, numberOfInputChannels?: number, numberOfOutputChannels?: number): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.scriptProcessorFilter = this.audioContext.createScriptProcessor(bufferSize, numberOfInputChannels, numberOfOutputChannels);
        this.mediaElementSource.connect(this.biquadFilter);
        this.scriptProcessorFilter.connect(this.audioContext.destination);
    }

    public connectIIRFilter(feedforward: number[], feedback: number[]): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.iirFilter = this.audioContext.createIIRFilter(feedforward, feedback);
        this.mediaElementSource.connect(this.biquadFilter);
        this.iirFilter.connect(this.audioContext.destination);
    }

    public connectDynamicsCompressorFilterFilter(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.dynamicsCompressorFilter = this.audioContext.createDynamicsCompressor();
        this.mediaElementSource.connect(this.biquadFilter);
        this.dynamicsCompressorFilter.connect(this.audioContext.destination);
    }

    public connectBiquadFilter(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.biquadFilter = this.audioContext.createBiquadFilter();
        this.mediaElementSource.connect(this.biquadFilter);
        this.biquadFilter.connect(this.audioContext.destination);
    }

    public connectChannelMergerEffector(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.channelMergerFilter = this.audioContext.createChannelMerger();
        this.mediaElementSource.connect(this.channelMergerFilter);
        this.channelMergerFilter.connect(this.audioContext.destination);
    }

    public connectDelayEffector(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.delayEffector = this.audioContext.createDelay();
        this.mediaElementSource.connect(this.delayEffector);
        this.delayEffector.connect(this.audioContext.destination);
    }

    public connectGainEffector(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.gainEffector = this.audioContext.createGain();
        this.mediaElementSource.connect(this.gainEffector);
        this.gainEffector.connect(this.audioContext.destination);
    }

    public cancelFullScreen(): boolean {
        let requestMethod = null;

        const hasWebkitRequestFullScreen = (this.mediaContext.webkitRequestFullscreen !== undefined);
        const hasMozRequestFullScreen = (this.mediaContext.mozRequestFullscreen !== undefined);
        const hasMsRequstFullScreen = (this.mediaContext.msRequestFullscreen !== undefined);

        if (document.exitFullscreen) {
            requestMethod = document.exitFullscreen();
        } else if (this.mediaContext.cancelFullScreen) {
            requestMethod = this.mediaContext.cancelFullScreen();
        } else if (hasWebkitRequestFullScreen) {
            requestMethod = document.webkitCancelFullScreen();
        } else if (hasMozRequestFullScreen) {
            requestMethod = document.mozCancelFullScreen();
        } else if (hasMsRequstFullScreen) {
            requestMethod = document.msExitFullscreen();
        }

        if (requestMethod === null || typeof requestMethod !== 'function') {
            return false;
        }

        requestMethod.call(this.mediaContext);

        return true;
    }

    public requestFullScreen(): boolean {
        if (this.isFullScreen()) {
            return false;
        }

        const hasWebkitRequestFullScreen = (this.mediaContext.webkitRequestFullscreen !== undefined);
        const hasMozRequestFullScreen = (this.mediaContext.mozRequestFullscreen !== undefined);
        const hasMsRequstFullScreen = (this.mediaContext.msRequestFullscreen !== undefined);

        let requestMethod = null;

        if (hasMozRequestFullScreen) {
            requestMethod = this.mediaContext.mozRequestFullscreen();
        } else if (hasWebkitRequestFullScreen) {
            requestMethod = this.mediaContext.webkitRequestFullscreen();
        } else if (hasMsRequstFullScreen) {
            requestMethod = this.mediaContext.msRequestFullscreen();
        } else if (this.mediaContext.requestFullscreen) {
            requestMethod = this.mediaContext.requestFullscreen();
        } else {
            requestMethod = this.mediaContext.requestFullScreen || this.mediaContext.webkitRequestFullscreen() || this.mediaContext.mozRequestFullscreen || this.mediaContext.msRequestFullscreen;
        }

        requestMethod.call(this.mediaContext);

        return true;
    }

    public isFullScreen(): boolean {
        const hasWebkitRequestFullScreen = (this.mediaContext.webkitRequestFullscreen !== undefined);
        const hasMozRequestFullScreen = (this.mediaContext.mozRequestFullscreen !== undefined);
        const hasMsRequstFullScreen = (this.mediaContext.msRequestFullscreen !== undefined);

        if (hasMozRequestFullScreen) {
            return document.mozFullScreen;
        }

        if (hasWebkitRequestFullScreen) {
            return document.webkitIsFullScreen;
        }

        if (hasMsRequstFullScreen) {
            return document.msFullscreenElement !== null;
        }

        return document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
    }

    public isPlayed(): boolean {
        return this.mediaContext
            && !this.isPaused()
            && !this.isEnded()
            && this.getReadyState() > 2;
    }

    public isPlaying(): boolean {
        return this.mediaContext
            && this.getCurrentTime() > 0
            && !this.isPaused()
            && !this.isEnded()
            && this.getReadyState() > 2;
    }

    public getReadyState(): number {
        return this.mediaContext.readyState;
    }

    public isPaused(): boolean {
        return this.mediaContext.paused;
    }

    public isEnded(): boolean {
        return this.mediaContext.ended;
    }

    public seekBackward(seconds: number): void {
        if (this.getCurrentTime() - seconds > 0) {
            this.setCurrentTime(this.getCurrentTime() - seconds);
        } else {
            this.setCurrentTime(0);
        }
    }

    public seekForward(seconds: number): void {
        if (this.getCurrentTime() + seconds <= this.getCurrentDuration()) {
            this.setCurrentTime(this.getCurrentTime() + seconds);
        } else {
            this.setCurrentTime(this.getCurrentDuration());
        }
    }

    public setCurrentTime(time: number): void {
        this.mediaContext.currentTime = time;
    }

    public getCurrentTime(): number {
        return this.mediaContext.currentTime;
    }

    public setVolume(value: number): void {
        this.mediaContext.volume = value;
    }

    public getVolume(): number {
        return this.mediaContext.volume;
    }

    public getBuffered(): TimeRanges {
        return this.mediaContext.buffered;
    }

    public getCurrentDuration(): number {
        return this.mediaContext.duration;
    }

    public getDuration(): any {
        return new Promise((resolve) => {
            const self = this;

            if (this.mediaContext.duration !== Infinity) {
                resolve(self.mediaContext.duration);
                return;
            }

            this.mediaContext.currentTime = 1e101;
            this.mediaContext.ontimeupdate = function () {
                self.mediaContext.ontimeupdate = () => {
                    return;
                }

                self.mediaContext.currentTime = 0;
                resolve(self.mediaContext.duration);
            }
        });
    }

    public setEvents(): void {
        const self = this;

        const onStalledEvent = (event: Event) => {
            self.eventListener.dispatch(PlayState.STALLED, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.STALLED);
        };

        this.mediaContext.removeEventListener(PlayState.STALLED, onStalledEvent);
        this.mediaContext.addEventListener(PlayState.STALLED, onStalledEvent);

        const onEmptiesEvent = (event: Event) => {
            self.eventListener.dispatch(PlayState.EMPTIED, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.EMPTIED);
        };

        this.mediaContext.removeEventListener(PlayState.EMPTIED, onEmptiesEvent);
        this.mediaContext.addEventListener(PlayState.EMPTIED, onEmptiesEvent);

        const onSuspendEvent = (event: Event) => {
            self.eventListener.dispatch(PlayState.SUSPEND, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.SUSPEND);
        };

        // The suspend event is fired when media data loading has been suspended.
        this.mediaContext.removeEventListener(PlayState.SUSPEND, onSuspendEvent);
        this.mediaContext.addEventListener(PlayState.SUSPEND, onSuspendEvent);

        const onDurationChangeEvent = (event: Event) => {
            self.eventListener.dispatch(PlayState.DURATION_CHANGE, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.DURATION_CHANGE);
        };

        this.mediaContext.removeEventListener(PlayState.DURATION_CHANGE, onDurationChangeEvent);
        this.mediaContext.addEventListener(PlayState.DURATION_CHANGE, onDurationChangeEvent);

        const onRateChangeEvent = (event: Event) => {
            self.eventListener.dispatch(PlayState.RATE_CHANGE, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.RATE_CHANGE);
        };

        this.mediaContext.removeEventListener(PlayState.RATE_CHANGE, onRateChangeEvent);
        this.mediaContext.addEventListener(PlayState.RATE_CHANGE, onRateChangeEvent);

        const onVolumeChangeEvent = (event: Event) => {
            self.eventListener.dispatch(PlayState.VOLUME_CHANGE, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.VOLUME_CHANGE);
        };

        // The volumechange event is fired when the volume has changed.
        this.mediaContext.removeEventListener(PlayState.VOLUME_CHANGE, onVolumeChangeEvent);
        this.mediaContext.addEventListener(PlayState.VOLUME_CHANGE, onVolumeChangeEvent);

        const onCanPlayThroughEvent = (event: Event) => {
            self.eventListener.dispatch(PlayState.CAN_PLAY_THROUGH, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.CAN_PLAY_THROUGH);
        };

        this.mediaContext.removeEventListener(PlayState.CAN_PLAY_THROUGH, onCanPlayThroughEvent);
        this.mediaContext.addEventListener(PlayState.CAN_PLAY_THROUGH, onCanPlayThroughEvent);

        const onSeekingEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.SEEKING, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.SEEKING);
        };

        this.mediaContext.removeEventListener(PlayState.SEEKING, onSeekingEvent);
        this.mediaContext.addEventListener(PlayState.SEEKING, onSeekingEvent);

        const onWaitingEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.WAITING, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.WAITING);
        };

        this.mediaContext.removeEventListener(PlayState.WAITING, onWaitingEvent);
        this.mediaContext.addEventListener(PlayState.WAITING, onWaitingEvent);

        const onCanPlayEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.CAN_PLAY, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.CAN_PLAY);
        };

        this.mediaContext.removeEventListener(PlayState.CAN_PLAY, onCanPlayEvent);
        this.mediaContext.addEventListener(PlayState.CAN_PLAY, onCanPlayEvent);

        const onLoadedDataEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.LOADED_DATA, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.LOADED_DATA);
        };

        this.mediaContext.removeEventListener(PlayState.LOADED_DATA, onLoadedDataEvent);
        this.mediaContext.addEventListener(PlayState.LOADED_DATA, onLoadedDataEvent);

        const onLoadedMetaDataEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.LOADED_METADATA, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.LOADED_METADATA);
        };

        this.mediaContext.removeEventListener(PlayState.LOADED_METADATA, onLoadedMetaDataEvent);
        this.mediaContext.addEventListener(PlayState.LOADED_METADATA, onLoadedMetaDataEvent);

        const onPlayingEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.PLAYING, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.PLAYING);
        };

        // The playing event is fired after playback is first started, and whenever it is restarted
        this.mediaContext.removeEventListener(PlayState.PLAYING, onPlayingEvent);
        this.mediaContext.addEventListener(PlayState.PLAYING, onPlayingEvent);

        const onPlayEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.PLAY, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.PLAY);
        };

        this.mediaContext.removeEventListener(PlayState.PLAY, onPlayEvent);
        this.mediaContext.addEventListener(PlayState.PLAY, onPlayEvent);

        const onPauseEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.PAUSE, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.PAUSE);
        };

        this.mediaContext.removeEventListener(PlayState.PAUSE, onPauseEvent);
        this.mediaContext.addEventListener(PlayState.PAUSE, onPauseEvent, false);

        const onStatuschanged = function (state: PlayState) {
            switch (state) {
                case PlayState.SEEKING:
                case PlayState.PLAY:
                    if (!self.isSpectrumEnabled) {
                        self.setSpectrumAnalyser();
                    }

                    self.triggerFrequencyData();
                    break;
            }
        };

        this.eventListener.removeListener(PlayState.ON_STATUS_CHANGED, onStatuschanged);
        this.eventListener.addListener(PlayState.ON_STATUS_CHANGED, onStatuschanged);

        const onEndedEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.ENDED, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.ENDED);
        };

        this.mediaContext.removeEventListener(PlayState.ENDED, onEndedEvent);
        this.mediaContext.addEventListener(PlayState.ENDED, onEndedEvent);

        this.mediaContext.onerror = function (event) {
            self.eventListener.dispatch(PlayState.ERROR, event);
        };
    }

    public isPlayable(): CanPlayTypeResult {
        return this.mediaContext.canPlayType(this.mediaType);
    }

    public isAutoPlayable(): any {
        return this.mediaContext.autoplay;
    }

    private getNetworkStateConstant(): string {
        switch (this.getNetworkState()) {
            case 1:
                return `NETWORK_EMPTY`;
            case 2:
                return `NETWORK_IDLE`;
            case 3:
                return `NETWORK_LOADING`;
            case 4:
                return `NETWORK_NO_SOURCE`;
            default:
                return `UNKNOWN`;
        }
    }

    private getNetworkState(): number {
        return this.mediaContext.networkState;
    }

    private getCurrentSource(): string {
        return this.mediaContext.currentSrc;
    }

    public getTextTracks(): any {
        return this.mediaContext.textTracks;
    }

    public fastSeek(time: number): void {
        this.mediaContext.fastSeek(time);
    }

    public isMuted(): boolean {
        return this.mediaContext.muted;
    }

    public isLooping(): boolean {
        return this.mediaContext.loop;
    }

    public clearSpectrum(selector: string, width: number = -1, height: number = -1): void {
        let targetDiv: any = document.querySelectorAll(selector);
        if (targetDiv.length > 0) {
            targetDiv = targetDiv[0];
        }

        if (!targetDiv) {
            return;
        }

        try {
            targetDiv.innerHTML = '';
            const canvas = targetDiv.appendChild(document.createElement("canvas"));
            canvas.width = width == -1 ? targetDiv.clientWidth : width;
            canvas.height = height == -1 ? targetDiv.clientHeight : height;
            canvas.style.verticalAlign = "middle";
            const canvasContext = canvas.getContext("2d");

            canvasContext.clearRect(0, 0, canvas.width, canvas.height);
            canvasContext.fillStyle = 'rgba(255, 0, 0, 0)';
        } catch (error) { }
    }

    public setVisualizerStyle(style: VisualizerStyle) {
        this.spectrumVisualizerStyle = style;
    }

    public setVisualizerLineWidth(width) {
        this.visualizerLineWidth = width;
    }

    public setSpectrum(selector: string): void {
        const visualizer = new Visualizer(selector);
        visualizer.setCanvas(this.spectrumCanvasWidth, this.spectrumCanvasHeight);
        visualizer.setSpectrumFillColor(this.spectrumFillColor);
        visualizer.setBackgroundFillColor(this.spectrumBackgroundFillColor);
        
        this.eventListener.addListener(AudioEvent.ON_FREQUENCY, (data: any) => {
            visualizer.draw(data.frequencies, data.binaryCount, this.visualizerLineWidth, this.spectrumVisualizerStyle);
        });
    }

    private triggerFrequencyData() {
        if (this.spectrumAnalyser == null) {
            return false;
        }

        const binaryCount = this.spectrumAnalyser.frequencyBinCount;
        const frequencies = new Uint8Array(binaryCount);

        const trigger = () => {
            if (!this.isPlayed() && !this.isSeeking()) {
                //return;
            }

            this.spectrumAnalyser.getByteFrequencyData(frequencies);

            if (frequencies) {
                this.eventListener.dispatch(AudioEvent.ON_FREQUENCY, {
                    frequencies: frequencies,
                    binaryCount: binaryCount
                });
            }

            setTimeout(() => {
                this.requestAnimationFrame(trigger);
            }, this.parseFrequencyTimeout);
        }

        this.requestAnimationFrame(trigger);
    }

    public cancelAnimationFrame(handle: number) {
        const cancelAnimationFrame = window.cancelAnimationFrame || window.webkitCancelAnimationFrame || window.mozCancelAnimationFrame;

        cancelAnimationFrame(handle);
    }

    public requestAnimationFrame(callback: FrameRequestCallback): number {
        const requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;

        return requestAnimationFrame(callback);
    }

    public setParseFrequencyTimeout(timeout: number): void {
        this.parseFrequencyTimeout = timeout;
    }

    public calculateRMS(data) {
        let sum = 0;

        for (let i = 0; i < data.length; i++) {
            sum += data[i] * data[i];
        }

        return Math.sqrt(sum / data.length);
    }

    public calculateFlux(data) {
        let flux = 0;

        for (let i = 1; i < data.length; i++) {
            const diff = data[i] - data[i - 1];
            flux += diff * diff;
        }

        return Math.sqrt(flux / data.length);
    }

    public calculateCentroid(data) {
        let sum = 0;
        let weightedSum = 0;
    
        for (let i = 0; i < data.length; i++) {
            sum += data[i];
            weightedSum += data[i] * i;
        }
    
        return weightedSum / sum * (this.audioContext.sampleRate / this.analyser.fftSize);
    }

    private setSpectrumAnalyser(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.spectrumAnalyser = this.audioContext.createAnalyser();
        this.mediaElementSource.connect(this.spectrumAnalyser);
        this.spectrumAnalyser.minDecibels = -120; // min FFT(fast Fourier transform) dB
        this.spectrumAnalyser.fftSize = 2048;//32 << 9;
        this.spectrumAnalyser.smoothingTimeConstant = 0.45;

        this.isSpectrumEnabled = true;
    }

    public setSource(source: string): void {
        this.mediaContext.src = source;
    }

    public addTextTrack(kind: TextTrackKind, label?: string, language?: string) {
        HTMLAudioElement.prototype
        this.mediaContext.addTextTrack(kind, label, language);
    }

    public isSeekable(): TimeRanges {
        return this.mediaContext.seekable;
    }

    public setMediaKeys(mediaKeys: MediaKeys): Promise<void> {
        return this.mediaContext.setMediaKeys(mediaKeys);
    }

    public isSeeking(): boolean {
        return this.mediaContext.seeking;
    }

    public isControlEnable() {
        return this.mediaContext.controls;
    }

    public setEnableControls(enable: boolean) {
        this.mediaContext.controls = enable;
    }

    public getSource(): string {
        return this.mediaContext.src;
    }

    public play(): void {
        this.mediaContext.play();
    }

    public pause(): void {
        this.mediaContext.pause();
    }

};