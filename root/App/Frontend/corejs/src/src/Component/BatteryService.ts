export class BatteryService {

    public static getManager(): Promise <any> {
        if (!navigator.getBattery) {
            throw new Error("getBattery is not valid function");
        }
        
        return navigator.getBattery();
    }
    
    public static charingTime() {
        let battery;

        this.getManager().then(function (data) {
            console.log(data);
        }).catch (function (error) {
            console.log(error);

        });

        if (battery = this.getManager()) {
            return battery.chargingTime;
        }
        
        return false;
    }
    
    public static dischargingTime() {
        let battery;

        if (battery = this.getManager()) {
            return battery.dischargingTime;
        }

        return false;
    }
    
    public static level(promise: DeferredPromise) {
        this.getManager().then(function (data: BatteryManager) {
            promise.resolve(data.level);
        }).catch (function (error) {
            promise.reject(error);
        });
    }
    
}