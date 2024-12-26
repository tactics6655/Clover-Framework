import { PictureInPictureInterface } from "src/Interface/PictureInPictureInterface";

export class PictureInPicture implements PictureInPictureInterface {

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