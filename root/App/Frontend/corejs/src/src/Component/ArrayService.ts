export class ArrayService {

    enqueue (arr) {
        return arr.push(arr);
    }
    
    dequeue (arr) {
        return arr.shift();
    }
    
    /*
    
    */
    bin2String (array) {
        var result = "";
        for (var i = 0; i < array.length; i++) {
            result += String.fromCharCode(parseInt(array[i], 2));
        }
        
        return result;
    }
    
    forEach (array, callback) {
        if (array && array.length && $.core.Validate.isFunc(callback)) {
            let length = array.length;

            for (var i = 0; i < length; i++) {
                callback(array[i], i, array);
            }
        }
    }
    
    range (N) {
        Array.apply(null, {length: N}).map(function(value, index){
            return index + 1;
        });
    }
    
    /**
     * Check that Array can push
     *
     * @return <Boolean>
     **/
    canPush () {
        if (!new Array().push) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get Minium Value in Array
     *
     * @param <Array> arr : Array
     *
     * @return <Boolean>
     **/
    getMinValue (arr) {
        return Math.min(...arr);
    }
    
    getLessValue (arr, val, min) {
        return arr.find(val => val < min);
    }
    
    /**
     * Get Minium Index in Array
     *
     * @param <Array> arr : Array
     *
     * @return <Integer>
     **/
    getLessIndex (arr, val, min) {
        return arr.findIndex(val => val < min);
    }
    
    getMoreValue (arr, val, min) {
        return arr.find(val => val > min);
    }
    
    getMoreIndex (arr, val, min) {
        return arr.findIndex(val => val > min);
    }
    
    /**
     * Get Random Number
     *
     * @param <Array> arr : Array
     *
     * @return <Integer>
     **/
    getRandom (arr) {
        return arr[Math.floor(arr.length * Math.random())];
    }
    
    sibling (arr, c) {
        for (var result = []; arr; arr = arr.nextSibling) {
            if (arr.nodeType === 1 && arr !== c) {
                result.push(arr);
            }
        }
        
        return result;
    }
    
    /**
     * Get Unique Array Values
     *
     * @return <Array>
     **/
    getUnique (arr) {
        return arr.filter(function (item, i, ar) {
            return ar.indexOf(item) === i;
        });
    }
    
    /**
     * initialize Array
     *
     * @param <Arguments>
     **/
    initArray () {
        var _this = new Array();
        var length = arguments.length;

        for (var i = 0; i < length; i++) {
            if (!$.core.Validate.isUndefined(arguments[i])) {
                _this[i + 1] = arguments[i];
            }
        }
        
        return _this;
    }
    
    locationFrame (id, type, url) {
        var iframe: any = document.getElementById(id);
        if (iframe) {
            var sendData: any = {}
            sendData.type = type;
            sendData.url = url;
            
            var sendDataJson = JSON.stringify(sendData);
            var target = this.getPostMessage(iframe.contentWindow.window);
            
            target.postMessage(sendDataJson, '*');
        }
    }
    
    getPostMessage (target) {
        return (target.postMessage ? target : (target.document.postMessage ? target.document : undefined));
    }
    
    sendLinkToIframe (id, data) {
        var target: any = document.getElementById(id);
        target.contentWindow.window.postMessage(data, '*');
    }
    
    /**
     * Check that Array is equal
     *
     * @param <Array> {arr1}       : Array
     * @param <Array> {arr2}       : Array
     *
     * @return <Boolean>
     **/
    isArrayEqual (arr1, arr2) {
        var bool = (arr1.length == arr2.length) && arr1.every(function (element, index) {
            return element === arr2[index];
        });
        
        return bool;
    }
    
    /**
     * Sortable Array
     *
     * @param <Array> arr : Array
     *
     * @return <Array>
     **/
    sort (arr) {
        var temp = {};
        
        var length = arr.length;
        
        for (var i = 0; i < length; i++) {
            temp[arr[i]] = true;
        }
        
        return Object.keys(temp);
    }
    
    isDef (args) {
        let length = args.length;

        for (var i = 0; i < length; ++i) {
            if ($.core.Validate.isUndefined(args[i])) {
                return false;
            }
        }

        return true;
    }
    
    /**
     * Replace Text to Replace Text in Array
     *
     * @param <Array> arr      : Array
     * @param <String> find    : to Find Text
     * @param <String> replace : Set the Replacement Text
     *
     * @return <Array>
     **/
    replace (arr, find, replace) {
        for (let i = 0; i < arr.length; i++) {
            arr[i] = arr[i].replace(find, replace);
        }
        
        return arr;
    }
    
    /**
     * Find Object in Array
     *
     * @param <Array> arr      : Array
     * @param <Object> obj     : to Find Object
     *
     * @return <Array>
     **/
    findObject (arr, obj) {
        let length = arr.length;
        
        for (var i = 0, len = length; i < len; i++) {
            if (arr[i] == obj) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Array Maximum Number Filter by specify number
     *
     * @param <Array> arr      : Array
     * @param <String> max     : Maximum number
     *
     * @return <Array>
     **/
    filterMax (arr, max) {
        return $.grep(arr, function (n, i) {
            return n > max;
        });
    }
    
    /**
     * Array Minimum Number Filter by specify number
     *
     * @param <Array> arr      : Array
     * @param <String> max     : Minimum number
     *
     * @return <Array>
     **/
    filterMin (arr, min) {
        $.grep(arr, function (n, i) {
            return n > min;
        }, true);
    }
    
    toObj (arr) {
        var result = {};
        for (var i in arr) {
            if (Object.prototype.toString.call(arr[i]) == '[object Array]') {
                result[i] = this.toObj(arr[i]);
            } else {
                result[i] = arr[i];
            }
        }
        
        return result;
    }
    
}