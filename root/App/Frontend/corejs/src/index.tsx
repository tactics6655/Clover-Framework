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

import { AudioService } from "./src/Component/AudioService";
import { AudioRecorder } from "./src/Component/AudioRecorder";
import { ClipboardService } from "./src/Component/ClipboardService";
import { GeolocationService } from "./src/Component/GeolocationService";
import { MediaPlayer, VisualizerStyle } from "./src/Component/MediaPlayer";
import { NotificationService } from "./src/Component/NotificationService";
import { Pagination } from "./src/Component/Pagination";
import { ScreenService } from "./src/Component/ScreenService";
import { TTSService } from "./src/Component/TTSService";
import { UserMediaService as UserMedia } from "./src/Component/UserMediaService";
import { PromiseService } from "./src/Component/PromiseService";
import { PopupService } from "./src/Component/PopupService";
import { LocalStorageService } from "./src/Component/LocalStorageService";
import { ElementService } from "./src/Component/ElementService";
import { BrowserService } from "./src/Component/BrowserService";
import { ValidatorService } from "./src/Component/ValidatorService";
import { EventService } from "./src/Component/EventService";
import { URLService } from "./src/Component/URLService";
import { WebcamService } from "./src/Component/WebcamService";
import { RequestService } from "./src/Component/RequestService";
import { JSONService } from "./src/Component/JSONService";
import { MouseService } from "./src/Component/MouseService";
import { MediaSessionService } from "./src/Component/MediaSessionService";
import { MobileDeviceService } from "./src/Component/MobileDeviceService";
import { BatteryService } from "./src/Component/BatteryService";
import { CookieService } from "./src/Component/CookieService";
import { StringService } from "./src/Component/StringService";
import { ArrayService } from "./src/Component/ArrayService";
import { WebsocketService } from "./src/Component/WebsocketService";
import { GamePadService } from "./src/Component/GamePadService";

import { AudioContextObject } from "./src/Component/Class/AudioContextObject";
import { Canvas2D } from "./src/Component/Class/Canvas2D";
import { OpenGLAttributeObject } from "./src/Component/Class/OpenGLAttributeObject";
import { OpenGLObject } from "./src/Component/Class/OpenGLObject";

import hljs from 'highlight.js';
import './../../../Resource/css/reset/reset.css';
//import './../../../Resource/base.css';
import './../../../Resource/js/highlight/styles/vs2015.min.css';

import React, { useEffect, useRef, Component } from 'react';
import ReactDOM from "react-dom/client";
import { Link, BrowserRouter, Routes, Route} from 'react-router-dom';

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

const AudioPlayer: React.FC = () => {
    const audioRef = useRef<HTMLAudioElement>(null);
    const spectrumRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (audioRef.current && spectrumRef.current) {
            const mediaPlayer = new MediaPlayer();
            mediaPlayer.setContext(document.getElementById("audio"));
            mediaPlayer.setEvents();
            mediaPlayer.setVisualizerLineWidth(1);
            mediaPlayer.setVisualizerStyle(VisualizerStyle.ROTATE_CIRCLE);
            mediaPlayer.setSpectrum("#spectrum");          
        }
    });

    return (
        <div>
            <audio id="audio" controls src="/App/File/preview.mp3" ref={audioRef}></audio>
            <div style={{width:'200px', height: '200px'}} id="spectrum" ref={spectrumRef}></div>
        </div>
    );
}

function renderApp() {
    const rootElement = document.getElementById('player');
    const root = ReactDOM.createRoot(rootElement);

    hljs.initHighlightingOnLoad();

    root.render(
        <React.StrictMode>
            <AudioPlayer />
        </React.StrictMode>
    );
}

document.addEventListener('DOMContentLoaded', renderApp);