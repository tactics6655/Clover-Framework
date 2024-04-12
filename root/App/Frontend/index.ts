declare const ActiveXObject;

declare global {
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

import NotificationService from "./src/Component/NotificationService";
import WebCam from "./src/Component/WebCam";

var service = new NotificationService();

document.addEventListener('DOMContentLoaded', function () {
    if (!service.isSupported()) {
        console.log("Service is not supported");
        return;
    }
    
    if (!service.isPermissionGranted()) {
        service.requestPermission();
    }

    service.notify('test', 'test', '', 'test', {});

});

/*declare const ActiveXObject;
declare global {
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
}*/

//declare let $:any;

/*import 'babel-polyfill';
import jQuery from 'jquery'
//require.context('./src/', false, /\$/);

import './src/config'
import './dist/variables'
//import mat4 from './src/Extention/gl-matrix-min'

import core from './src/Extention/Initialization'
import Application from './src/Function/Application'
import Array from './src/Function/Array'
import Audio from './src/Function/Audio'
import Base from './src/Function/Base'
import Battery from './src/Function/Battery'
import Browser from './src/Function/Browser'
import Cache from './src/Function/Cache'
import Canvas from './src/Function/Canvas'
import ChromeExtend from './src/Function/ChromeExtend'
import Clipboard from './src/Function/Clipboard'
import Console from './src/Function/Console'
import Cookie from './src/Function/Cookie'
import detectAdblock from './src/Function/detectAdblock'
import dragDrop from './src/Function/dragDrop'
import Effect from './src/Function/Effect'
import Element from './src/Function/Element'
import Event from './src/Function/Event'
import File from './src/Function/File'
import Flash from './src/Function/Flash'
import Gamepad from './src/Function/Gamepad'
import Generator from './src/Function/Generator'
import Geo from './src/Function/Geo'
import Google from './src/Function/Google'
import HarmonicGenerator from './src/Function/HarmonicGenerator'
import ID3 from './src/Function/ID3'
import Imoticon from './src/Function/Imoticon'
import JSON from './src/Function/JSON'
import List from './src/Function/List'
import Lyrics from './src/Function/Lyrics'
import Language from './src/Function/Language'
import MediaSession from './src/Function/MediaSession'
import MediaSource from './src/Function/MediaSource'
import Midi from './src/Function/Midi'
import Mobile from './src/Function/Mobile'
import Mouse from './src/Function/Mouse'
import Notify from './src/Function/Notify'
import OptionList from './src/Function/OptionList'
import Popup from './src/Function/Popup'
import Pagination from './src/Function/Pagination'
import Promise from './src/Function/Promise'
import Radio from './src/Function/Radio'
import Request from './src/Function/Request'
import Screen from './src/Function/Screen'
import Scroll from './src/Function/Scroll'
import Selector from './src/Function/Selector'
import SessionStorage from './src/Function/SessionStorage'
import ScreenCapture from './src/Function/ScreenCapture'
import SimpleCrypto from './src/Function/SimpleCrypto'
import SNS from './src/Function/SNS'
import Speech from './src/Function/Speech'
import Storage from './src/Function/Storage'
import StreamObject from './src/Function/StreamObject'
import String from './src/Function/String'
import System from './src/Function/System'
import Time from './src/Function/Time'
import Timer from './src/Function/Timer'
import URL from './src/Function/URL'
import Validate from './src/Function/Validate'
import WebDB from './src/Function/WebDB'
import WebCam from './src/Function/WebCam'
import WebRealbook from './src/Function/WebRealbook'
import WebSocket from './src/Function/WebSocket'
import XML from './src/Function/XML'

import UserMedia from './src/Function/UserMedia'
import RTC from './src/Function/RTC'

import AudioRecorder from './src/Component/AudioRecorder'
import MediaPlayer from './src/Component/MediaPlayer'*/
