import {ValidatorService} from './ValidatorService';

export class JSONService {

    public static isSupported () {
        if ("JSON" in window) {
            return true;
        }
        
        return false;
    }
    
    public static autoDecode (str) {
        if (str === null) {
            return 'null';
        }
    
        if (!this.isSupported ()) {
            return str;
        }

        if (this.isJSON(str)) {
            return this.decode(str);
        }

        const evalJson = this.SecureEvalJSON(str);
        if (!evalJson) {
            return str;
        }
        
        return evalJson;
    }
    
    public static SecureEvalJSON (str) {
        if (!str) {
            return false;
        }

        var _secure =
            str.replace( /\\["\\\/bfnrtu]/g, '@' )
            .replace( /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
            .replace( /(?:^|:|,)(?:\s*\[)+/g, '');
            
        if (/^[\],:{}\s]*$/.test(_secure)) {
            return eval('(' + str + ')');
        } 
        
        return false;
    }
    
    public static strToJSON (str) {
        return JSON.parse(eval(str)[0]);
    }
    
    public static ConverToStr (obj) {
        var result = "";
        
        if (typeof JSON != "undefined") {
            result = this.stringify(obj);
        } else {
            var arr = [];
            for (var i in obj) {
                arr.push("'" + i + "':'" + obj[i] + "'");
            }
            result = "{" + arr.join(",") + "}";
        }
        
        return result;
    }
    
    public static isJSON (m) {
        if (ValidatorService.isObject(m)) {
            try {
                m = JSON.stringify(m);
            } catch (err) {
                return false;
            }
        }
        
        if (ValidatorService.isStr(m)) {
            try {
                m = JSON.parse(m);
            } catch (err) {
                return false;
            }
        }
        
        if (!ValidatorService.isObject(m)) {
            return false;
        }
        
        return true;
    }
    
    public static stringify (object) {
        var stringify = JSON.stringify(object);
        
        if (/^[\{\[]/.test(stringify)) {
            return stringify;
        }
        
        return null;
    }
    
    public static decode (object) {
        if (this.isJSON(object)) {
            return jQuery.parseJSON(JSON.stringify(object));
        } 

        return object;
    }
    
    public static parse (object) {
        if (!this.isSupported ()) {
            return false;
        }

        if (this.isJSON(object)) {
            return JSON.parse(object);
        }
        
        return object;
    }
    
}