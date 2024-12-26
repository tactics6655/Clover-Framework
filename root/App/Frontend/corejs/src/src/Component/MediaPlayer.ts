import { EventListener } from '../Classes/EventListener';

import { MediaPlayerInterface } from 'src/Interface/MediaPlayerInterface';

import { MediaSession } from './Class/MediaSession';

import { Visualizer, VisualizerStyle } from './Class/Visualizer';

export { VisualizerStyle };

enum ErrorMessages {
    NOT_IMPLEMENTED_API = 'getUserMedia is not implemented in this browser',
    NOT_INITIALIED = 'Media recorder is not initialized',
    NOT_STARTED = 'Cannot start MediaRecorder when already recording',
    EFFECTOR_IS_NOT_INITIALISED = 'Effector is not initialized',
    MEDIA_SESSION_IS_NOT_SUPPORTED = 'MediaSession is not supported'
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

enum PlayState {
    ERROR = 'error',
    ON_STATUS_CHANGED = 'onstatuschanged',
    ENDED = 'ended',
    PAUSE = 'pause',
    ABORT = 'abort',
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
    WAITING = 'waiting',
    ENCRYPTED = 'encrypted',
    SEEKED = 'seeked',
    TIMEUPDATE = 'timeupdate'
}

enum MimeTypes {
    MP3 = 'audio/mpeg',
    WAV = 'audio/wav',
    OGG = 'audio/ogg',
    WEBM = 'audio/webm',
    MP4 = 'video/mp4',
    MPEG = 'video/mpeg'
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
    private stereoPannerFilter: StereoPannerNode;

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
            throw new Error(ErrorMessages.EFFECTOR_IS_NOT_INITIALISED);
        }

        this.pannerEffector.panningModel = modelName;
    }

    public setGainValue(value: number = 1): void {
        if (this.gainEffector == null) {
            throw new Error(ErrorMessages.EFFECTOR_IS_NOT_INITIALISED);
        }

        this.gainEffector.gain.value = value;
    }

    public getSampleRate(): number {
        return this.audioContext.sampleRate;
    }

    public setDelayValue(value: number): void {
        if (this.gainEffector == null) {
            throw new Error(ErrorMessages.EFFECTOR_IS_NOT_INITIALISED);
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
        this.mediaElementSource.connect(this.scriptProcessorFilter);
        this.scriptProcessorFilter.connect(this.audioContext.destination);
    }

    public connectIIRFilter(feedforward: number[], feedback: number[]): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.iirFilter = this.audioContext.createIIRFilter(feedforward, feedback);
        this.mediaElementSource.connect(this.iirFilter);
        this.iirFilter.connect(this.audioContext.destination);
    }

    public connectStereoPannerFilter(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }
        
        this.stereoPannerFilter = this.audioContext.createStereoPanner();
        this.mediaElementSource.connect(this.stereoPannerFilter);
        this.stereoPannerFilter.connect(this.audioContext.destination);
    }

    public connectDynamicsCompressorFilterFilter(): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.dynamicsCompressorFilter = this.audioContext.createDynamicsCompressor();
        this.mediaElementSource.connect(this.dynamicsCompressorFilter);
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

    public connectDelayEffector(maxDelayTime?: number): void {
        if (!this.isAudioContextConnected) {
            this.connectToAudioContext();
        }

        this.delayEffector = this.audioContext.createDelay(maxDelayTime);
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

    public isAirPlayAvailable(): boolean {
        return typeof window.WebKitPlaybackTargetAvailabilityEvent != 'undefined';
    }

    public getPreloadProperty(): string {
        return this.mediaContext.preload;
    }

    public isRemotePlaybackAvailable(): boolean {
        return 'remote' in HTMLMediaElement.prototype;
    }

    public getRemotePlayback(): RemotePlayback {
        return this.mediaContext.remote;
    }

    public getDefaultPlaybackRate(): number {
        return this.mediaContext.defaultPlaybackRate;
    }

    public setDisableRemotePlayback(disable: boolean) {
        this.mediaContext.disableRemotePlayback = disable;
    }

    public setDefaultPlaybackRate(): void {
        this.setPlaybackRate(this.getDefaultPlaybackRate());
    }

    public setPlaybackRate(rate: number): void {
        this.mediaContext.playbackRate = rate;
    }

    public setPreservesPitch(preserve: boolean): void {
        this.mediaContext.preservesPitch = preserve;
    }

    public haveFutureData(): boolean {
        return this.getReadyState() == this.mediaContext.HAVE_FUTURE_DATA;
    }

    public haveEnoughData(): boolean {
        return this.getReadyState() == this.mediaContext.HAVE_ENOUGH_DATA;
    }

    public haveCurrentData(): boolean {
        return this.getReadyState() == this.mediaContext.HAVE_CURRENT_DATA;
    }

    public haveMetaData(): boolean {
        return this.getReadyState() == this.mediaContext.HAVE_METADATA;
    }

    public haveNothing(): boolean {
        return this.getReadyState() == this.mediaContext.HAVE_NOTHING;
    }

    public haveLeastData(): boolean {
        return this.getReadyState() > this.mediaContext.HAVE_CURRENT_DATA;
    }

    public isPlayed(): boolean {
        return this.mediaContext
            && !this.isPaused()
            && !this.isEnded()
            && this.haveLeastData();
    }

    public isPlaying(): boolean {
        return this.mediaContext
            && this.getCurrentTime() > 0
            && !this.isPaused()
            && !this.isEnded()
            && this.haveLeastData();
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

            return;
        } 

        this.setCurrentTime(0);
    }

    public seekForward(seconds: number): void {
        if (this.getCurrentTime() + seconds <= this.getCurrentDuration()) {
            this.setCurrentTime(this.getCurrentTime() + seconds);

            return;
        } 

        this.setCurrentTime(this.getCurrentDuration());
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

    public getDuration(): Promise<unknown> | void {
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

    public setMediaSessionMetaData(init?: MediaMetadataInit): void {
        if (!this.mediaSessionHandler.isMediaSessionSupported()) {
            throw new Error(ErrorMessages.MEDIA_SESSION_IS_NOT_SUPPORTED);
        }

        this.mediaSessionHandler.setMetadata(init);
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
        
        const onEncryptedEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.ENCRYPTED, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.ENCRYPTED);
        };

        this.mediaContext.removeEventListener(PlayState.ENCRYPTED, onEncryptedEvent);
        this.mediaContext.addEventListener(PlayState.ENCRYPTED, onEncryptedEvent);
        
        const onTimeUpdateEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.TIMEUPDATE, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.TIMEUPDATE);
        };

        this.mediaContext.removeEventListener(PlayState.TIMEUPDATE, onTimeUpdateEvent);
        this.mediaContext.addEventListener(PlayState.TIMEUPDATE, onTimeUpdateEvent);

        const onSeekedEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.SEEKED, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.SEEKED);
        };

        this.mediaContext.removeEventListener(PlayState.SEEKED, onSeekedEvent);
        this.mediaContext.addEventListener(PlayState.SEEKED, onSeekedEvent);

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

        const onAbortEvent = function (event: Event) {
            self.eventListener.dispatch(PlayState.ABORT, event);

            self.eventListener.dispatch(PlayState.ON_STATUS_CHANGED, PlayState.ABORT);
        };

        this.mediaContext.removeEventListener(PlayState.ABORT, onAbortEvent);
        this.mediaContext.addEventListener(PlayState.ABORT, onAbortEvent);

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
            case this.mediaContext.NETWORK_EMPTY:
                return `NETWORK_EMPTY`;
            case this.mediaContext.NETWORK_IDLE:
                return `NETWORK_IDLE`;
            case this.mediaContext.NETWORK_LOADING:
                return `NETWORK_LOADING`;
            case this.mediaContext.NETWORK_NO_SOURCE:
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

    public setVisualizerStyle(style: VisualizerStyle): void {
        this.spectrumVisualizerStyle = style;
    }

    public setVisualizerLineWidth(width: number): void {
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

    private triggerFrequencyData(): void | boolean {
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

    public calculateRMS(data: Array<number>) {
        let sum = 0;

        for (let i = 0; i < data.length; i++) {
            sum += data[i] * data[i];
        }

        return Math.sqrt(sum / data.length);
    }

    public calculateFlux(data: Array<number>) {
        let flux = 0;

        for (let i = 1; i < data.length; i++) {
            const diff = data[i] - data[i - 1];
            flux += diff * diff;
        }

        return Math.sqrt(flux / data.length);
    }

    public calculateCentroid(data: Array<number>): number {
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
        this.spectrumAnalyser.fftSize = 2048; //32 << 9;
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