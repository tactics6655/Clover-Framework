import * as XMLHTTP from './../Variables/XMLHTTP';
import { ValidatorService } from './ValidatorService';
import { EventService } from './EventService';
import { URLService } from './URLService';
import { BrowserService } from './BrowserService';
import { PromiseService } from './PromiseService';
import { JSONService } from './JSONService';

export class RequestService {

    private static ajaxCallbacks;

    private static ajaxFailCallbacks;

    private static customCallbacks;

    private static isRequestProcessing: boolean;

    public static isCachedRequest(type) {
        return (/^(GET|HEAD|POST|PATCH)$/.test(type))
    }
    
    public static isSafeRequest(type) {
        return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(type))
    }
    
    public static isValidRequest(type) {
        return (/^(GET|POST|HEAD|PUT|DELETE|CONNECT|PATCH|OPTIONS|TRACE)$/.test(type))
    }
    
    public static getScript(script) {
        $.getScript(script);
    }
    
    public static getReadyStatus() {
        return document.readyState; //get dynamic status
    }
    
    public static isMalwareProxy() {
        try {
            return window.location.host.endsWith(".duapp.com") || window.location.host.endsWith(".25lm.com")
        } catch (e) {
            return !1
        }
    }
    
    /**
        $.Request.isUrlExists(href, function (success) {
                if (success) {
                    alert('success');
                } else {
                    alert('failed');
                }
        })
        
        * Check Url is Exist
        * @param {url}        : url
        * @param {callback}   : Callback
        **/
    public static isUrlExists(url, callback) {
        if (!ValidatorService.isFunc(callback)) {
            throw Error('Callback is not function');
        }

        $.ajax({
            type: 'HEAD',
            url: url,
            success() {
                $.proxy(callback, this, true);
            },
            error() {
                $.proxy(callback, this, false);
            }
        });
    }
    
    public static isXDomainRequest(res) {
        var XDomainRequest = window.XDomainRequest;
        return XDomainRequest && res instanceof XDomainRequest;
    }
    
    public static runCustomCallback(id, prefix, args = {}) {
        if (ValidatorService.isFunc(this.customCallbacks[id][prefix])) {
            this.customCallbacks[id][prefix].call(this, args);
        }
    }
    
    public static addCustomCallback(id, prefix, callback) {
        if (!ValidatorService.isUndefined(this.customCallbacks[id])) {
            $.log(id + ' ajax callback is exists');
            this.customCallbacks[id] = {};
        }
        
        if (ValidatorService.isFunc(callback)) {
            this.customCallbacks[id][prefix] = callback;
        }
        
        return this;
    }
    
    /**
     * add Ajax Sucess Callback
     * @param {id}        : id
     * @param {callback}  : Callback
     **/
    public static addAjaxCallback(id, callback, preFetch) {
        var callerScript = EventService.getCallerScriptPath();
        callerScript = callerScript[3];
        
        var host = location.hostname;
        var protocol = location.hostname == 'localhost' ? '' : document.location.protocol + '//';
        var domain = protocol + host;
        var regex = new RegExp(domain + '\/.*.js', "i");
        var isSafeCaller = regex.test(callerScript);
        
        if (!isSafeCaller) {
            //console.log('do not allow fix the script ' + callerScript);
            //return;
        }
        
        if (jQuery.isReady) {
            //console.log('do not allow add ajax callback on document ready');
            //return;
        }
        
        if (!ValidatorService.isUndefined(this.ajaxCallbacks[id])) {
            $.log(id + ' ajax callback is exists');
        }
        
        if (ValidatorService.isFunc(callback)) {
            if (preFetch) {
                EventService.addListener(document, id, callback);
            } else {
                this.ajaxCallbacks[id] = callback;
            }
        }
        
        return this;
    }
    
    /**
     * Add ajax fail callback
     * @param {id}        : id
     * @param {callback}  : Callback
     **/
    public static addAjaxFailCallbacks(id, callback) {
        if (!ValidatorService.isUndefined(this.ajaxCallbacks[id])) {
            $.log(id + ' ajax fail callback is exists');
        }
        
        if (ValidatorService.isFunc(callback)) {
            this.ajaxFailCallbacks[id] = callback;
        }
        
        return this;
    }
    
    /**
     * Convert file to blob by url
     * @param {url}       : Link
     * @param {callback}  : Callback
     **/
    public static getBlobDataXhr(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'blob';
        xhr.onload = function (e: ProgressEvent<EventTarget>) {
            if (this.status == 200) {
                //var blobData = URLService.createObject(this.response);
                // TODO
                //return callback(blobData);
            }
        };
        
        xhr.send();
    }
    
    /**
     * Convert file to base64 by url
     * @param {url}       : Link
     * @param {callback}  : Callback
     **/
    getBase64DataXhr(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'arraybuffer';
        xhr.onload = function (e) {
            if (this.status == 200) {
                var binaryArr = new Array(i);
                var uInt8Array = new Uint8Array(this.response);
                var i = uInt8Array.length;
                while (i--) {
                    binaryArr[i] = String.fromCharCode(uInt8Array[i]);
                }
                var data = binaryArr.join('');
                var base64 = window.btoa(data);
                return callback(base64);
            }
        };
        
        xhr.send();
    }
    
    /**
     * Get Url Aux
     * @param {url} : URL
     **/
    public static getAux(url) {
        return url.indexOf("?") == -1 ? "?" : "&";
    }
    
    /**
     * Get URL Response
     * @param {url}	      : POST URL Parameter
     * @param {content}	  : POST Content Parameter
     *
     * @return {array}
     **/
    public static getReponse(url, content) {
        var result = new Array;
        var aux = this.getAux(url);
        var xhr = (document.body, this.createXhrObject());
        
        xhr.open("POST", url + aux + "time=" + (new Date).getTime(), false);
        typeof (content == 'undefined') ? content = "" : xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(content);
        
        if (BrowserService.isSafari() || BrowserService.isOpera()) {
            var resultNodes = xhr.responseXML.firstChild.childNodes;
            
            for (var i = 0; i < resultNodes.length; i++) {
                null != resultNodes.item(i).firstChild && (result[resultNodes.item(i).nodeName] = resultNodes.item(i).firstChild.nodeValue);
            }
            
            return result;
        }
    }
    
    public static getActiveXObject () {
        return window.ActiveXObject;
    }

    public static getXMLHttp() {
        let activeXList = [];

        if (this.getActiveXObject()) {
            activeXList = [
                XMLHTTP.listMSXML2
            ];
        } else if (window.XMLHttpRequest) {
            activeXList = [
                XMLHTTP.listXMLHTTP
            ];
        }
        
        var length = activeXList.length;
        
        for (var i = 0; i < length; i++) {
            try {
                var activeX = new(this.getActiveXObject())(activeXList[i]);
                
                return function () {
                    if (activeX) {
                        return activeX;
                    } else {
                        return new(this.getActiveXObject())(activeXList[i]);
                    }
                };
            } catch (e) {}
        }
        
        throw new Error('Ajax not supported');
    }
    
    /**
     * Return XHR Object
     *
     * @return {object} : XHR Object
     **/
    public static createXhrObject() {
        var xhr;
        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            xhr = this.getXMLHttp();
        }
        
        if (ValidatorService.isObject(xhr)) {
            return xhr;
        } else {
            return false;
        }
    }
    
    /**
     * Append Javascript into Head
     * @param {type}	  : Bookmark URL
     * @param {url}	  : Bookmark URL
     * @param {title} : Bookmark Title
     **/
    public static appendJsInstance(src) {
        var head = $('head')[0];
        var script: any = document.createElement('SCRIPT');
        script.src = src;
        script.onload = function () {
            head.removeChild(script);
        }
        
        head.appendChild(script);
    }
    
    /**
     * XMLHttpRequest Call
     * @param {type}
     * @param {url}
     * @param {parameter}
     * @param {asynchronous}
     **/
    public static xhr(type = 'GET', url, parameter, asynchronous = true) {
        try {
            var xhr = this.createXhrObject();
            
            if (xhr === false) {
                return;
            }

            xhr.open(type, url, asynchronous);
            
            if (type == "POST") {
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.setRequestHeader("Content-length", parameter.length);
                xhr.setRequestHeader("Access-Control-Allow-Origin", "*.*");
            }
            
            if (!asynchronous) {
                asynchronous = true;
            }
            
            xhr.send(parameter);
            
            let isOnloadSupportedBrowser = (BrowserService.isOpera() || BrowserService.isSafari() || BrowserService.isGecko());
            
            if (isOnloadSupportedBrowser) {
                xhr.onload = function () {
                    if (xhr.readyState === 4) {
                        if (/^20\d$/.test(xhr.status)) {
                            return xhr;
                        } else {
                            alert(this.ResponseCode[xhr.status] + ' : ' + xhr.statusText);
                        }
                    }
                };
            } else {
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (/^20\d$/.test(xhr.status)) {
                            return xhr;
                        } else {
                            alert(this.ResponseCode[xhr.status] + ' : ' + xhr.statusText);
                        }
                    }
                };
            }
        } catch (e) {}
    }
    
    public static sendMessage(id, msg, url) {
        try{
            var _window = (document.getElementById(id) as any).contentWindow;
            _window.postMessage(msg, url);
        } catch(e) {
            console.log(e);
        }
    }
    
    public static ajax(type, url, params, callback, datatype, message, options = {}) {
        var userArguments = undefined;
        var request;

        try {
            var self = this;

            self.isRequestProcessing = true;

            $.extend(options, {
                type: type,
                xhrfields : {withCredentials : true},
                //CORS
                /*
                    * Header Required *
                    Access-Control-Allow-Credentials : true
                    Access-Control-Allow-Origin : http://localhost
                */
                url: url,
                data: params,
                cache: true,
                async: true,
                //dataType: "text",
                contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                success (args: any, txtStatus: any, xhr: any) {
                    args = args ?? "";

                    if (!callback) {
                        return;
                    }

                    if (typeof callback == 'function') {
                        // Dispatch Event
                        if (typeof document.getAttribute(callback) === 'function') {
                            let event = new Event(callback, args);
                            document.dispatchEvent(event);

                            return;
                        }

                        // User Custom Function
                        if (PromiseService.isSupported ()) {
                            return new Promise(function (resolve, reject) {
                                if (!args) {
                                    return;
                                }

                                args = JSONService.autoDecode(args);
                                if (typeof userArguments !== 'undefined') {
                                    args['coreUserObj'] = userArguments;
                                }

                                try {
                                    resolve(args);
                                } catch (e) {
                                    reject(new Error("Request is failed"));
                                }
                            });
                        } else {
                            if (args) {
                                args = JSONService.autoDecode(args);
                                if (typeof userArguments !== 'undefined') {
                                    args['coreUserObj'] = userArguments;
                                }

                                try {
                                    callback(args);
                                } catch (e) {}
                            }
                        }

                        return;
                    }

                    if (typeof this.ajaxCallbacks[callback] == 'undefined') {
                        return new DOMException(callback + " is not callback");
                    }

                    if ( ValidatorService.isFunc(this.ajaxCallbacks[callback]) ) {
                        try {
                            if (args) {
                                args = JSONService.autoDecode(args);
                                if (typeof userArguments !== 'undefined') {
                                    args['coreUserObj'] = userArguments;
                                }

                                try {
                                    this.ajaxCallbacks[callback].call(this, args);
                                } catch (e) {}
                            }

                            const xhrStatus = xhr.status;

                            if (self.isRequestProcessing == true) {
                                self.isRequestProcessing = false;
                            }
                        } finally {
                            if (self.isRequestProcessing == true) {
                                self.isRequestProcessing = false;
                            }
                        }
                    }
                },
                error(xhr: any) {
                    try {
                        if (ValidatorService.isFunc(this.ajaxFailCallbacks[callback])) {
                            this.ajaxFailCallbacks[callback].call(this, xhr);
                        }
                    } finally {
                        if (self.isRequestProcessing == true) {
                            self.isRequestProcessing = false;
                        }
                    }
                }
            });

            request = $.ajax(options);
        } catch (e) {
            console.log(e);
        } finally {
            request = null; 
        }
        
        self.isRequestProcessing = false;
    }
}