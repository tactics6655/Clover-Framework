import { MediaSessionInterface } from "src/Interface/MediaSessionInterface";

export class MediaSession implements MediaSessionInterface {
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

    public setPlaybackRatePositionState(playbackRate?: number): void {
        this.setPositionState({playbackRate: playbackRate});
    }

    public setDurationPositionState(duration?: number): void {
        this.setPositionState({duration: duration});
    }

    public setPositionState(state?: MediaPositionState): void {
        navigator.mediaSession.setPositionState(state);
    }

    public setMetadata(init?: MediaMetadataInit): void {
        if (!this.isMediaSessionSupported()) {
            return;
        }

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