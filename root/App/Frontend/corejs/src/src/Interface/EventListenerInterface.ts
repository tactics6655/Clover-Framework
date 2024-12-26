export interface EventListenerInterface {
    removeListeners(event: string): boolean;
    removeListener(event: string, callback: Function): boolean;
    addListener(event: string, callback: Function): void;
}
