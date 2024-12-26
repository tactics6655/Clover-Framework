export interface MediaSessionInterface {
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