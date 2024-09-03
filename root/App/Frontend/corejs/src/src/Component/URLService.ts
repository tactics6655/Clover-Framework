import * as RegexRules from './../Variables/Regex';

export class URLService {

    public static createBlob (arrayBuffer: ArrayBuffer) {
        var url = this.getNative();
        var blob = new Blob([arrayBuffer]);
        
        return url.createObjectURL(blob);
    }
    
    public static getJoinChar (url) {
        return /\?/.test(url) ? "&" : "?";
    }
    
    public static createObject () {
        return (window.createObjectURL && window) || (window && window.webkitURL) || (window.URL && window.URL.revokeObjectURL);
    }
    
    public static isCOMDomain () {
        return location.hostname.match(/\.com$/);
    }
    
    public static goRoot () {
        window.location.href = "/";
    }
    
    public static getUrlVars (url) {
        var vars = [], hash;
        var hashes = url.slice(url.indexOf('?') + 1).split('&');
        
        if (hashes.indexOf(url) < 0) {
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
        }
        
        return vars;
    }
    
    public static getUrl (isHashRemove = false) {
        $.core.Base.resetWinCache();
        var url = window.location.href;
        var target = '';
        
        if (isHashRemove) {
            var hash = location.hash;
            target = url.replace(hash, '');
        } else {
            target = url;
        }
        
        return target;
    }
    
    public static getNative () {
        return window.URL || window.webkitURL || window.mozURL || window.msURL;
    }
    
    public static getObject (target) {
        var url = this.getNative();
        
        if (!url.createObjectURL) {
            url.createObjectURL = function (obj) {
                return obj;
            }
        }
        
        return url.createObjectURL(target);
    }
    
    public static revokeObject (target) {
        var url = this.getNative();
        
        return url.revokeObjectURL(target);
    }
    
    public static parseQuerystring (string) {
        var params = {};
        var string = string.split('&');
        var length = string.length;
        
        for (var i = 0; i < length; i++) {
            var split = string[i].split('=');
            params[split[0]] = decodeURIComponent(split[1]);
        }
        
        return params;
    }
    
    public static changeSrcDirectory (url, dir) {
        return url.replace(/src\="(.*)\"/g, 'src\="' + dir + "\"");
    }
    
    public static getQueryString (key) {
        var regex = new RegExp("[\?&]" + key.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]") + "=([^&#]*)");
        var url = regex.exec(this.getUrl());
        
        if (url == null) {
            return false;
        } else {
            return url[1];
        }
    }
    
    public static getParam (name, url) {
        try {
            var url = url || this.getUrl();
            var regex = new RegExp('[\=&?]' + name + '=([^&#]*)').exec(url);
            if (regex) {
                if (regex.length > 0) {
                    return regex[1];
                } else {
                    return regex;
                }
            }
        } finally {
            url = null; 
        } 
    }
    
    public static setQuery (paramName, paramValue, url) {
        var url = url || this.getUrl();
        var chr = $.core.URL.getJoinChar(url);
        var regex = new RegExp('[\=&?]' + paramName + '=([^&#]*)').exec(url);
        chr == '?' ? '' : '&';
        
        if (regex) {
            return this.setParam(paramName, paramValue, url);
        } else {
            return url + chr + paramName + '=' + paramValue;
        }
    }
    
    public static setParam (paramName, paramValue, userUrl) {
        try {
            var url = this.getUrl();
            var target = userUrl || url;
            
            var regex = new RegExp("([\?&]" + paramName + "\=)[^&#]*");
            if (paramValue) {
                var target = target.replace(regex, "$1" + paramValue);
            } else {
                var target = target.replace(regex, "");
            }
            
            return target;
        } finally {
            url = null; 
            target = null; 
            regex = null; 
        } 
    }
    
    public static getParameters (url) {
        try {
            var url = url || this.getUrl();
            return url.match(RegexRules.regURLParmas);
        } finally {
            url = null; 
        }
    }

}