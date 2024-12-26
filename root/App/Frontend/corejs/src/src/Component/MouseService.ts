export class MouseService {

    getEventType () {
        const type = ('ontouchstart' in window ? 'touchend' : 'click');
        
        return type;
    }
    
    getEventPosition (event) {
        let position: any = [];
        
        position.type = event.type;
        if ((event.changedTouches) && (event.changedTouches.length > 0)) {
            position.position = event.changedTouches;
        } else if (event.clientX && event.clientY) {
            position.position = event;
        }
        
        return position;
    }
    
    exitPointerLock () {
        document.exitPointerLock = document.exitPointerLock || document.mozExitPointerLock;

        document.exitPointerLock();
    }
    
    setPointerLockErrorEventListener (callback) {
        if ("pointerlockerror" in document) {
            document.addEventListener('pointerlockerror', callback, false);
        }
        
        if ("mozpointerlockerror" in document) {
            document.addEventListener('mozpointerlockerror', callback, false);
        }
    }
    
    setPointerLockChangeEventListener (callback) {
        if ("onpointerlockchange" in document) {
            document.addEventListener('pointerlockchange', callback, false);
        }
        
        if ("onmozpointerlockchange" in document) {
            document.addEventListener('mozpointerlockchange', callback, false);
        }
    }
    
    isLocked (id) {
        const canvas = document.getElementById(id);

        if (document.pointerLockElement === canvas || document.mozPointerLockElement === canvas) {
            return true;
        } 

        return false;
    }
    
    requestPointerLock (id) {
        const canvas = document.getElementById(id);

        canvas.requestPointerLock = canvas.requestPointerLock || canvas.mozRequestPointerLock;
        canvas.requestPointerLock();
    }
    

}