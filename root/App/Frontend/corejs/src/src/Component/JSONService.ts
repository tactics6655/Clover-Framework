import {ValidatorService} from './ValidatorService';

export class JSONService {

    public static isSupported () {
        if ("JSON" in window) {
            return true;
        }
        
        return false;
    }
    
    public static autoDecode (value: any) {
        if (value === null) {
            return 'null';
        }
    
        if (!this.isSupported ()) {
            return value;
        }

        if (this.isJSON(value)) {
            return this.decode(value);
        }

        const evalJson = this.secureEval(value);
        
        if (!evalJson) {
            return value;
        }
        
        return evalJson;
    }
    
    public static secureEval (value: string) {
        if (!value) {
            return false;
        }

        const secured =
            value.replace( /\\["\\\/bfnrtu]/g, '@' )
            .replace( /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
            .replace( /(?:^|:|,)(?:\s*\[)+/g, '');
            
        if (/^[\],:{}\s]*$/.test(secured)) {
            return eval('(' + value + ')');
        } 
        
        return false;
    }
    
    public static fromString (str) {
        return JSON.parse(eval(str)[0]);
    }
    
    public static toString (obj) {
        var result = "";
        
        if (typeof JSON != "undefined") {
            result = this.stringify(obj);
        } else {
            let pair = [];

            for (var i in obj) {
                pair.push("'" + i + "':'" + obj[i] + "'");
            }

            result = "{" + pair.join(",") + "}";
        }
        
        return result;
    }
    
    public static isJSON (value: any) {
        if (ValidatorService.isObject(value)) {
            try {
                value = JSON.stringify(value);
            } catch (err) {
                return false;
            }
        }
        
        if (ValidatorService.isStr(value)) {
            try {
                value = JSON.parse(value);
            } catch (err) {
                return false;
            }
        }
        
        if (!ValidatorService.isObject(value)) {
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