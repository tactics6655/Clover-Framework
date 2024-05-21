export class TTSService {
    getSynthesis () {
        return window.speechSynthesis;
    }
    
    getSpeechRecognition () {
        return window.speechRecognition || window.webkitSpeechRecognition;
    }
    
    getVoices () {
        var synth = this.getSynthesis();
        return synth.getVoices();
    }
    
    getVoicesLength () {
        var voices = this.getVoices();
        return voices.length;
    }
    
    getPopularVoiceList () {
        var voiceList = [];
        var synth = this.getSynthesis();
        var voices = synth.getVoices();
        for (var i = 0; i < voices.length; i++) {
            voiceList.push(voices[i]);
        }
        
        return voiceList;
    }

    speech (word, speecher, pitch, rate) {
        var i;
        var synth = this.getSynthesis();
        var utterThis = new SpeechSynthesisUtterance(word);
        var voices = this.getVoices();
        
        for (i = 0; i < voices.length; i++) {
            if (voices[i].name === speecher) {
                utterThis.voice = voices[i];
            }
        }
        
        utterThis.pitch = pitch;
        utterThis.rate = rate;
        synth.speak(utterThis);
    }
}