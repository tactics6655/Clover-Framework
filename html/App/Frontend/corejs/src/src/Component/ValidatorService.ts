import * as RegexRules from './../Variables/Regex';

export class ValidatorService {
    
    public static getType (str) {
        if (this.isUndefined(str)) {
            return 'undefined';
        }
        
        if (this.isWindow(str)) {
            return 'window';
        }
        
        if (this.isDate(str)) {
            return 'date';
        }
        
        if (this.isNumeric(str)) {
            if (this.isRRN(str)) {
                return 'rrn';
            } else {
                return 'number';
            }
        }
        
        if (this.isBool(str)) {
            return 'bool';
        }
        
        if (this.isArray(str)) {
            return 'array';
        }
        
        if (this.isRegex(str)) {
            return 'regex';
        }
        
        if (this.isFunc(str)) {
            return 'function';
        }
        
        if (this.isObject(str)) {
            return 'object';
        }
        
        if (this.isStr(str)) {
            if (this.isJapan(str)) {
                return 'japan';
            } else if (this.isHiragana(str)) {
                return 'hiragana';
            } else if (this.isKatakana(str)) {
                return 'katakana';
            } else if (this.isKor(str)) {
                return 'kor';
            } else if (this.isJSON(str)) {
                return 'json';
            } else if (this.isTime(str)) {
                return 'time';
            } else if (this.isURL(str)) {
                return 'url';
            } else if (this.isWeekday(str)) {
                return 'weekday';
            } else if (this.isEmail(str)) {
                return 'email';
            } else {
                return 'string';
            }
        }
    }

    public static isJapan (str) {
        return str.match(RegexRules.regJapan) ? true : false;
    }
    
    public static isHiragana (str: any) {
        return str.match(RegexRules.regHiragana) ? true : false;
    }
    
    public static isKatakana (str: any) {
        return str.match(RegexRules.regKatakana) ? true : false;
    }
    
    public static isKor (str: any) {
        return str.match(RegexRules.regOnlyKor) ? true : false;
    }
    
    public static isURL (str: any) {
        return str.match(RegexRules.regUrl) ? true : false;
    }
    
    public static regWhiteSpace (str) {
        return str.match(RegexRules.regWhiteSpace) ? true : false;
    }
    
    public static isEmail (str) {
        return str.match(RegexRules.regEmail) ? true : false;
    }
    
    public static isArrayBuffer (buff) {
        return toString.call(buff) === '[object ArrayBuffer]';
    }
    
    public static isRRN (str) {
        return str.match(RegexRules.regRRN) ? true : false;
    }
    
    public static isJSON (str) {
        return $.core.JSON.isJSON(str);
    }
    
    public static isWeekday (str) {
        try {
            var tmp = str.split(",");
            var length = tmp.length;
            for (var i = 0; i < length; i++) {
                if (tmp[i].length > 1) {
                    return false;
                }
                
                if (isNaN(tmp[i]) == true) {
                    return false;
                }
                
                if (tmp[i] > 7 || tmp[i] < 1) {
                    return false;
                }
            }
            
            return str;
        } finally {
            tmp = null; 
            length = null; 
        }
    }
    
    public static getJosa (str, tail) {
        var strTemp = str.substr(str.length - 1);
        return ((strTemp.charCodeAt(0) - 16) % 28 != 0) ? str + tail.substr(0, 1) : str + tail.substr(1, 1);
    }
    
    public static isWindow (elem) {
        return null != elem && elem == elem.window && toString.call(elem) === '[object Window]';
    }
    
    public static isEmptyObject (obj) {
        for (var c in obj) return !1;
        return !0
    }
    
    public static isPromiseLike (obj) {
        return obj && this.isFunc(obj.then);
    }
    
    public static isFormData (form) {
        return toString.call(form) === '[object FormData]';
    }
    
    public static isFile (file) {
        return toString.call(file) === '[object File]';
    }
    
    public static isBlob (blob) {
        return toString.call(blob) === '[object Blob]';
    }
    
    public static isBlobBuilder (blob: any) {
        return toString.call(blob) === '[object BlobBuilder]';
    }
    
    public static isNumeric (num: any) {
        return 0 <= num - parseFloat(num);
    }
    
    public static isUndefined (value: any) {
        return typeof value === 'undefined'; //val === void 0
    }
    
    public static isBool (value) {
        return typeof value === 'boolean';
    }
    
    public static isArray (value: any) {
        return value instanceof Array && value.constructor === Array && toString.call(value) === '[object Array]';
    }
    
    public static isNull (value: any) {
        return value == undefined || value == null || value == 'null' || value.toString().replace(/ /g,"") == "";
    }
    
    public static isDate (value: any) {
        return toString.call(value) === '[object Date]' && value instanceof Date;
    }
    
    public static isRegex (value: any) {
        return value instanceof RegExp && toString.call(value) === '[object RegExp]';
    }
    
    public static isStr (value, mode = null) {
        if (mode == 'object') {
            return this.isStr(value) || "[object String]" === Object.prototype.toString.call(value);
        } 

        return typeof value === 'string' && String(value) === value;
    }
    
    public static isFunc (value) {
        return typeof value === 'function' && {}.toString.call(value) === '[object Function]';
    }
    
    public static isObject (value, mode = null) {
        if (mode == 'object') {
            return this.isObject(value) && "[object Object]" === Object.prototype.toString.call(value);
        }

        return typeof value === 'object';
    }
    
    public static isNum (value, mode = null) {
        if (mode == 'object') {
            return this.isNum(value) || "[object Number]" === Object.prototype.toString.call(value);
        }

        return typeof value === 'number' && isFinite(value);
    }
    
    public static isTime (str) {
        if (str == null || str == "") {
            return false;
        } else if (str.length < 4) {
            return false;
        }
        
        var hour = str.substring(0, 2),
            min = str.substring(2);
            
        if (hour > 23) {
            return false;
        } else if (min > 59) {
            return false;
        }
        
        return str;
    }
    
    public static isBlank (str) {
        for (var i = 0; i < str.length; i++) {
            var ch = str.charAt(i);
            if ((ch != ' ') && (ch != '\n') && (ch != '\et')) {
                return false;
            }
        }
        
        return true;
    }
}