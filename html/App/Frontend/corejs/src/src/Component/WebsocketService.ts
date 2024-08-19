export class WebsocketService {
    
    isSupported () {
        if ("WebSocket" in window) {
            return true;
        }
        
        return false;
    }
    
    open (url: string | URL, protocols?: string | string[]) {
        if (!this.isSupported()) {
            return false;
        }

        return new WebSocket(url, protocols);
    }
    
    send (context: WebSocket, data: string | ArrayBufferLike | Blob | ArrayBufferView) {
        context.send(data);
    }
    
}