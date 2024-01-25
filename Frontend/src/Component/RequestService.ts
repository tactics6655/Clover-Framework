class RequestService {

    public getBlobData(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'blob';
        xhr.onload = function (e) {
            if (this.status == 200) {
                var blob_data = $.core.URL.createObject(this.response);
                return callback(blob_data);
            }
        };
        
        xhr.send();
    }

    public getActiveXObject() {
        return window.ActiveXObject;
    }
    
    public getXMLHttp() {
        let ActiveXList;

        if ($.core.Request.getActiveXObject()) {
            ActiveXList = [
				"MSXML2.XMLHTTP.6.0", 
				"MSXML2.XMLHTTP.5.0", 
				"MSXML2.XMLHTTP.4.0", 
				"MSXML2.XMLHTTP.3.0", 
				"MSXML2.XMLHTTP.2.0", 
				"MSXML2.XMLHTTP"
			];
        } else if (window.XMLHttpRequest) {
            ActiveXList = ['Microsoft.XMLHTTP'];
        }
        
        const length = ActiveXList.length;
        
        for (var i = 0; i < length; i++) {
            try {
                var ActiveX = new(this.getActiveXObject())(ActiveXList[i]);

                return function () {
                    if (ActiveX) {
                        return ActiveX;
                    } else {
                        return new(this.getActiveXObject())(ActiveXList[i]);
                    }
                };
            } catch (e) {}
        }
        
        throw new Error('Ajax is not supported');
    }
      
    public createXhrObject() {
        var xhr;
        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            xhr = this.getXMLHttp();
        }
        
        if ($.core.Validate.isObject(xhr)) {
            return xhr;
        } else {
            return false;
        }
    }

    public xhr(method = 'GET', url, parameter, asynchronous = true) {
        try {
            let xhr = this.createXhrObject();
            
            if (xhr === false) {
                return;
            }
            
            xhr.open(method, url, asynchronous);
            
            if (method == "POST") {
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.setRequestHeader("Content-length", parameter.length);
                xhr.setRequestHeader("Access-Control-Allow-Origin", "*.*");
            }
            
            xhr.send(parameter);
            
            let isOnloadSupportedBrowser = ($.core.Browser.isOpera() || $.core.Browser.isSafari() || $.core.Browser.isGecko());
            
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
        } catch (e) {
            console.log(e)
        }
    }

    public isCachedMethod(type) {
        return (/^(GET|HEAD|POST|PATCH)$/.test(type))
    }
    
    public isSafeMethod(type) {
        return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(type))
    }
    
    public isValidMethod(type) {
        return (/^(GET|POST|HEAD|PUT|DELETE|CONNECT|PATCH|OPTIONS|TRACE)$/.test(type))
    }
}