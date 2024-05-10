
import {AudioContextObject} from './../Function/Class/AudioContextObject';

export class AudioService {

    private streamAudio;

    setMidiFrequency (oscillatorContext, currentTime, midiNote, timeConstant = 0) {
        const frequency = Math.pow(2, (midiNote - 69) / 12) * 440;
        
        this.setOscillatorFrequency(oscillatorContext, frequency, currentTime, timeConstant);
    }
    
    setOscillatorFrequency (oscillatorContext, frequency, currentTime, timeConstant = 0) {
        oscillatorContext.frequency.setTargetAtTime(frequency, currentTime, timeConstant);
    }

    hasMediaCapabilities () {
        if ('mediaCapabilities' in navigator) {
            return true;
        }
        
        return false;
    }

    hasLoop () {
        if ('loop' in document.createElement('audio')) {
            return true;
        }
        
        return false;
    }

    isMediaCapabilities (audioFileConfiguration, callback) {
        if (this.hasMediaCapabilities()) {
            /*
                const audioFileConfiguration = {
                    type : 'file', 
                    audio : {
                        contentType: "audio/mp3",
                        channels: 2,  
                        bitrate: 132700, 
                        samplerate: 5200 
                    } 
                  }; 
            */
            navigator.mediaCapabilities.decodingInfo(audioFileConfiguration).then(function (result) {
                callback(callback);
            });
        }
    }

    isPlayable (element, type, codecs) {
        let _codecs = '';
        let bool;
        
        switch (type) {
            case "opus":
                type = 'audio/opus';
                _codecs = 'opus';
                bool = element.canPlayType(type + ';codecs="' + _codecs + '"');
                break;
            case "ogg":
                type = 'audio/ogg';
                _codecs = 'theora, vorbis';
                bool = element.canPlayType(type + ';codecs="' + _codecs + '"');
                break;
            case "oga":
                type = 'audio/ogg';
                _codecs = 'vorbis';
                bool = element.canPlayType(type + ';codecs="' + _codecs + '"');
                break;
            case "wav":
                type = 'audio/wav';
                _codecs = '1';
                bool = element.canPlayType(type + ';codecs="' + _codecs + '"');
                break;
            case "weba":
                type = 'audio/weba';
                _codecs = 'vorbis';
                bool = element.canPlayType(type + ';codecs="' + _codecs + '"');
                break;
            case "webm":
                type = 'audio/weba';
                _codecs = 'vp8.0, vorbis';
                bool = element.canPlayType(type + ';codecs="' + _codecs + '"');
                break;
            case "dolby":
                type = 'audio/mp4';
                _codecs = 'ec-3';
                bool = element.canPlayType(type + ';codecs="' + _codecs + '"');
                break;
            case "aiff":
                bool = element.canPlayType("audio/x-aiff;");
                break;
            case "flac":
                bool = element.canPlayType("audio/x-flac;") || 
                    element.canPlayType("audio/flac;");
                break;
            case "m4a":
                bool = element.canPlayType("audio/x-m4a;") || 
                    element.canPlayType("audio/m4a;") || 
                    element.canPlayType("audio/aac;");
                break;
            case "mp4":
                bool = element.canPlayType('audio/x-mp4;codecs="avc1.42E01E, mp4a.40.2"') || 
                    element.canPlayType("audio/mp4;") || 
                    element.canPlayType("audio/aac;");
                break;
            case "caf":
                bool = element.canPlayType("audio/x-caf;");
                break;
            case "aac":
                bool = element.canPlayType("audio/aac;");
                break;
            case "mpeg":
                bool = element.canPlayType("audio/mpeg;");
                break;
            case "mp3":
                bool = element.canPlayType("audio/mp3;") || element.canPlayType("audio/mpeg;");
                break;
            default:
                
        }
        
        return bool;
    }

    isSupport () {
        if (window.HTMLAudioElement && !$.core.Mobile.isRefMobile()) {
            return true;
        }
        
        return false;
    }

    isPlaying (audioObject) {
        var letObject = audioObject || this.streamAudio;
        
        if (typeof this.streamAudio == 'object') {
            try {
                if (this.streamAudio.currentTime > 0 && !this.streamAudio.paused && !this.streamAudio.ended && this.streamAudio.readyState > 2) {
                    return true;
                }
            } catch(e) {}
            
            return false;
        }
    }

    Object (src, audioObject) {
        var letObject = audioObject || this.streamAudio;
        
        this.streamAudio.src = src;
        /*this.play = function () {
            this.StreamAudio.playAudio();
        };*/
        
        Object.defineProperty(this.streamAudio, "default", {
            //volume: 100,
            writable: false,
            enumerable: false,
            configurable: false
        });

        Object.defineProperty(this.streamAudio, 'src', {
            get: function () {
                return this.src;
            },

            set: function(src) {
                this.src = src;
            }
        });
    }

    stopAudio (audioObject) {
        let audio = audioObject || this.streamAudio;
        
        this.pauseAudio(audio);
        this.setAudioTime(0, audio);
    }

    loadAudio (src, audioObject) {
        var letObject = audioObject || this.streamAudio;
        
        if (this.isSupport()) {
            if (!this.streamAudio || typeof this.streamAudio !== 'object') {
                this.streamAudio = document.createElement("audio");
            }
            
            try{
                const playPromise = this.streamAudio.play();
                
                if (this.isPlaying(this.streamAudio)) {
                    this.pauseAudio(this.streamAudio);
                }
                
                if (playPromise !== null) {
                    this.streamAudio.setAttribute("src", src);
                    this.streamAudio.src = src;
                    this.streamAudio.load();
                } else if (typeof playPromise !== 'undefined') {
                    playPromise.then(function () {
                        this.StreamAudio.setAttribute("src", src);
                        this.StreamAudio.src = src;
                        this.StreamAudio.load();
                    }).catch(function () {
                        if (this.isPlaying()) {
                            this.pauseAudio();
                        }
                        
                        this.StreamAudio.setAttribute("src", src);
                        this.StreamAudio.load();
                    });
                }
            } catch(e) {
                console.log(e);
            }
        }
    }

    decodeAudio (result, audioContext, callback, failCallback) {
        audioContext.decodeAudioData(result, function (buffer) {
            callback(audioContext, buffer);
        }, function (e) {
            failCallback(e);
        });
    }

    playAudio (audioObject) {
        var letObject = audioObject || this.streamAudio;
        
        if (typeof this.streamAudio == 'object') {
            this.streamAudio.play();
        }
    }

    pauseAudio (audioObject) {
        var letObject = audioObject || this.streamAudio;
        
        if (typeof this.streamAudio == 'object') {
            this.streamAudio.pause();
        }
    }

    setAudioTime (time, audioObject) {
        var letObject = audioObject || this.streamAudio;
        
        if (typeof this.streamAudio == 'object') {
            this.streamAudio.currentTime = time;
        }
    }

    getOfflineAudioContext () {
        var offlineAudioContext = null;

        try {
            offlineAudioContext = window.OfflineAudioContext;

            return new(offlineAudioContext)();
        } catch (e) {}

        return offlineAudioContext;
    }

    getContextObject () {
        var audioContext = this.getContext();
        var contextObject = new AudioContextObject(audioContext);
        
        return contextObject;
    }

    getContext () {
        let AudioContext = null;

        try {
            if (!window.hasOwnProperty('webkitAudioContext') && window.hasOwnProperty('AudioContext')) {
                AudioContext = window.AudioContext;
            } else {
                AudioContext = window.webkitAudioContext || window.mozAudioContext || window.msAudioContext;
            }
            
            return new (AudioContext)();
        } catch (error) {}
        
        return AudioContext;
    }

    createMediaElementSource (audioContext, audioElement) {
        return audioContext.createMediaElementSource(audioElement);
    }
    
    getNode (context, audioElement) {
        return context.createMediaElementSource(audioElement);
    }

    setBiquadFilter (context, type, freq, gain) {
        let effect = context.createBiquadFilter();
        effect.type = type;
        
        try{
            effect.frequency.setTargetAtTime(0, context.currentTime, freq);
        } catch(e) {
            effect.frequency.value = freq; //is deprecated and will be removed in M64, around January 2018.
        }
        
        try{
            effect.gain.setTargetAtTime(0, context.currentTime, gain);
        } catch(e) {
            effect.gain.value = gain;
        }
        
        return effect;
    }

    setDelay (context, audioNode, time) {
        let effect = context.createDelay();
        effect.delayTime.value = time;
        audioNode.connect(effect);
        audioNode.connect(context.destination);
        effect.connect(context.destination);
    }
    
    contextSamplesToSeconds (audioContext, value) {
        return audioContext.sampleRate / value;
    }
    
    contextGetSamplesRate (audioContext) {
        return audioContext.sampleRate;
    }
    
    getContextCurrentTime (audioContext) {
        return audioContext.currentTime;
    }
    
    getContextListener (audioContext) {
        return audioContext.listener;
    }

    setGain (context, element) {
        var variable = context.createGain();
        element.connect(variable);
        return variable;
    }

    setPan (context, audioNode: any) {
        let effect = context.createPanner();
        effect.panningModel = "equalpower";
        audioNode.connect(effect);
        audioNode.connect(context.destination);
        effect.connect(context.destination);
    }
    
    setInvert (value, context, element) {
        let effect = context.createGain();
        effect.gain.value = value;
        element.connect(effect);
        return effect;
    }

    createGain (audioContext) {
        if (typeof audioContext.prototype.createGain !== 'function') {
            audioContext.prototype.createGain = audioContext.prototype.createGainNode;
        }
        
        return audioContext.createGain();
    }
    
    createBiquadFilter (audioContext) {
        return audioContext.createBiquadFilter();
    }

    setPeriodicWave (oscillatorNode) {
        if (typeof oscillatorNode.prototype.setPeriodicWave !== 'function') {
            oscillatorNode.prototype.setPeriodicWave = oscillatorNode.prototype.setWaveTable;
        }
        
        return oscillatorNode.setPeriodicWave;
    }

    stopOscillatorNode (oscillatorNode) {
        if (typeof oscillatorNode.prototype.stop !== 'function') {
            oscillatorNode.prototype.stop = oscillatorNode.prototype.noteOff;
        }
        
        return oscillatorNode.stop;
    }
    
    startOscillatorNode (oscillatorNode) {
        if (typeof oscillatorNode.prototype.start !== 'function') {
            oscillatorNode.prototype.start = oscillatorNode.prototype.noteOn;
        }
        
        return oscillatorNode.start;
    }

    stopBufferSourceNode (audioBufferSourceNode) {
        if (typeof audioBufferSourceNode.prototype.stop == 'function') {
            audioBufferSourceNode.prototype.stop = audioBufferSourceNode.prototype.noteGrainOff;
        }
        
        return audioBufferSourceNode.stop;
    }

    startBufferSourceNode (audioBufferSourceNode) {
        if (typeof audioBufferSourceNode.prototype.start == 'function') {
            audioBufferSourceNode.prototype.start = audioBufferSourceNode.prototype.noteGrainOn;
        }
        
        return audioBufferSourceNode.start;
    }

    createPeriodicWave (audioContext) {
        if (typeof audioContext.prototype.createPeriodicWave == 'function') {
            audioContext.prototype.createPeriodicWave = audioContext.prototype.createWaveTable;
        }
        
        return audioContext.createPeriodicWave();
    }
    
    createDelay (audioContext) {
        if (typeof audioContext.prototype.createDelay == 'function') {
            audioContext.prototype.createDelay = audioContext.prototype.createDelayNode;
        }
        
        return audioContext.createDelay();
    }

    createPanner (audioContext) {
        return audioContext.createPanner();
    }
    
    createStereoPanner (context) {
        return context.createStereoPanner;
    }
    
    createOscillator (context) {
        return context.createOscillator();
    }
    
    createAnalyser (context) {
        return context.createAnalyser;
    }

    closeAudioEffect (input, output, AudioNode) {
        let _input = input;
        let _output = output;

        if (typeof input != 'function') {
            if (_input instanceof AudioNode) {
                _input.disconnect();
            } 

            _input = null;
        }
        
        if (typeof output != 'function') {
            if (_output instanceof AudioNode) {
                _output.disconnect();
            }

            _output = null;
        }
    }

    freqBinCountAnalyser (analyser) {
        return analyser.frequencyBinCount; //Array
    }

    getByteFreqDataAnalyser (analyser, array) {
        return analyser.getByteFrequencyData(array);
    }
    
    createBuffSource (context) {
        return context.createBufferSource;
    }

    createOscillatorContext () {
        let audioContext = this.getContext();
        let Oscillator = this.createOscillator(audioContext);
        Oscillator.connect(audioContext.destination);
        
        return [Oscillator, audioContext];
    }

}