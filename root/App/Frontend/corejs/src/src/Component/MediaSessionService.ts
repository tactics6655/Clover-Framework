export class MediaSessionService {

    public static isSupported () {
        if ('MediaSession' in navigator) {
            return true;
        }
        
        return false;
    }
    
    public static setMetadata(title, artist, album, artwork) {
        navigator.mediaSession.metadata = new MediaMetadata({
            title: title,
            artist: artist,
            album: album,
            artwork: artwork
            /*
                artwork: [
                    { src: 'https://dummyimage.com/96x96',   sizes: '96x96',   type: 'image/png' }
    
                    { src: 'https://dummyimage.com/128x128', sizes: '128x128', type: 'image/png' }
    
                    { src: 'https://dummyimage.com/192x192', sizes: '192x192', type: 'image/png' }
    
                    { src: 'https://dummyimage.com/256x256', sizes: '256x256', type: 'image/png' }
    
                    { src: 'https://dummyimage.com/384x384', sizes: '384x384', type: 'image/png' }
    
                    { src: 'https://dummyimage.com/512x512', sizes: '512x512', type: 'image/png' }
    
                ]
            */
        });
    }
    
    public static setActionHandler(action, callback) {
        if (this.isSupported ()) {
            navigator.mediaSession.setActionHandler(action, callback);
        }
    }
    
    public static setPlayHandler(callback) {
        this.setActionHandler('play', callback);
    }
    
    public static setPauseHandler(callback) {
        this.setActionHandler('pause', callback);
    }
    
    public static setSeekbackwardHandler(callback) {
        this.setActionHandler('seekbackward', callback);
    }
    
    public static setSeekforwardHandler(callback) {
        this.setActionHandler('seekforward', callback);
    }
    
    public static setPrevioustrackHandler(callback) {
        this.setActionHandler('previoustrack', callback);
    }
    
    public static setNextrackHandler(callback) {
        this.setActionHandler('nexttrack', callback);
    }
    
    public static setPlaybackState(state) {
        navigator.mediaSession.playbackState = state;
    }
    
    public static setStateToPlaying() {
        this.setPlaybackState("playing");
    }
    
    public static setStateToPaused() {
        this.setPlaybackState("paused");
    }

}