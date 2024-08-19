export class PromiseService {

    public static isSupported () {
        if (typeof Promise !== "undefined" && Promise.toString().indexOf("[native code]") !== -1) {
            return false;
        }
        
        return true;
    }

    public static getDeferred (): DeferredPromise {
        let _resolve;
        let _reject;
        
        let promise = new Promise(function(resolve, reject) {
            _resolve = resolve;
            _reject = reject;
        });
        
        return {
            'resolve': _resolve,
            'reject': _reject,
            'promise': promise
        };
    }

}