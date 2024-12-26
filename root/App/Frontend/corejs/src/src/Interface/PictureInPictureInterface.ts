export interface PictureInPictureInterface {
    hasPip(): boolean;
    setPipMode(): Promise<PictureInPictureWindow> | boolean;
    exitPip(): Promise<void> | boolean;
}