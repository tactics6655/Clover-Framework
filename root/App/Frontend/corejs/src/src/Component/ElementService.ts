import $ from 'jquery';

export class ElementService {

    public static setFontResizableByWheel (object) {
        $(object).bind('wheel', function(event) {
            var fontsize = parseInt($(this).css("font-size").replace("px", ""));

            if ((event.originalEvent as any).deltaY < 0) {
                if (fontsize < 250) {
                    $(this).css("font-size", fontsize + 1);
                }
            } else {
                if (fontsize > 9) {
                    $(this).css("font-size", fontsize - 1);
                }
            }

            return false;
        });
    }

    public static setMovableOnWindow (object, attributeObject) {
        var self = this;

        $(object).mousedown(function(e) {
            var eventWhich = $.core.Evt.getMouseEventWhichType(e);

            if (eventWhich != 'left') return;

            document.body.style.cursor = "grabbing";

            var offsetX = self.getElementOffsetLeft(object);
            var offsetY = self.getElementOffsetTop(object);
            
            e.preventDefault();

            var xPosition = -((offsetX - e.pageX));
            var yPosition = -((offsetY - e.pageY));

            $(document).mousemove(function(e) {
                $(attributeObject).css("top", (e.pageY - yPosition));
                $(attributeObject).css("left", (e.pageX - xPosition));
            });

            $(document).mouseup(function(e) {
                document.body.style.cursor = "default";
                $(document).unbind('mousemove');
            });
        });
    }

    public static setXYResizable (container, object, callback, widthPadding, heightPadding) {
        var moveX = false;
        var moveY = false;
        var self = this;

        if (!widthPadding) {
            widthPadding = 2;
        }
        
        if (!heightPadding) {
            heightPadding = 20;
        }
        
        $(object).mousemove(function(e) {
            var leftOffset = this.getElementOffsetLeft(container);
            var topOffset = this.getElementOffsetTop(container);
            var posX = -(leftOffset - e.pageX);
            var posY = -(topOffset - e.pageY);

            document.body.style.cursor = "default";

            var isXCursorInControllBar = (parseInt(String(posX)) > ($(object).width() - widthPadding) && parseInt(String(posX)) < $(object).width()) ? true : false;
            var isXCursorInArea = (parseInt(String(posY)) < $(object).height() - heightPadding) ? true : false;

            if (isXCursorInControllBar && isXCursorInArea) {
                document.body.style.cursor = "w-resize";
                moveX = true;
            } else {
                moveX = false;
            }
            var isYCursorInControllBar = (parseInt(String(posY)) > ($(object).height() - widthPadding) && parseInt(String(posY)) < $(object).height()) ? true : false;
            var isYCursorInArea = parseInt(String(posX)) < ($(object).width() - heightPadding) ? true : false;

            if (isYCursorInControllBar && isYCursorInArea) {
                document.body.style.cursor = "s-resize";
                moveY = true;
            } else {
                moveY = false;
            }
        });

        $(object).mousedown(function(e) {
            if (moveX) {
                $(document).mousemove(function(e) {
                    document.body.style.cursor = "w-resize";
                    var offsetX = self.getElementOffsetLeft(object);
                    var xPosition = -(offsetX - e.pageX);
                    $(object).css("width", xPosition);
                    
                    if (typeof callback === 'function') {
                        callback("x", xPosition);
                    }
                });
            } else if (moveY) {
                $(document).mousemove(function(e) {
                    document.body.style.cursor = "s-resize";
                    var offsetY = self.getElementOffsetTop(object);
                    var yPosition = -(offsetY - e.pageY);
                    $(object).css("height", yPosition);
                    
                    if (typeof callback === 'function') {
                        callback("y", yPosition);
                    }
                });
            }
        });

        $(document).mouseup(function(e) {
            document.body.style.cursor = "default";
            $(document).unbind('mousemove');
        });
    }
    
    public static setMovableOnContainer (container, object) {
        var self = this;

        $(object).mousedown(function(e) {
            var on = this;
            var eventWhich = $.core.Evt.getMouseEventWhichType(e);

            if (eventWhich != 'left') return;

            e.preventDefault();

            var width = $(container).height();
            var offsetLeft = this.getElementOffsetLeft(container);
            var mousePositionX = -(parseInt($(on).css("left")) - (-(offsetLeft - e.pageX)));


            var height = $(container).height();
            var offsetTop = this.getElementOffsetTop(container);
            var mousePositionY = -(parseInt($(on).css("top")) - -(height - (-(offsetTop - e.pageY))));

            document.body.style.cursor = "grabbing";

            var currWidth = $(on).width();
            var currHeight = $(on).height();

            $(document).mousemove(function(e) {
                var width = $(container).width();
                var offsetLeft = self.getElementOffsetLeft(container);
                var diffOffsetX = ((width - (-(offsetLeft - e.pageX))));
                var marginY = ((width >> 1) > diffOffsetX) ? (width >> 1) - diffOffsetX : -(diffOffsetX - (width >> 1));

                var leftP = ($(container).width() >> 1) + marginY - (mousePositionX);

                if (leftP < 0) {
                    leftP = 0;
                } else if (leftP + currWidth > width) {
                    leftP = width - currWidth;
                }

                var height = parseInt(String($(container).height()));
                var offset = self.getElementOffsetTop(container);
                var diffOffsetY = -((height - (-(offset - e.pageY))));

                var topP = diffOffsetY - mousePositionY;

                if (topP < 0) {
                    topP = 0;
                } else if (parseInt(String(topP)) + parseInt(String(currHeight)) > parseInt(String(height))) {
                    topP = parseInt(String(height)) - parseInt(String(currHeight));
                }

                $(on).css("left", leftP);
                $(on).css("top", topP);
            });
        });

        $(document).mouseup(function(e) {
            document.body.style.cursor = "default";
            $(document).unbind('mousemove');
        });
    }
    
    public static setResizableOnContainer (controller, container, callback) {
        var self = this;

        $(controller).mousedown(function(e) {
            var eventWhich = $.core.Evt.getMouseEventWhichType(e);

            if (eventWhich != 'left') return;

            document.body.style.cursor = "nw-resize";

            e.preventDefault();

            $(document).mousemove(function(e) {
                document.body.style.cursor = "nw-resize";

                var offsetX = self.getElementOffsetLeft(container);
                var offsetY = self.getElementOffsetTop(container);

                var xPosition = -(offsetX - e.pageX);
                var yPosition = -(offsetY - e.pageY);

                $(container).css("width", xPosition);
                $(container).css("height", yPosition);
                
                if (typeof callback === 'function') {
                    callback(xPosition, yPosition);
                }
            });

            $(document).mouseup(function(e) {
                document.body.style.cursor = "default";
                $(document).unbind('mousemove');
            });
        });
    }
    
    public static parseString (html) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, "text/html");
        
        return doc;
    }
    
    public static setStyle (data, style, callback) {
        if ($.core.Validate.isFunc(callback)) {
            $(data).css(style).queue(callback);
        } else {
            $(data).css(style);
        }
    }
    
    public static isHTMLElement (elem) {
        return (elem instanceof window.HTMLElement) ? true : false;
    }
    
    public static setMenuToggleClass (target, cls) {
        //JQMIGRATE: 'hover' pseudo-event is deprecated, use 'mouseenter mouseleave'
        $(target).on('focus mouseenter mouseover mousedown', function () {
            $(this).addClass(cls);
        }),
        $(target).on('mouseleave mouseout contextmenu mouseup',function () { 
            $(this).removeClass(cls);
        });
    }
    
    public static insertDOMParentBefore (target, dom, id) {
        var elem = document.getElementById(target);
        
        if (elem) {
            var _dom;

            if (!$.core.Validate.isObject(dom)) {
                _dom = document.createElement(dom);
            } else {
                _dom = document.getElementById(dom);
            }
            
            elem.parentNode.insertBefore(_dom, elem);
            _dom.setAttribute("id", id);
        }
    }
    
    public static getElementOffsetLeft (element) {
        var element =  document.querySelector(element);
        var bodyRect = document.body.getBoundingClientRect();
        var elemRect = element.getBoundingClientRect();
        var offset   = elemRect.left - bodyRect.left;
        
        return offset;
    }
    
    public static getElementOffsetTop (element) {
        var element =  document.querySelector(element);
        var bodyRect = document.body.getBoundingClientRect();
        var elemRect = element.getBoundingClientRect();
        var offset   = elemRect.top - bodyRect.top;
        
        return offset;
    }
    
    public static getElementOffsetBottom (element) {
        var element =  document.querySelector(element);
        var bodyRect = document.body.getBoundingClientRect();
        var elemRect = element.getBoundingClientRect();
        var offset   = elemRect.bottom - bodyRect.bottom;
        
        return offset;
    }
    
    public static getElementsByClassNameCompatible (classes) {
        if (document.getElementsByClassName) {
            return document.getElementsByClassName(classes);
        }
        
        var i;
        var classArr = new Array();
        var regex: RegExp = new RegExp('^| ' + classes + ' |$');
        var elem = document.body.getElementsByTagName("*");
        var len = elem.length;
        for (i=0; i < len; i++) {
            var className = elem[i].className;
            
            if (className && regex.test(className)) {
                classArr.push(elem[i]);
            }
        }
        
        return classArr;
    }
    
    public static makeStruct (item, duplicate, names) {
        var item = item.split(duplicate);
        var count = item.length;
        
        function constructor() {
            for (var i = 0; i < count; i++) {
                this[names[i]] = arguments[i];
            }
        }
        
        return constructor;
    }
    
    public static getBodyLastChild () {
        return document.body.lastChild;
    }
    
    public static isBodyRTL () {
        return window.getComputedStyle(document.body).direction === 'rtl';
    }
    
    public static isCorrectFunctionName (func) {
        var func: any = /^\s*function\s*([A-Za-z0-9_$]*)/;
        return func.exec(func);
    }
    
    public static fontTest (beforeweight, beforefamily, afterweight, afterfamily, id) {
        var before;
        var after;

        before.family = (typeof(beforefamily) != 'undefined')? beforefamily: 'serif';
        before.weight = (typeof(beforeweight) != 'undefined')? beforeweight: '300';
        after.family = (typeof(afterfamily) != 'undefined')? afterfamily: 'serif';
        after.weight = (typeof(afterweight) != 'undefined')? afterweight: '300';	
        
        $('body').prepend('<p id="' + id + '" style="font-family:' + before.family + ';font-size:72px;font-weight:' + before.weight + ';left:-9999px;top:-9999px;position:absolute;visibility:hidden;width:auto;height:auto;">ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./\!</p>');
        
        var beforeWidth = $('p#' + id).width();
        var beforeHeight = $('p#' + id).height();
        
        $('p#jQuery-Font-Test').css({
            'font-family': (after.family + ',' + before.family),
            'font-weight': after.weight
        });
        
        var afterWidth = $('p#' + id).width();
        var afterHeight = $('p#' + id).height();
        
        $('p#' + id).remove();
        
        return (((afterHeight != beforeHeight) || (afterWidth != beforeWidth)) ? true: false);
    }
    
    public static getChildsText (node) {
        function getStrings(node, arr) {
            if (node.nodeType == 3) { /* Node.TEXT_NODE */
                arr.push(node.data);
            } else if (node.nodeType == 1) { /* Node.ELEMENT_NODE */
                for (var m = node.firstChild; m != null; m = m.nextSibling) {
                    getStrings(m, arr);
                }
            }
        }
        
        var arr = [];
        getStrings(node, arr);
        return arr.join("");
    }
    
    public static selectTextArea (id) {
        (document.getElementById(id) as any).select();
    }
    
    public static getPointerX (evt) {
        if (!evt) {
            evt = window.event;
        }
        
        try{
            return evt.pageX || (evt.clientX + this.getScrollLeft());
        } catch (e) {
            console.log(e);
        }
    }
    
    public static getFunction (name, params) {
        var query = name + "(" + params + ");";
        return eval(query);
    }
    
    public static getPointerY (evt) {
        if (!evt) {
            evt = window.event;
        }
        
        return evt.pageY || (evt.clientY + this.getScrollLeft());
    }
    
    public static getScrollX () {
        if (self.pageXOffset) {
            return self.pageXOffset;
        } else if (document.documentElement && document.documentElement.scrollTop) {
            return document.documentElement.scrollLeft;
        } else if (document.body && document.body.scrollLeft) {
            return document.body.scrollLeft;
        }
    }
    
    public static getScrollY () {
        if (self.pageYOffset) {
            return self.pageYOffset;
        } else if (document.documentElement && document.documentElement.scrollTop) {
            return document.documentElement.scrollTop;
        } else if (document.body && document.body.scrollTop) {
            return document.body.scrollTop;
        }
    }
    
    public static toggleLayer (id) {
        try {
            var obj = document.getElementById(id);
            
            obj.style.display = "none" == obj.style.display ? "block" : "none";
        } catch (e) {
            console.log(e);
        }
        
        return true;
    }
    
    public static findObjectFromNodeName (object, name) {
        for (var obj = object; obj; obj = obj.parentNode) {
            if (name == obj.nodeName) {
                return obj;
            }
        }
        
        return null;
    }
    
    public static findForm (object) {
        return this.findObjectFromNodeName(object, 'form');
    }
    
    public static trimAllTags (form) {
        try {
            var length = form.elements.length;
            for (var i = 0; i < length; i++) {
                //form.elements[i].tagName.toLowerCase();
                //form.elements[i].type
                /*TODO*/
            }
            return !0
        } catch (e) {
            console.log(e);
        }
    }
    
    public static getPosition (id, type) {
        var elem = this.getById(id);
        
        if (!elem) {
            return 0;
        }
        
        var offset = 0;
        while (elem) {
            if (type=='left') {
                if (!$.core.Validate.isUndefined(elem.offsetLeft)) {
                    offset += elem.offsetLeft;
                }
            } else {
                if (!$.core.Validate.isUndefined(elem.offsetTop)) {
                    offset += elem.offsetTop;
                }
            }
            elem = !$.core.Validate.isUndefined(elem.offsetParent) ? elem.offsetParent : null
        }
        return offset;
    }
    
    public static getParent (id, node) {
        var elem = this.getById(id);
        
        if (!elem) {
            return null;
        }
        
        var p = null;
        
        if (!node && !$.core.Validate.isUndefined(elem.offsetParent)) {
            p = elem.offsetParent;
        } else if (!$.core.Validate.isUndefined(elem.parentNode)) {
            p = elem.parentNode;
        } else if (!$.core.Validate.isUndefined(elem.parentElement)) {
            p = elem.parentElement;
        }
        
        return p;
    }
    
    public static generateTooltip (elem, _class, body) {
        var _tooltip = [];
        
        if (!$(elem).length) {
            return;
        }
        
        $(elem).each(function (i, item) {
            var _tooltipItem = $('<div class="' + _class + '" data-index="' + i + '"></div>').appendTo(body);
            $(item).attr('data-index', i);
            _tooltip.push(_tooltipItem);
        });
        
        return _tooltip;
    }

    public static removeIEObject (id) {
        var obj = this.getById(id);
        if (obj) {
            for (var i in obj) {
                if (typeof obj[i] == "function") {
                    obj[i] = null;
                }
            }
            
            obj.parentNode.removeChild(obj);
        }
    }
    
    public static getAttr (element, properties) {
        return element.getAttribute(properties);
    }
    
    public static getStyleText (element) {
        var style = this.getAttr(element, "style");
        if (!style) {
            style = element.style;
        }
        
        if (typeof(style) === "object") {
            return style;
        }
        
        return null;
    }
    
    public static addStyle (id, html) {
        var style = document.createElement("style");
        style.type = "text/css";
        style.innerHTML = html;
        style.id = id;
        
        document.head.appendChild(style);
    }
    
    public static getStyle (element, properties) {
        if (element.currentStyle) {
            return element.currentStyle[properties];
        } else if (window.getComputedStyle) {
            return document.defaultView.getComputedStyle(element, null).getPropertyValue(properties);
        }
    }
    
    public static preloadImage (src) {
        var preloadIMage = new Image;
        preloadIMage.src = src;
    }
    
    public static getMatchesSelector (elem) {
        return 
            elem.prototype.matchesSelector || 
            elem.prototype.mozMatchesSelector || 
            elem.prototype.msMatchesSelector || 
            elem.prototype.oMatchesSelector || 
            elem.prototype.webkitMatchesSelector;
    }
    
    public static setAllCheckboxToggle (elem, target) {
        var isChecked = elem.checked;
        
        if (target) {
            var checker = [];
            
            if (!target.length) {
                checker.push(target);
            } else if (target.length > 0) {
                for (var i=0; i<target.length; i++) {
                    checker.push(target[i]);
                }
            }
            
            for (var i=0; i<checker.length; i++) {
                var currentItem = checker[i];
                
                if (isChecked && !currentItem.checked) {
                    currentItem.checked = true;
                } else if (!isChecked && currentItem.checked) {
                    currentItem.checked = false;
                }
            }
        }
    }
    
    public static setCheckboxToggle (item) {
        var obj = $('input[name=' + item + ']:checkbox');
        
        obj.each(function () {
            $(this).attr('checked', $(this).attr('checked'))
        });
    }
    
    public static getSelectedText () {
        if (window.getSelection) {
            return window.getSelection();
        } else if (document.getSelection) {
            return document.getSelection();
        } else {
            var selection = document.selection && document.selection.createRange();
            
            if (!selection) {
                return false;
            }
            
            if (selection.text) {
                return selection.text;
            }
            
            return false;
        }
    }
    
    public static getDoc (elem) {
        return (
            elem.contentWindow || 
            elem.contentDocument
        ).document;
    }
    
    public static isIncludedVideo (Id) {
        var obj = document.getElementById(Id);
        
        if (obj && /object|embed/i.test(obj.nodeName)) {
            return true;
        }
        
        return false;
    }

    public static setHeaderStyle (style) {
        var head = document.getElementsByTagName('head')[0];
        
        if (style && head) {
            var styles = this.create('style');
            styles.setAttribute('type', 'text/css');
            
            if (styles.styleSheet) {
                try {
                    styles.styleSheet.cssText = style;
                } catch(e) {
                    styles.nodeText = style;
                }
            } else {
                styles = document.createTextNode(style);
                styles.appendChild(styles);
            }
            
            head.appendChild(styles);
        }
    }
    
    public static setJS (url, callback) {
        try {
            var head = document.getElementsByTagName('head')[0];
            var script: any = this.create('script');
            var scripts: any = head.getElementsByTagName('script');
            
            script.type = 'text/javascript';
            script.src = url;
            script.async = true;
            
            for (var i = 0; i < scripts.length; i++) {
                if (scripts[i].href === script.src) {
                    return false;
                }
            }
            
            head.appendChild(script) || document.body.appendChild(script);
            
            script.onreadystatechange = function (event) {
                if (/complete|loaded/.test(script.readyState)) {
                    if ($.core.Validate.isFunc(callback)) {
                        try {
                            callback.call(this, script);
                        } catch (e) {
                            console.log(e)
                        }
                    }
                }
            }
            
            script.onload = function (event) {
                if ($.core.Validate.isFunc(callback)) {
                    try {
                        callback.call(this, script);
                    } catch (e) {
                        console.log(e)
                    }
                }
            }
        } catch (e) {
            console.log(e);
        }
    }
    
    public static setCSS (url, callback) {
        try {
            var head = document.getElementsByTagName('head')[0];
            var link = this.create('link');
            var links = head.getElementsByTagName('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = url;
            
            for (var i = 0; i < links.length; i++) {
                if (links[i].href === link.href) {
                    return false;
                }
            }
            
            head.appendChild(link) || document.body.appendChild(link);
            
            /*script.onreadystatechange = function () {
                if (/complete|loaded/.test(script.readyState)) {
                    if (callback != null && callback != undefined) {
                        callback();
                    }
                }
            }
            
            script.onload = function () {
                if (callback != null && callback != undefined) {
                    callback();
                }
            }*/
        } catch (e) {
            console.log(e);
        }
    }
    
    public static setChecked (element) {
        $(element).prop('checked', true);
    }
    
    public static unsetChecked (element) {
        $(element).prop('checked', false);
    }
    
    public static isChecked (element) {
        return $(element).is(':checked');
    }
    
    public static hasProperty (obj, prop) {
        return Object.prototype.hasOwnProperty.call(obj, prop);
    }
    
    public static setProperty (obj, prop, descriptor) {
        if (descriptor) {
            Object.defineProperty(obj, prop, descriptor);
        } else {
            Object.defineProperties(obj, prop);
        }
    }
    
    public static getInnerWinSize () {
        return {
            width: this.getInnerWidth(),
            height: this.getInnerHeight()
        };
    }
    
    public static getProperty (element, prop) {
        return Object.getOwnPropertyDescriptor(element, prop);
    }
    
    public static generateCode (target, type, content) {
        var src = "";
        
        switch (type) {
        case "a":
            src = '<a href="' + target + '">' + content + '</a>';
            break;
        case "button":
            src = '<input type="button" value="' + content + '">' + target + '</input>';
            break;
        case "range":
            src = '<input type="range" value="' + content + '">' + target + '</input>';
            break;
        case "text":
            src = '<input type="text" value="' + content + '">' + target + '</input>';
            break;
        case "textarea":
            src = '<input type="textarea" value="' + content + '">' + target + '</input>';
            break;
        case "audio":
            src = '<audio src="' + target + '" controls></audio>';
            break;
        case "img":
            src = '<img src="' + target + '"></img>';
            break;
        case "embed":
            src = '<embed src="' + target + '"></embed>';
            break;
        case "video":
            src = '<video src="' + target + '" controls></video>';
            break;
        }
        
        return src;
    }
    
    public static isWheelExists () {
        document.onmousewheel !== undefined ?  'mousewheel' : 'DOMMouseScroll';
        
        if (document.onmousewheel !== undefined) {
            return true;
        }
        
        var hasWheel = ('onwheel' in document.createElement('div'));
        return hasWheel ? true : false;
    }
    
    public static generateMultimediaCode (file, type) {
        let src = "";
        
        switch (type) {
            case "audio":
                src = '<audio src="' + file + '" controls></audio>';
                break;
            case "img":
                src = '<img src="' + file + '"></img>';
                break;
            case "embed":
                src = '<embed src="' + file + '"></embed>';
                break;
            case "video":
                src = '<video src="' + file + '" controls></video>';
                break;
        }
        
        return src;
    }
    
    public static getRoot () {
        return document.documentElement;
    }
    
    public static clone ($element) {
        return $element.clone();
    }
    
    public static getSpecificType (type) {
        return $(document.activeElement).is(type);
    }
    
    public static getParents (el) {
        var parents = [];
        var p = el.parentNode;
        while (p !== document) {
            var o = p;
            parents.push(o);
            p = o.parentNode;
        }
        
        return parents;
    }
    
    public static addClass (element, className) {
        if (element.classList) {
            element.classList.add(className);
        } else {
            element.className += ' ' + className;
        }
    }
    
    public static hasClass (element, className) {
        var name = element.className;
        var reg = new RegExp(className, 'g');
        return reg.test(name);
    }
    
    public static removeClass (element, className) {
        if (element.classList) {
            element.classList.remove(className);
        } else {
            var name = element.className;
            var reg = new RegExp('[\\s\\u00A0\\u3000]*' + className + '[\\s\\u00A0\\u3000]*', 'g');
            element.className = name.replace(reg, ' ').replace(/[\s\u00A0\u3000]*$/, '');
        }
    }
    
    public static hasClasses (elem, className) {
        return elem.className.split(' ').indexOf(className) > -1;
    }
    
    /**
     * Get Text Node
     * @param {node} : element name
     **/
    public static getTextNode (elem) {
        var textNode = document.createTextNode(elem);
        return textNode;
    }
    
    /**
     * Get Object Rectangle Size
     * @param {scope} : element
     **/
    public static getRectangle (scope) {
        return {
            'offset_left': $(scope).offset().left || 0,
            'offset_top': $(scope).offset().top || 0,
            'position_left': $(scope).position().left || 0,
            'position_top': $(scope).position().top || 0,
            'width': $(scope).width() || 0,
            'height': $(scope).height() || 0
        };
    }
    
    public static getBasenamebyID (id) {
        return (document.getElementById(id) as any).value.split(/(\\|\/)/g).pop();
    }
    
    public static appendElement (id, type, callback) {
        var ul = document.getElementById(id);
        var li = document.createElement(type);
        
        if ($.core.Validate.isFunc(callback)) {
            callback(li);
        }
        
        ul.appendChild(li);
    }
    
    public static appendText (id, txt, callback) {
        this.appendElement(id, 'a', function (attr) {
            if ($.core.Validate.isFunc(callback)) {
                callback(attr);
            }
            
            attr.appendChild(document.createTextNode(txt));
        });
    }
    
    public static appendDiv (dom, cls) {
        if ($.core.Validate.isObject(dom)) {
            dom = dom.id || dom.className;
        }
        
        if (dom.match(/^#(.*)/)) {
            dom = this.getById(dom);
        } else if (dom.match(/^\.(.*)$/)) {
            dom = dom.replace(/(^.)/i, "");
            dom = this.getByClass(document, dom, dom);
        } else if (!$.core.Validate.isObject(dom)) {
            dom = this.getById(dom);
        }
        
        let domSize = $(dom).length;
        
        if (domSize > 0) {
            var append = this.create('div');
            append.className = cls;
            dom.appendChild(append);
            return append;
        } else {
            return false;
        }
    }
    
    public static appendDivId (dom, cls) {
        if ($.core.Validate.isObject(dom)) {
            dom = dom.id || dom.className;
        }
        
        if (dom.match(/^#(.*)/)) {
            dom = this.getById(dom);
        } else if (dom.match(/^\.(.*)$/)) {
            dom = dom.replace(/(^.)/i, "");
            dom = this.getByClass(document, dom, dom);
        } else if (!$.core.Validate.isObject(dom)) {
            dom = this.getById(dom);
        }
        
        let domSize = $(dom).length;
        
        if (domSize > 0) {
            var append = this.create('div');
            append.setAttribute("id", cls);
            dom.appendChild(append);
            return append;
        } else {
            return false;
        }
    }
    
    public static addDivOnBody (cls) {
        var docFrag = this.createFragment();
        var container = this.create('div');
        
        container.className = cls;
        docFrag.appendChild(container);
        document.body.appendChild(docFrag);
        
        return container;
    }
    
    public static getObjectType (target) {
        try {
            switch (typeof target) {
            case "undefined":
                return null;
            case "object":
                return target;
            default:
                return document.getElementById(target)
            }
        } catch (e) {
            return null;
        }
    }
    
    public static getWithNumberKeyCode (keyCode) {
        if (
            (keyCode > 47 && keyCode < 58) || 
            (keyCode > 95 && keyCode < 106) || 
            (keyCode == 46 || 
                keyCode == 39 || 
                keyCode == 37 || 
                keyCode == 9 || 
                keyCode == 8)
        ) {
            return true;
        }
        
        return false;
    }
    
    public static getKeyCodeType (keyCode: any) {
        //48 ~ 57
        if (keyCode > 47 && keyCode < 58) {
            return 'NUM';
        //65 ~ 90
        } else if (keyCode > 64 && keyCode < 91) {
            return 'ALPHABET_LOWER';
        //96 ~ 105
        } else if (keyCode > 95 && keyCode < 106) {
            return 'KEYPAD_NUM';
        //112 ~ 123
        } else if (keyCode > 111 && keyCode < 124) {
            return 'FNKEY';
        }
    }
    
    public static forceChange (element, content) {
        $(element).text(content);
        if ($(element).text() == content) {
            return true;
        }
        
        $(element).html(content);
        if ($(element).html() == content) {
            return true;
        }
        
        $(element).val(content);
        if ($(element).val() == content) {
            return true;
        }
        
        return false;
    }
    
    public static removeAttribute (element, attributes) {
        for (var attr in attributes) {
            if (attributes.hasOwnProperty(attr)) {
                continue;
            }
            
            element.removeAttribute(attr, attributes[attr]);
        }
    }
    
    public static setAttribute (element, attributes) {
        for (var attr in attributes) {
            if (!attributes.hasOwnProperty(attr)) {
                continue;
            }
            
            element.setAttribute(attr, attributes[attr]);
        }
    }
    
    public static getInnerWidth () {
        if (document.compatMode == 'CSS1Compat' && document.documentElement && document.documentElement.clientWidth) {
            return document.documentElement.clientWidth;
        } else if (document.documentElement && document.documentElement.clientWidth) {
            return document.documentElement.clientWidth;
        } else if (document.body && document.body.clientWidth) {
            return document.body.clientWidth;
        } else if (typeof (window.innerWidth) != "undefined") {
            return window.innerWidth;
        } else {
            return screen.width;
        }
    }
    
    public static getInnerHeight () {
        if (document.documentElement && document.documentElement.clientHeight) {
            return document.documentElement.clientHeight;
        } else if (document.body && document.body.clientHeight) {
            return document.body.clientHeight;
        } else if (typeof (window.innerHeight) != "undefined") {
            return window.innerHeight;
        } else {
            return screen.height;
        }
    }
    
    public static getScrollTop () {
        if (typeof (window.pageYOffset) != "undefined") {
            return window.pageYOffset;
        } else if (document.documentElement) {
            return document.documentElement.scrollTop;
        } else if (document.body) {
            return document.body.scrollTop;
        }
    }
    
    public static getScrollLeft () {
        if (document.documentElement) {
            return document.documentElement.scrollLeft;
        } else if (document.body) {
            return document.body.scrollLeft;
        }
    }
    
    public static getWidth (element) {
        if ($.core.Validate.isObject(element)) {
            return $(element).width();
        } else {
            return 0;
        }
    }
    
    public static getHeight (element) {
        if ($.core.Validate.isObject(element)) {
            return $(element).height();
        } else {
            return 0;
        }
    }
    
    public static getOffset (element) {
        var offset = {
            "left": element.offsetLeft,
            "top": element.offsetTop
        };
        
        var o;
        
        while (o = element.offsetParent) {
            offset.left += o.offsetLeft;
            offset.top += o.offsetTop;
        }
        
        return offset;
    }
    
    public static getImgPosition (element) {
        var _elem = element || document;
        
        if (!_elem) {
            return {
                "realOffset": 0,
                "correctOffset": 0
            };
        }
        
        var defScreenHeight = 0;
        var offset = this.getOffset(_elem);
        var real = parseInt(offset.top, 10);
        var currect = parseInt(String(real - defScreenHeight * 0.1));
        
        return {
            "realOffset": real,
            "correctOffset": currect
        };
    }
    
    public static getLeft (element) {
        var _elem = element || document;
        
        return $(_elem).offset().left;
    }
    
    public static getTop (element) {
        var _elem = element || document;
        
        return $(_elem).offset().top;
    }
    
    public static getByTag (tag, elementNode) {
        var _element: any = elementNode || document;
        
        if (typeof (tag) != 'string') {
            return tag;
        }
        
        var elementNode = null;
        
        try {
            elementNode = _element.getElementsByTagName(tag);
        } catch (e) {
            console.log(e);
        }
        
        return elementNode;
    }
    
    public static getByName (id, elementNode) {
        var _element = elementNode || document;
        
        if (typeof (id) != 'string') {
            return id;
        }
        
        var elementNode = null;
        
        try {
            elementNode = _element.getElementsByName(id);
        } catch (e) {
            console.log(e);
        }
        
        return elementNode;
    }
    
    public static getById (id, elementNode = null) {
        var _element = elementNode || document;
        
        if (typeof (id) != 'string') {
            return null;
        }
        
        var elementNode = null;
        
        try {
            elementNode = _element.getElementById(id);
        } catch (e) {
            console.log(e);
        }
        
        return elementNode;
    }
    
    public static getinnerHTML (elementNode) {
        var elem = elementNode || document;
        
        return elem.innerHTML;
    }
    
    public static getByClass (elem, tagName, className) {
        var classObject = this.getByClasses(tagName);
        var length = classObject.length;
        
        for (var i = 0; i < length; i++) {
            if ((new RegExp(className)).test(classObject[i].className)) {
                return classObject[i];
            }
        }
        
        return null;
    }
    
    public static getByClasses (className, elementNode = null) {
        var _element = elementNode || document;
        
        return _element.getElementsByClassName(className);
    }
    
    public static getClassCount (className) {
        return this.getByClasses(className).length;
    }
    
    public static setAllInnerHTMLbyClass (className, htmlContent) {
        var _target = this.getByClasses(className);
        var _length = this.getClassCount(className);
        
        for (var i = 0; i < _length; i++) {
            _target[i].innerHTML = htmlContent;
        }
    }
    
    public static setIframeToggleMute (id) {
        var browser = document.querySelector(id);
        var request = browser.getMuted();
        
        request.onsuccess = function () {
            if (request.result) {
                browser.unmute();
            } else {
                browser.mute();
            }
        }
    }
    
    public static setinnerHTML (elementNode, htmlContent) {
        elementNode.innerHTML = htmlContent;
    }
    
    public static isSelectedType (type) {
        if ($(this.getSelected()).is(type)) {
            return true;
        }
        
        return false;
    }
    
    public static getSelected () {
        return document.activeElement;
    }
    
    public static createEvent (eventNode) {
        var cEvent;
        if (document.createEvent != null) {
            cEvent = document.createEvent(eventNode);
        } else if (document.createEventObject != null) {
            cEvent = document.createEventObject(eventNode);
        }
        
        return cEvent;
    }
    
    public static create (elementNode: any) {
        return document.createElement(elementNode);
    }
    
    public static createNS (attribute, tags) {
        if (attribute) {
            if (tags) {
                return document.createElementNS(attribute, tags);
            } else {
                return document.createElementNS(attribute, '');
            }
        }
        
        return document.createElementNS || document.createElementNS('http://www.w3.org/2000/svg', 'svg').createSVGRect;
    }
    
    public static createFragment () {
        return document.createDocumentFragment();
    }
    
    public static setStyles (elementNode, properties) {
        var properties = properties || {};
        
        for (var property in properties) {
            elementNode.style[property] = properties[property];
        }
    }
}