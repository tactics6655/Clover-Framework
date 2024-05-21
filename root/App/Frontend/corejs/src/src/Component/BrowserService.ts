export class BrowserService {

    public isStyleScoped (elem) {
        return void 0 === elem.document.createElement("style").scoped;
    }

    isFileReaderSupported () {
        return (window.File && window.FileList && window.FileReader)
    }
    
    getBlobBuilder () {
        return window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder || window.MSBlobBuilder;
    }

    isFrame (window) {
        var root = window.parent;
        
        if (root == 'undefined') {
            return false;
        }
        
        for (var i = 0; i < root.frames.length; i++) {
            if (window == root.frames[i]) {
                return true;
            }
        }
        
        return false;
    }

    public static isContextMenuSupport () {
        if ('contextMenu' in document && 'HTMLMenuItemElement' in window) {
            return true;
        }
        
        return false;
    }

    public static isDownloadSupport () {
        if (!window.externalHost && 'download' in document.createElement('a')) {
            return true;
        }
        
        return false;
    }

    isMobile () {
        var filter = "win16|win32|win64|mac|macintel";
        
        if (navigator.platform) {
            var isMobile = (filter.indexOf(navigator.platform.toLowerCase()) < 0) ? true : false;
        }
        
        var a = navigator.userAgent || 
                navigator.vendor || 
                window.opera;
        
        if (/(android|bb\d+|meego).+mobile|avantgo|webos|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) {
            var _isMobile = true;
        } else {
            var _isMobile = false;
        }
        
        return _isMobile || isMobile;
    }
    isLGUSeries() {
        var UserAgent = navigator.userAgent.toLowerCase();
        
        if (
            UserAgent.match("u8[000|110|120|130|180|210|260|330|360|380|500]") ||
            UserAgent.match("u[300|400|830|900|960|990]")
        ) {
            return true;
        }
        
        return false;
    }
    
    isLGGSeries() {
        var UserAgent = navigator.userAgent.toLowerCase();
        
        if (UserAgent.match("g[1100|1500|1610|1800|3000|3100|4011|4015|4020|4050|5220c|5300|5300i|5310|5400|5500|5600|6070|7000|7020|7030|7050|7100|7200|8000|210|510|510w|650|912]")) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyMega() {
        var UserAgent = navigator.userAgent.toLowerCase();
        
        if (UserAgent.match("gt-i[9150|9152|9200|9205]")) {
            return true;
        }
        
        return false;
    }
    
    /* Smartphones */
    isGalaxyS() {
        var UserAgent = navigator.userAgent.toLowerCase();
        
        if (
            UserAgent.match("gt-i9000[b|m|t|m4]") || 
            UserAgent.match("gt-i9003") || 
            UserAgent.match("gt-i90[09|88]") || 
            UserAgent.match("sch-i[909-919]") || 
            UserAgent.match("sc-02b") || 
            UserAgent.match("shw-m1[10s|30k|30l]")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxySDuos() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (UserAgent.match("gt-s7562i")) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyII() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("gt-i9100[g|t|m|p]") || 
            UserAgent.match("gt-o9210t") || 
            UserAgent.match("sgh-i[757m|727r|9108|927|929|727|777]") || 
            UserAgent.match("sgh-t[989d|989]") || 
            UserAgent.match("isw11sc") || 
            UserAgent.match("sc-02c") || 
            UserAgent.match("shw-m250[k|l|s]") || 
            UserAgent.match("sph-d710") || 
            UserAgent.match("sch-r989") || 
            UserAgent.match("gt-i9105")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyIII() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("gt-i[9300|9300t|9305|905n|9305t|9308|9301l]") || 
            UserAgent.match("shv-e210[k|l|s]") || 
            UserAgent.match("sgh-[t999|t999l|t999v|i747|i747m|n064|n035]") || 
            UserAgent.match("sch-[j021|r530|i535|s960l|s968c|i939]") || 
            UserAgent.match("sc-[03e|06d]") || 
            UserAgent.match("scl21")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyS4() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("gt-i[9500|9505|9506|9595g|9508|9502]") || 
            UserAgent.match("shv-e3[00k|00l|00s|30k|30l|30s]") || 
            UserAgent.match("sgh-[i337|m919|n045|i337m|m919v]") || 
            UserAgent.match("sch-[i515|r970|i959|r970x|r970c]") || 
            UserAgent.match("sph-l720") || 
            UserAgent.match("sc-04e")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyS5() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("sm-g900[h|f|fd|i|k|l|s|m|md|w8|t|t1|a|v|r2|p|6w|8v|9d|d|j]") || 
            UserAgent.match("sc-04f") || 
            UserAgent.match("slc23")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyS7() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("sm-g930[f|fd|w9|s|k|l|0|v|a|az|p|t|r4|8|u]") || 
            UserAgent.match("sm-g935[0|v|a|p|t|u|r4|f|fd|w8|s|k|l]") || 
            UserAgent.match("sc-02h") || 
            UserAgent.match("scv33")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyS8() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("sm-g930[f|fd|w9|s|k|l|0|v|a|az|p|t|r4|8|u]") || 
            UserAgent.match("sm-g935[0|v|a|p|t|u|r4|f|fd|w8|s|k|l]") || 
            UserAgent.match("sc-02h") || 
            UserAgent.match("scv33")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyCore() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("sm-g[350|3502|386f|g360p]") || 
            UserAgent.match("shw-m580d") || 
            UserAgent.match("gt-i8262d") || 
            UserAgent.match("sch-i829")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyNoteTablet() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("gt-n[5100|5110|5120|8000|8010|8020]") || 
            UserAgent.match("sm-p[600|601|605|900|905]")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyNotePhabletsLTE() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("gt-n[7005|7105]") || 
            UserAgent.match("sm-n[9005|7505]")
        ) {
            return true;
        }
        
        return false;
    }
    
    isGalaxyNotePhablets3G() {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (
            UserAgent.match("gt-n[7000|7100]") || 
            UserAgent.match("sm-n[9000|7500|910g|n915g|n920]")
        ) {
            return true;
        }
        
        return false;
    }

    isReferenceMobile () {
        return navigator.userAgent.match(/Android/i) || 
               navigator.userAgent.match(/webOS/i) || 
               navigator.userAgent.match(/iPhone/i) || 
               navigator.userAgent.match(/iPad/i) || 
               navigator.userAgent.match(/iPod/i);
    }

    public static hasTouchScreen () {
        if (window.PointerEvent && ('maxTouchPoints' in navigator)) {
            if (navigator.maxTouchPoints > 0) {
                return true;
            }
        }
    
        if (window.matchMedia && window.matchMedia("(any-pointer:coarse)").matches) {
            return true;
        } else if (window.TouchEvent || ('ontouchstart' in window)) {
            return true;
        }

        return false;
    }

    public static isFileProtocol () {
        var location = window.location;
        
        return (location.protocol === ('file:') ? true : false);
    }

    public static isLocalhost () {
        //[::1] is the IPv6 localhost address.
        var location = window.location;

        return ((
            location.hostname.match('localhost') || 
            location.hostname.match('[::1]') || 
            location.hostname.match('192.168.0.') ||
            location.hostname.match('127.0.0.1')
        ) ? true : false);
    }

    public static getPerformance () {
        return window.performance || 
            window.mozPerformance || 
            window.msPerformance || 
            window.webkitPerformance || {};
    }

    isWebVideo (url) {
        return /\.webm$|\.mp4$/.test(url);
    }
    
    public static getFirstScriptTag () {
        return document.getElementsByTagName('script')[0];
    }

    public static isTouchable () {
        return "ontouchstart" in document.documentElement;
    }

    public static hasPointerEvents () {
        var elem = document.createElement('div');
        var docElem = document.documentElement;
        
        if (!('pointerEvents' in elem.style)) {
            return false;
        }
        
        elem.style.pointerEvents = 'auto';
        elem.style.pointerEvents = 'x';
        docElem.appendChild(elem);
        
        var isSupports = window.getComputedStyle && window.getComputedStyle(elem, '').pointerEvents === 'auto';
        docElem.removeChild(elem);
        
        return !!isSupports;
    }

    public static getCharacterSet () {
        if (document.charset) {
            return document.charset.toLowerCase();
        } else if (document.characterSet) {
            return document.characterSet.toLowerCase();
        }
    }

    public static isTouchSupport () {
        return "createTouch" in document;
    }
    public static is64Bit () {
        var agent = navigator.userAgent;
        
        if (agent.indexOf("x64") != -1) {
            return true;
        }

        return false;
    }

    public static getPerformTiming () {
        var perform = this.getPerformance();
        
        return perform.timing;
    }

    public static getAllPerformanceType () {
        var performanceTypes = new Array();
        var perform = this.getPerformance();
        for (var value in perform) {
            performanceTypes.push(value);
        }
        
        return performanceTypes;
    }

    public static redirectToCompleteHost () {
        var host = location.host.toLowerCase();
        var url = location.href;
        
        if (host.indexOf("www")== -1) {
            location.href = url.replace("//","//www.");
        }
    }

    public static isChromeApp () {
        return (
            window.chrome || 
            window.chrome.storage
        );
    }

    isEmbededObject (id: string) {
        var isEO = false;
        var obj = document.getElementById(id);
        
        if (obj && (obj.nodeName === "OBJECT" || obj.nodeName === "EMBED")) {
            isEO = true
        }
        
        return isEO;
    }

    public static addAmbientLightEventHandler (callback) {
        if (!this.hasAmbientLight()) {
            return false;
        }
    
        /*$.core.Evt.addListener(window, 'devicelight', function (event) {
            callback(event);
        });*/
    }

    public static hasAmbientLight () {
        if ('ondevicelight' in window) {
            return true;
        }
        
        return false;
    }

    public static getLanguage () {
        return navigator.language || navigator.userLanguage;
    }

    public static getCreateShadowRoot () {              
        return window.createShadowRoot || window.webkitCreateShadowRoot;
    }

    public static isMozila () {
        try {
            if (jQuery.browser.mozilla) {
                return true;
            }
            
            return false;
        } catch (e) {
            return false;
        }
    }

    public static bookmark (title: string, url: string) {
        if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
            alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Command/Cmd' : 'Ctrl') + window.lang['favorite']);
        } else if (window.sidebar && window.sidebar.addPanel) {
            window.sidebar.addPanel(title, url, '');
        } else if ((window.sidebar && (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)) || (window.opera && window.print)) {
            var aElements = document.createElement('a');
            aElements.setAttribute('href', url);
            aElements.setAttribute('title', title);
            aElements.setAttribute('rel', 'sidebar');
            aElements.click();
        } else if (window.sidebar && this.isMozila()) {
            jQuery(this).attr('rel', 'sidebar');
        } else {
            if (window.external && ('AddFavorite' in window.external)) {
                //window.external.AddFavorite(url, title);
            } else {
                alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Command/Cmd' : 'Ctrl') + window.lang['favorite']);
            }
        }
    }

    public static getType () {
        var UserAgent = navigator.userAgent.toLowerCase();
        if (UserAgent.indexOf("nokia") != -1) return 'Nokia';
        if (UserAgent.indexOf("sonyericsson") != -1) return 'Sony Ericsson';
        if (UserAgent.indexOf("polaris") != -1) return 'POLARIS';
        if (UserAgent.indexOf("symbian") != -1) return 'Symbian';
        if (UserAgent.indexOf("blackberry") != -1) return 'BlackBerry';
        if (UserAgent.indexOf("shw-m180") != -1) return 'Galaxy Tab';
        if (UserAgent.indexOf("shw-m380") != -1) return 'Galaxy Tab 10';
        //Internet Explorer
        if (UserAgent.indexOf("msie 6.") != -1) return 'MSIE 6.x';
        if (UserAgent.indexOf("msie 7.") != -1) return 'MSIE 7.x';
        if (UserAgent.indexOf("msie 8.") != -1) return 'MSIE 8.x';
        if (UserAgent.indexOf("msie 9.") != -1) return 'MSIE 9.x';
        if (UserAgent.indexOf("msie 10.") != -1) return 'MSIE 10.x';
        if (UserAgent.indexOf("android") != -1) return 'Android';
        //iOS
        if (UserAgent.indexOf("iphone") != -1) return 'iPhone';
        if (UserAgent.indexOf("ipad") != -1) return 'iPad';
        if (UserAgent.indexOf("ipod") != -1) return 'iPod';
        //Microsoft
        if (UserAgent.indexOf("iemobile") != -1) return 'IEMobile';
        if (UserAgent.indexOf("windows ce") != -1) return 'Windows CE';
        if (UserAgent.indexOf("windows phone") != -1) return 'Windows Phone';
        if (UserAgent.indexOf("netscape") != -1) return 'Netscape';
        if (UserAgent.indexOf("msie") != -1) return 'Internet Explorer';
        //General
        if (UserAgent.indexOf("opera") != -1) return 'Opera';
        if (UserAgent.indexOf("chrome") != -1) return 'Chrome';
        if (UserAgent.indexOf("mozilla/5.0") != -1) return 'Mozilla';
        if (UserAgent.indexOf("firefox") != -1) return 'Firefox';
        if (UserAgent.indexOf("opera mobi") != -1) return 'Opera Mobi';
        if (UserAgent.indexOf("opera mini") != -1) return 'Opera Mini';
        if (UserAgent.indexOf("webtv") != -1) return 'WebTV'; //LG
        //Max OS
        if (UserAgent.indexOf("chimera") != -1) return 'Chimera';
        if (UserAgent.indexOf("safari") != -1) return 'Safari';
    }

    public static getAndroidVersion () {
        var UserAgent = navigator.userAgent;

        if (UserAgent.indexOf("Android") > 0) {
            var version = parseFloat(UserAgent.slice(UserAgent.indexOf("Android") + 8));
            return parseInt(String(version));
        }

        return -1;
    }

    public static getDetail () {
        var Browser: any = {};
        var userAgent = navigator.userAgent.toLowerCase();
        
        if (userAgent.indexOf("msie") > -1) {
            Browser.Name = 'Internet Explorer';
            Browser.Version = parseFloat(userAgent.match(/msie (\d+\.\d+)/)[1]);
            if (Browser.Version >= 8 && document.documentMode >= 7) {
                Browser.documentMode = document.documentMode;
            }
        } else if (userAgent.indexOf("firefox") > -1) {
            Browser.Name = 'Firefox';
            Browser.Version = parseFloat(userAgent.match(/firefox\/(\d+\.\d+)/)[1]);
        } else if (userAgent.indexOf("chrome") > -1) {
            Browser.Name = 'Chrome';
            Browser.Version = parseFloat(userAgent.match(/chrome\/(\d+\.\d+)/)[1]);
        } else if (userAgent.indexOf("opera") > -1) {
            Browser.Name = 'Opera';
            Browser.Version = parseFloat(userAgent.match(/opera\/(\d+(\.\d+)?)/)[1]);
        } else if (userAgent.indexOf("applewebkit") > -1) {
            Browser.Name = 'Safari';
            Browser.Version = parseFloat(userAgent.match(/applewebkit\/(\d+(\.\d+)?)/)[1]);
        } else {
            Browser.Name = 'Unknown';
            Browser.Version = '0';
        }
        
        return Browser;
    }

    public static isMacPowerPC () {
        var userAgent = navigator.userAgent.toLowerCase();
        
        if (/(mac_powerpc)/gi.exec(userAgent)) {
            return true;
        }
        
        return false;
    }

    public static isOpenBSD () {
        var userAgent = navigator.userAgent.toLowerCase();
        
        if (/(openbsd)/gi.exec(userAgent)) {
            return true;
        }
        
        return false;
    }

    public static isSunOS () {
        var userAgent = navigator.userAgent.toLowerCase();
        
        if (/(sunos)/gi.exec(userAgent)) {
            return true;
        }
        
        return false;
    }

    public static isLinux () {
        var userAgent = navigator.userAgent.toLowerCase();
        
        if (/(linux)/gi.exec(userAgent)) {
            return true;
        }
        
        return false;
    }

    public static isWindowsOS () {
        var userAgent = navigator.userAgent.toLowerCase();
        
        if (/(windows)|(winnt)|(win98)|(win95)|(win16)/gi.exec(userAgent)) {
            return true;
        }
        
        return false;
    }

    public static getWindowsVersion () {
        if (this.isWindows()) {
            var userAgent = navigator.userAgent.toLowerCase();
            
            if (/(windows nt 6.2)/gi.exec(userAgent)) {
                return "8";
            } else if (/(windows nt 6.1)/gi.exec(userAgent)) {
                return "7";
            } else if (/(windows nt 6.0)/gi.exec(userAgent)) {
                return "vista";
            } else if (/(windows nt 5.2)/gi.exec(userAgent)) {
                return "2003";
            } else if (/(windows nt 5.1)|(windows XP)/gi.exec(userAgent)) {
                return "XP";
            } else if (/(windows nt 5.0)|(windows 2000)/gi.exec(userAgent)) {
                return "2000";
            } else if (/(windows nt 4.0)|(winnt4.0)|(winnt)|(windows nt)/gi.exec(userAgent)) {
                return "4.0";
            } else if (/(windows ME)/gi.exec(userAgent)) {
                return "ME";
            } else if (/(windows 98)|(win98)/gi.exec(userAgent)) {
                return "98";
            } else if (/(windows 95)|(win95)|(windows_95)/gi.exec(userAgent)) {
                return "95";
            } else if (/(win16)/gi.exec(userAgent)) {
                return "3.11";
            }
        }
    }

    public static isSmartPhone () {
        var userAgent = navigator.userAgent.toLowerCase();
        
        if (/(Mobile)|(iPhone)|(Android)/gi.exec(userAgent)) {
            return true;
        }
        
        return false;
    }

    public static pushState (stateObject, title, url) {
        window.top.history.pushState(stateObject, title, url);
    }

    public static getCanvas (id) {
        var canvas = document.getElementById(id);

        if (canvas.getContext) {
            return canvas.getContext('2d');
        }
    }

    public static getUserAgent () {
        return navigator.userAgent;
    }

    public static isSupportTouch () {
        var isAndroid = navigator.userAgent.indexOf('Android') != -1;
        
        return isAndroid || !!('createTouch' in document);
    }

    public static mobileVibrate (ms) {
        var vibrator = this.getVibrator();
        
        if (vibrator.vibrate) {
            vibrator.vibrate(ms);
        }
    }

    public static hasVibrator () {
        return !!(this.getVibrator().vibrate);
    }

    public static getVibrator () {
        return window.navigator.vibrate || 
               window.navigator.webkitVibrate || 
               window.navigator.mozVibrate || 
               window.navigator.msVibrate;
    }

    public static isUserNeedPayCost () {
        if (this.hasConnection()) {
            var connection = this.getConnection();
            
            if (!connection.metered && (connection.type && connection.type == "cellular")) {
                return false;
            }
            
            return false;
        }
        
        return false;
    }

    public static isCellular () {
        if (this.hasConnection()) {
            var connection = this.getConnection();
            
            if (connection && connection.effectiveType=== 'cellular') {
                return true;
            }
        }
        
        return false;
    }

    public static getUserNetworkSpeed () {
        if (this.hasConnection()) {
            var connection = this.getConnection();
            
            return connection.bandwidth;
        } 

        return 0;
    }

    public static hasConnection () {
        const connection = this.getConnection();

        if (connection) {
            return true;
        }
        
        return false;
    }

    public static getConnection () {
        return navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    }

    public static getBoundingHeight (id: string) {
        var height = 0;
        var element = document.getElementById(id);
        var rect = element.getBoundingClientRect();

        if (rect.height) {
            height = rect.height;
        } else {
            height = rect.bottom - rect.height; 
        }
        
        return height;
    }

    public static getTarget (event) {
        return event.srcElement || event.target;;
    }

    public static isSelenium () {
        return window.navigator.userAgent.toLowerCase().indexOf("mozilla/5.0 (selenium; ") !== -1;
    }
    
    public static isWindows () {
        return window.navigator.userAgent.toLowerCase().indexOf("win") !== -1;
    }
    
    public static isFirefox () {
        return window.navigator.userAgent.toLowerCase().indexOf("firefox") !== -1;
    }
    
    public static isNetscape () {
        return window.navigator.userAgent.toLowerCase().indexOf("netscape") !== -1;
    }
    
    public static isOpera () {
        return (
            window.navigator.userAgent.toLowerCase().indexOf("opera") !== -1 || 
            window.navigator.userAgent.toLowerCase().indexOf("opr") !== -1);
    }
    
    public static isChrome () {
        return window.navigator.userAgent.toLowerCase().indexOf("chrome") !== -1;
    }
    
    public static isGecko () {
        return window.navigator.userAgent.toLowerCase().indexOf("gecko") !== -1;
    }
    
    public static isKonqueror () {
        return window.navigator.userAgent.toLowerCase().indexOf("konqueror") !== -1;
    }
    
    public static isSafari () {
        return window.navigator.userAgent.toLowerCase().indexOf("applewebkit") !== -1;
    }
    
    public static isIE () {
        return navigator.userAgent.indexOf("MSIE") > 0 || /msie/i.test(navigator.userAgent);
    }
    
    public static isIOS () {
        return navigator.platform.match(/(iPhone|iPod|iPad)/i);
    }
    
    public static isMacPlatform () {
        return navigator.platform.match(/(Mac|iPhone|iPod|iPad)/i);
    }
    
    public static isBlackBerry () {
        return window.navigator.userAgent.toLowerCase().indexOf("blackberry") !== -1;
    }
    
    public static isMac () {
        return window.navigator.userAgent.toLowerCase().indexOf("mac") !== -1;
    }
}