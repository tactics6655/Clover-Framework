export default class EventDispatcher {

    private events = {};

    constructor() {
        this.events = [];
    }

    addListener(eventName, closure, priority = 0) {
        if (typeof this.events[eventName] === 'undefined') {
            this.events[eventName] = [];
        }

        if (typeof closure !== 'function') {
            throw new Error('Method of closure is not callable');
        }

        this.events[eventName].push(closure);
    }

    isExists(eventName: string) {
        return (typeof this.events[eventName] === 'undefined');
    }

    isCallable() {
        
    }

    dispatch(eventName: string, args: string) {
        if (this.isExists(eventName)) {
            throw new Error('Event is not callable');
        }

        this.events[eventName].map(function (event) {
            if (typeof event(args) !== 'function') {
                return true;
            }

            event(args);
        });
    }

    removeListeners(eventName) {
        this.events[eventName] = [];
    }

    removeListener(eventName, closure) {
        if (this.isExists(eventName)) {
            throw new Error('Event is not callable');
        }

        this.events = this.events[eventName].filter(function (event) {
            return event != closure;
        })
    }

    unsubscribe() {

    }

    subscribe() {

    }

    emit() {

    }
    
    sort(eventName: string) {
        this.events = this.events[eventName].sort(function (prev: any, next: any) {
            return prev.priority > next.priority;
        });
    }

}