import { ValidatorService } from "./ValidatorService";

export class GamePadService {

    private controllerKeys: Gamepad[];
    private pressedKeyIndex;
    private dynamicKeys;

    setControllerCallback (callback: (arg: any) => {}, controllers: Gamepad[]) {
        const context = this.getContext();
        var i = 0;

        for (i = 0; i < context.length; i++) {
            if (!context[i]) {
                continue;
            }

            if (!(context[i].index in controllers)) {
                callback(context[i]);
            }
        }
    }
    
    getContext (): Gamepad[] {
        return navigator.getGamepads ? navigator.getGamepads() : (navigator.webkitGetGamepads ? navigator.webkitGetGamepads() : []);
    }
    
    setDynamicKeys (keys) {
        this.dynamicKeys = keys;
    }
    
    hasEvent () {
        var padEvent = 'GamepadEvent' in window;
        
        if (padEvent) {
            return true;
        }
        
        return false;
    }
    
    getType (device) {
        let {index, id, button, axis} = device.gamepad;

        return {
            "index": index,
            "id": id,
            "button": button,
            "axis": axis
        }
    }
    
    getAxesArrowIndex (gamePadValue) {
        for (let j in this.controllerKeys) {
            
            const controller = this.controllerKeys[j];
            
            for (var i = 0; i < controller.axes.length; i++) {
                var axes = controller.axes[i];
                
                if (axes > gamePadValue) {
                    var arrow = 1;
                } else if (axes < -gamePadValue) {
                    var arrow = -1;
                } else {
                    var arrow = 0;
                }

                var pressed = false;
                var position = 0;
                
                if (arrow != 0) {
                    pressed = true;

                    if (i <= 1) {
                        var position = -1;
                    } else {
                        var position = 1;
                    }
                }
                
                if (ValidatorService.isObject(axes)) {
                    //pressed = axes.pressed;
                    //axes = axes.value;
                }
                
                if (!pressed) {
                    return false;
                }
                
                return {
                    "arrow": arrow,
                    "pressed": pressed,
                    "pos": position,
                    "key": i
                };
            }
        }
    }
    
    getPressedIndex () {
        for (let j in this.controllerKeys) {
            const controller = this.controllerKeys[j];

            for (let i = 0; i < controller.buttons.length; i++) {
                var button = controller.buttons[i];
                var pressed = button.value == 1.0;

                if (ValidatorService.isObject(button)) {
                    pressed = button.pressed;
                }
                
                if (pressed && !ValidatorService.isUndefined(i) && !$.core.Arr.Search(this.dynamicKeys, i) && controller.buttons[i].pressed == true) {
                    if (pressed && this.pressedKeyIndex != i) {
                        this.pressedKeyIndex = i;
                        return i;
                    }
                } else if (pressed) {
                    this.pressedKeyIndex = i;
                    return i;
                }
            }
        }
    }
    
    Destroy (gamepad) {
        delete this.controllerKeys[gamepad.index];
    }
    
    setControllerKey (key: string, value: string) {
        this.controllerKeys[key] = value;
    }
    
    isConnected () {
        var gp = this.getContext()[0];
        
        if ((gp)) {
            if (gp.connected && this.hasEvent()) return true;
        }
        
        return false;
    }
    
    isKeyExists () {
        if (this.getContext()) {
            return true;
        }
        
        return false;
    }
    
    isButtonPressed (index: number) {
        if (this.isConnected() && this.isKeyExists()) {
            const context = this.getContext()[0];

            if (context.buttons[index].value == 1 && context.buttons[index].pressed) {
                return true;
            }
            
            return false;
        }
    }

}