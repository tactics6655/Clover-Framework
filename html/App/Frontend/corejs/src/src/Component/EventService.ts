import {ElementService} from './ElementService';
import {ValidatorService} from './ValidatorService';
import {BrowserService} from './BrowserService';

export class EventService {

    public static getHiddenDocumentType () {
        let hidden;
        
        if (typeof document.hidden !== "undefined") { // Opera 12.10 and Firefox 18 and later support 
            hidden = "hidden";
        } else if (typeof document.msHidden !== "undefined") {
            hidden = "msHidden";
        } else if (typeof document.webkitHidden !== "undefined") {
            hidden = "webkitHidden";
        }
        
        return hidden;
    }

    public static getHiddenDocumentVisibilityChangeType () {
        var visibilityChange;
        
        if (typeof document.hidden !== "undefined") { // Opera 12.10 and Firefox 18 and later support 
            visibilityChange = "visibilitychange";
        } else if (typeof document.msHidden !== "undefined") {
            visibilityChange = "msvisibilitychange";
        } else if (typeof document.webkitHidden !== "undefined") {
            visibilityChange = "webkitvisibilitychange";
        }
        
        return visibilityChange;
    }

    public static setDocumentHiddenListener (callback) {
        var visibilityChange = this.getHiddenDocumentVisibilityChangeType();
        
        this.addListener(document, visibilityChange, callback);
    }

    public static isDocumentHidden () {
        var hidden = this.getHiddenDocumentType();
        
        if (document[hidden]) {
            return true;
        }
        
        return false;
    }

    setResizeHorizontalEvent(minimumSize, target, handler) {
        if (!minimumSize) minimumSize = 0;
        
        $(handler).mousedown(function(e) {
            var eventWhich = this.getMouseEventWhichType(e);
            
            if (eventWhich != 'left') return;
            
            document.body.style.cursor = "n-resize";
            e.preventDefault();
            
            $(document).mousemove(function(e) {
                var offset = ElementService.getElementOffsetTop(target);
                var resizedValue = e.pageY - offset;
                if (resizedValue < minimumSize) return;
                $(target).css("height", resizedValue);
            });
        });
        
        $(document).mouseup(function(e){
            document.body.style.cursor = 'default';
            $(document).unbind('mousemove');
        });
    }

    public static getCallerScriptPath () {
        try {
            throw new Error();
        } catch(e: any) {
            var stack = e.stack.split('\n');
            var index = 0;
            for (var i in stack) {
                if (!stack[i].match(/http[s]?:\/\//)) continue;
                index = Number(i) + 2;
                break;
            }
        }
        
        return stack;
    }

    public static getMouseEventWhichType (e) {
        var which;
        
        switch(e.which) {
            case 1:
                which = "left";
                break;
            case 2:
                which = "middle";
                break;
            case 3:
                which = "right";
                break;
            default:
                which = "none";
                break;
        }
        
        return which;
    }

    public static onBackSpaceClick (callback) {
        function keyDownDetector(e) {
            if (e.target.nodeName != "INPUT" && e.target.nodeName != "TEXTAREA") {    
                if (e.keyCode === 8) {
                    callback(e);
                }
            }
        }
        
        try {
            this.addListener(document, 'keydown', function (e) {
                keyDownDetector(e)
            });
        } catch (Exception) {
            $(document).keydown(function (e) {
                keyDownDetector(e);
            });
        }
    }

    public static addClickEvent (callback, chkRegEx) {
        var links = document.body.getElementsByTagName("A");
        var length = links.length;
        for (var i = 0; i < length; i++) {
            var link:any = links[i];
            var href = null;
            
            try {
                href = link.href;
            } catch (e) {
                console.log(e);
            }
            
            if (!href) {
                continue;
            } else if (chkRegEx) {
                if (href.match(chkRegEx)) continue;
            }
            
            if (link.attachEvent) {
                link.attachEvent("onclick", this.onClickHandler(event, callback));
            } else { 
                link.addEventListener('click', this.onClickHandler(event, callback), false);
            }
        }
    }

    public static onClickHandler (event, callback) {
        var link = this.getEventSource(event);
        
        while (link && link.tagName != "A") {
            link = link.parentNode;
        }
        
        if (!link) {
            return;
        }
        
        callback(window, {'href':link.href,'title':link.title});
    }

    public static getEventSource (event) {
        try {
            var obj = event.srcElement ? event.srcElement : event.target;
            return obj;
        } finally {
            obj = null;
        }
    }

    public static iframeOnClick (id, callback) {
        (document.getElementById(id) as any).contentWindow.document.body.onclick = function () {
            callback();
        };
    }

    public static normalize (event) {
        var eventNormalize: any = {};
        
        eventNormalize.target = (event.target ? event.target : event.srcElement);
        eventNormalize.which = (event.which ? event.which : event.button);
        
        return eventNormalize;
    }

    public static isSupported (eventName, element) {
        eventName = 'on' + eventName;
        var isSupported = eventName in element;
        
        return isSupported;
    }

    public static disableDraggable (element) {
        element.draggable = false;
        
        element.onmousedown = function (event) {
            event.preventDefault();
            return false;
        };
    }

    public static prefixMouseEvent (pointerEvent) {
        return window.MSPointerEvent ? 'MSPointer' + pointerEvent.charAt(7).toUpperCase() + pointerEvent.substr(8) : pointerEvent;
    }

    public static when (element, type, fn, condition) {
        var func = function () {
            if (condition()) {
                element.off(type, func);
                fn.apply(this, arguments);
            }
        };
        
        element.on(type, func);
    }

    public static addNN4EventListener (element, event, listener) {
        if (!element.NN4Event) element.NN4Event = {};
        if (!element.NN4Event[event]) element.NN4Event[event] = [];
        
        var event_arr = element.NN4Event[event];
        event_arr[event_arr.length] = listener;
    }

    public static getNN4Event (element, event) {
        if (element.NN4Event && element.NN4Event[event]) {
            var event_arr = element.NN4Event[event];
            var length = event_arr.length;
            
            for (var i = 0; i < length; i++) {
                event_arr[i]();
            }
        }
    }

    public static addListener (element, event, listener) {
        if (ValidatorService.isNull(element) || ValidatorService.isUndefined(element) || !ValidatorService.isObject(element)) {
            return;
        }
        
        // Support: IE 11
        // Standards-based browsers support DOMContentLoaded
        if (element.addEventListener) {
            element.addEventListener(event, listener, false);
        // If IE event model is used
        // Support: IE 9 - 10 only
        } else if (element.attachEvent) {
            element.attachEvent('on' + event, listener || function () {
                event.call(element);
            });
        } else {
            this.addNN4EventListener(element, event, listener);
            element['on' + event] = function () {
                this.getNN4Event(element, event)
            };
        }
    }

    public static removeEvent (element, eventType, fn) {
        if (element.addEventListener) {
            return element.removeEventListener(eventType, fn, false);
        } else if (element.detachEvent) {
            return element.detachEvent("on" + eventType, fn);
        }
    }

    public static preventEvent (evt) {
        var evt = evt || window.event;
        
        if (evt.preventDefault) {
            evt.preventDefault();
        } else {
            evt.returnValue = false;
            evt.cancelBubble = true;
        }
    }

    public static onReady (doc, callback) {
        var fired = false;
        
        this.addListener(doc, 'DOMContentLoaded', function () {
            if (fired) {
                return;
            }
            
            fired = true;
            callback();
        });
        
        this.addListener(doc, 'readystatechange', function () {
            if (fired) {
                return;
            }
            
            if (doc.readyState === 'complete') {
                fired = true;
                callback();
            }
        });
    }

    addNodeEvent = document.addEventListener ? 
    (function (node, type, handler) {
        node.addEventListener(type, handler, false);
    }) : 
    (function (node, type, handler) {
        node.attachEvent('on' + type, handler);
    });

    removeNodeEvent = document.removeEventListener ? 
        (function (node, type, handler) {
            node.removeEventListener(type, handler, false);
        }) : 
        (function (node, type, handler) {
            node.detachEvent('on' + type, handler);
        });

    public static trigger (elements, event, ignore) {
        if (ignore === true) {
            $(elements).triggerHandler(event);
        } else {
            $(elements).trigger(event);
        }
    }

    public static loopCallback (start, end, callback) {
        for (var i = start; i < end; i++) {
            callback();
        }
    }

    public static getShortCutKeyType (event) {
        if (BrowserService.isIE()) {
            event = window.event;
            event.target = event.srcElement;
        }
        
        if (event.altKey || event.ctrlKey || event.metaKey) {
            return;
        }
        
        switch (event.target.nodeName) {
            case "INPUT":
            case "SELECT":
            case "TEXTAREA":
                return;
        }
        
        switch (event.keyCode) {
            //return keydownKeycode[event.keyCode];
        }

    }

}