declare global {
    interface HTMLElement {
        mozRequestPointerLock: any;
    }
    interface Navigator {
        getBattery: any;
        mozNotification: any;
        requestWakeLock: any;
        mozPower: any;
        mozFMRadio: any;
        webkitGetUserMedia: any;
        getUserMedia: any;
        msGetUserMedia: any;
        mozGetUserMedia: any;
    }
    interface Document {
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
    interface JQueryStatic {
        core: any;
        log: any;
    }
    interface CanvasRenderingContext2D {
        globalAlpha: any;
    }
}

declare let $:any;

import 'babel-polyfill';
import jQuery from './jquery-3.3.1.min.js'
require.context('./src/', false, /\.ts$/);

import './src/config.js'
import './dist/variables.js'
import mat4 from './src/Extention/gl-matrix-min.ts'

import core from './src/Extention/Initialization.ts'
import Application from './src/Function/Application.ts'
import Array from './src/Function/Array.ts'
import Audio from './src/Function/Audio.ts'
import Base from './src/Function/Base.ts'
import Battery from './src/Function/Battery.ts'
import Browser from './src/Function/Browser.ts'
import Cache from './src/Function/Cache.ts'
import Canvas from './src/Function/Canvas.ts'
import ChromeExtend from './src/Function/ChromeExtend.ts'
import Clipboard from './src/Function/Clipboard.ts'
import Console from './src/Function/Console.ts'
import Cookie from './src/Function/Cookie.ts'
import detectAdblock from './src/Function/detectAdblock.ts'
import dragDrop from './src/Function/dragDrop.ts'
import Effect from './src/Function/Effect.ts'
import Element from './src/Function/Element.ts'
import Event from './src/Function/Event.ts'
import File from './src/Function/File.ts'
import Flash from './src/Function/Flash.ts'
import Gamepad from './src/Function/Gamepad.ts'
import Generator from './src/Function/Generator.ts'
import Geo from './src/Function/Geo.ts'
import Google from './src/Function/Google.ts'
import HarmonicGenerator from './src/Function/HarmonicGenerator.ts'
import ID3 from './src/Function/ID3.ts'
import Imoticon from './src/Function/Imoticon.ts'
import JSON from './src/Function/JSON.ts'
import List from './src/Function/List.ts'
import Lyrics from './src/Function/Lyrics.ts'
import Language from './src/Function/Language.ts'
import MediaSession from './src/Function/MediaSession.ts'
import MediaSource from './src/Function/MediaSource.ts'
import Midi from './src/Function/Midi.ts'
import Mobile from './src/Function/Mobile.ts'
import Mouse from './src/Function/Mouse.ts'
import Notify from './src/Function/Notify.ts'
import OptionList from './src/Function/OptionList.ts'
import Popup from './src/Function/Popup.ts'
import Pagenation from './src/Function/Pagenation.ts'
import Promise from './src/Function/Promise.ts'
import Radio from './src/Function/Radio.ts'
import Request from './src/Function/Request.ts'
import Screen from './src/Function/Screen.ts'
import Scroll from './src/Function/Scroll.ts'
import Selector from './src/Function/Selector.ts'
import SessionStorage from './src/Function/SessionStorage.ts'
import ScreenCapture from './src/Function/ScreenCapture.ts'
import SimpleCrypto from './src/Function/SimpleCrypto.ts'
import SNS from './src/Function/SNS.ts'
import Speech from './src/Function/Speech.ts'
import Storage from './src/Function/Storage.ts'
import StreamObject from './src/Function/StreamObject.ts'
import String from './src/Function/String.ts'
import System from './src/Function/System.ts'
import Time from './src/Function/Time.ts'
import Timer from './src/Function/Timer.ts'
import URL from './src/Function/URL.ts'
import Validate from './src/Function/Validate.ts'
import WebDB from './src/Function/WebDB.ts'
import WebCam from './src/Function/WebCam.ts'
import WebRealbook from './src/Function/WebRealbook.ts'
import WebSocket from './src/Function/WebSocket.ts'
import XML from './src/Function/XML.ts'

import UserMedia from './src/Function/UserMedia.ts'
import RTC from './src/Function/RTC.ts'

import AudioRecorder from './src/Component/AudioRecorder.ts'
import MediaPlayer from './src/Component/MediaPlayer.ts'