export class MobileDeviceService {

    screenEnabled () {
        if (navigator.mozPower.screenEnabled) {
            return true;
        }
        
        return false;
    }
    
    screenBright (value) {
        if (this.screenEnabled() == true) {
            navigator.mozPower.screenBrightness = value;
        }
    }
    
    screenUnlock () {
        navigator.requestWakeLock('screen');
    }
    
    powerOff () {
        navigator.mozPower.powerOff();
    }

}