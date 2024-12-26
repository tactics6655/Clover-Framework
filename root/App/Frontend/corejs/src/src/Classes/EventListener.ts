import { EventListenerInterface } from "../Interface/EventListenerInterface";

export class EventListener implements EventListenerInterface {

    public events: Array<any>;

    constructor() {
        this.events = [];
    }

    public removeListeners(event: string): boolean {
        if (this.events[event] === undefined) {
            return false;
        }

        delete this.events[event];

        return true;
    }

    public removeListener(event: string, callback: Function): boolean {
        if (this.events[event] === undefined) {
            return false;
        }

        this.events[event].listeners = this.events[event].listeners.filter(listener => {
            return listener.toString() !== callback.toString();
        })

        return true;
    }

    public addListener(event: string, callback: Function): void {
        if (this.events[event] === undefined) {
            this.events[event] = {
                listeners: []
            }
        }

        this.events[event].listeners.push(callback);
    }

    public dispatch(event: string, details: any) {
        if (this.events[event] === undefined) {
            return;
        }

        this.events[event].listeners.forEach((listener) => {
            listener(details);
        })
    }

}
