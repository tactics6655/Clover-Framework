export class CookieService {

    public static removeData (cName) {
        if (!this.isSupported ()) {
            return false;
        }

        var dateFormat = new Date();
        dateFormat.setDate(dateFormat.getDate() - 1);
        var cookies = cName + '=' + '; expires=' + dateFormat.toGMTString() + '; path=/';
        
        return this.setData(cookies);
    }
    
    public static isSupported () {
        return navigator.cookieEnabled;
    }

    public static setData (data: string) {
        document.cookie = data;

        if (document.cookie.length == 0) {
            return false;
        }

        return true;
    }
    

    public static getItem (name) {
        if (!this.isSupported ()) {
            return false;
        } 
        
        var cookies = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
        return !cookies ? "" : decodeURIComponent(cookies[2]);
    }
    
    public static setItem (name: string, value: any, expiredDate: number) {
        if (!this.isSupported ()) {
            return false;
        } 

        var dateFormat = new Date();
        dateFormat.setDate(dateFormat.getDate() + expiredDate);

        var cookies = name + '=' + escape(value) + '; path=/ ';
        cookies += ';domain=' + window.location.hostname;
        if (typeof expiredDate != 'undefined') {
            cookies += ';expires=' + dateFormat.toGMTString() + ';';
        }
        
        return this.setData(cookies);
    }

}