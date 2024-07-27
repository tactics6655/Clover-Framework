declare const ActiveXObject;

declare const DocumentTouch;

declare global {

    interface DeferredPromise {
        resolve: (data: any) => {};
        reject: (error: any) => {};
        promise: Promise<any>;
    }

    interface BatteryManager {
        charging: boolean;
        chargingTime: number;
        dischargingTime: number;
        level: number;
        onchargingchange: any;
        onchargingtimechange: any;
        ondischargingtimechange: any;
        onlevelchange: any;
    }

    interface console {
        err: any;
    }

    interface Date {
        toGMTString: any;
    }

    interface HTMLElement {
        mozRequestPointerLock: any;
        getContext: any;
        type: any;
    }

    interface Navigator {
        userLanguage: any;
        webkitConnection: any;
        connection: any;
        mozConnection: any;
        msVibrate: any;
        mozVibrate: any;
        webkitVibrate: any;
        getBattery: any;
        mozNotification: any;
        requestWakeLock: any;
        mozPower: any;
        mozFMRadio: any;
        webkitGetUserMedia: any;
        getUserMedia: any;
        msGetUserMedia: any;
        mozGetUserMedia: any;
        webkitGetGamepads: any;
    }

    interface Element {
        getWidth: any;
        getHeight: any;
        requestFullScreen: any;
        toggleFullScreen: any;
        cancelFullScreen: any;
    }

    interface Document {
        getAttribute: any;
        webkitHidden: any;
        msHidden: any;
        onmousewheel: any;
        selection: any;
        createEventObject: any;
        mozPointerLockElement: any;
        mozExitPointerLock: any;
        documentMode?: any;
        msFullscreenElement: any;
        mozCancelFullScreen: () => void;
        webkitExitFullscreen: () => void;
        fullscreenElement: () => void;
        mozFullScreenElement: () => void;
        webkitFullscreenElement: () => void;
        webkitCancelFullScreen: any;
        msExitFullscreen: any;
        fullScreen: any;
        webkitIsFullScreen: any;
        mozFullScreen: any;
    }

    interface HTMLElement {
        msRequestFullscreen?: () => Promise<void>;
        mozRequestFullscreen?: () => Promise<void>;
        webkitRequestFullscreen?: () => Promise<void>;
    }

    interface Window {
        WebKitPlaybackTargetAvailabilityEvent: any;
        getUserMedia: any;
        webkitGetUserMedia: any;
        mozGetUserMedia: any;
        msGetUserMedia: any;
        speechRecognition: any;
        ga: any;
        jQuery: any;
        dataURLtoBlob: any;
        webkitAudioContext: any;
        mozAudioContext: any;
        msAudioContext: any;
        DocumentTouch: any;
        chrome: any;
        webkitPerformance: any;
        sidebar: any;
        language: any;
        webkitCreateShadowRoot: any;
        createShadowRoot: any;
        mozPerformance: any;
        mozRequestAnimationFrame: any;
        msPerformance: any;
        externalHost: any;
        webkitRequestAnimationFrame: any;
        msRequestAnimationFrame: any;
        oRequestAnimationFrame: any;
        webkitCancelAnimationFrame: any;
        msCancelAnimationFrame: any;
        mozCancelAnimationFrame: any;
        lang: any;
        MSPointerEvent: any;
        adsbygoogle: any;
        MSBlobBuilder: any;
        BlobBuilder: any;
        WebKitBlobBuilder: any;
        MozBlobBuilder: any;
        webkitRTCPeerConnection: any;
        mozRTCPeerConnection: any;
        webkitMediaSource: any;
        opera: any;
        ActiveXObject: any;
        XDomainRequest: any;
        webkitNotifications: any;
        mozIndexedDB: () => void;
        webkitIndexedDB: () => void;
        msIndexedDB: () => void;
        webkitIDBTransaction: () => void;
        msIDBTransaction: () => void;
        webkitIDBKeyRange: () => void;
        msIDBKeyRange: () => void;
        openDatabase: () => void;
        SpeechRecognition: () => void;
        webkitSpeechRecognition: () => void;
        createObjectURL: any;
        mozURL: any;
        msURL: any;
        clipboardData: any;
        attachEvent: any;
    }

    interface Number {
        clamp: any;
        mod: any;
    }

    interface Notification {
        permissionLevel: any;
    }

    interface JQueryStatic {
        browser: any;
        isReady: any;
        core: any;
        log: any;
    }

    interface CanvasRenderingContext2D {
        globalAlpha: any;
    }
}

export { AudioService } from "./src/Component/AudioService";
export { AudioRecorder } from "./src/Component/AudioRecorder";
export { ClipboardService } from "./src/Component/ClipboardService";
export { GeolocationService } from "./src/Component/GeolocationService";
export { MediaPlayer } from "./src/Component/MediaPlayer";
export { NotificationService } from "./src/Component/NotificationService";
export { Pagination } from "./src/Component/Pagination";
export { ScreenService } from "./src/Component/ScreenService";
export { TTSService } from "./src/Component/TTSService";
export { UserMediaService as UserMedia } from "./src/Component/UserMediaService";
export { PromiseService } from "./src/Component/PromiseService";
export { PopupService } from "./src/Component/PopupService";
export { LocalStorageService } from "./src/Component/LocalStorageService";
export { ElementService } from "./src/Component/ElementService";
export { BrowserService } from "./src/Component/BrowserService";
export { ValidatorService } from "./src/Component/ValidatorService";
export { EventService } from "./src/Component/EventService";
export { URLService } from "./src/Component/URLService";
export { WebcamService } from "./src/Component/WebcamService";
export { RequestService } from "./src/Component/RequestService";
export { JSONService } from "./src/Component/JSONService";
export { MouseService } from "./src/Component/MouseService";
export { MediaSessionService } from "./src/Component/MediaSessionService";
export { MobileDeviceService } from "./src/Component/MobileDeviceService";
export { BatteryService } from "./src/Component/BatteryService";
export { CookieService } from "./src/Component/CookieService";
export { StringService } from "./src/Component/StringService";
export { ArrayService } from "./src/Component/ArrayService";
export { WebsocketService } from "./src/Component/WebsocketService";
export { GamePadService } from "./src/Component/GamePadService";

import { ScreenService } from "./src/Component/ScreenService";
import { ElementService } from "./src/Component/ElementService";

export { AudioContextObject } from "./src/Component/Class/AudioContextObject";
export { Canvas2D } from "./src/Component/Class/Canvas2D";
export { OpenGLAttributeObject } from "./src/Component/Class/OpenGLAttributeObject";
export { OpenGLObject } from "./src/Component/Class/OpenGLObject";

if (typeof Number.prototype.mod !== 'function') {
    Number.prototype.mod = function (n: number) {
        return ((this % n) + n) % n;
    };
}

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
    };
}

if (typeof Number.prototype.clamp !== 'function') {
    Number.prototype.clamp = function (min: number, max: number) {
        return Math.min(Math.max(this, min), max);
    };
}

if (!Element.prototype.getHeight) {
    Element.prototype.getHeight = function getHeight() {
        ElementService.getHeight(this);
    }
}

if (!Element.prototype.setAttribute) {
    Element.prototype.setAttribute = function setAttribute(attributes) {
        ElementService.setAttribute(this, attributes);
    }
}

if (!Element.prototype.removeAttribute) {
    Element.prototype.removeAttribute = function removeAttribute(attributes) {
        ElementService.removeAttribute(this, attributes);
    }
}

if (!Element.prototype.getWidth) {
    Element.prototype.getWidth = function getWidth() {
        ElementService.getWidth(this);
    }
}

if (!Element.prototype.requestFullScreen) {
    Element.prototype.requestFullScreen = function requestFullScreen() {
        ScreenService.requestFullScreen(this);
    }
}

if (!Element.prototype.toggleFullScreen) {
    Element.prototype.toggleFullScreen = function toggleFullScreen() {
        ScreenService.toggleFullScreen(this);
    }
}

if (!Element.prototype.cancelFullScreen) {
    Element.prototype.cancelFullScreen = function cancelFullScreen() {
        ScreenService.cancelFullScreen(this);
    }
}