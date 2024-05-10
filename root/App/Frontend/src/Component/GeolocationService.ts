export class GeolocationService {

    isSupport () {
        return (navigator.geolocation) ? true : false;
    }

    getCurrentPosition (onSuccess: PositionCallback, onError: PositionErrorCallback) {
        if (!this.isSupport()) {
            return false;
        }
        
        const options: PositionOptions = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };
        
        navigator.geolocation.getCurrentPosition(onSuccess, onError, options);
    }

    watchPosition (onSuccess: PositionCallback, onError: PositionErrorCallback) {
        if (!this.isSupport()) {
            return false;
        }
        
        const options: PositionOptions = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };
        
        navigator.geolocation.watchPosition(onSuccess, onError, options);
    }
}