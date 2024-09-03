export class WebDatabase {

    isSupported () {
        if (window.openDatabase) {
            return true;
        }
        
        return false;
    }

    getIndexedDB () {
        return window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
    }
    
    getIDBTransaction () {
        return window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction;
    }
    
    getIDBKeyRange () {
        return window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;
    }

    open () {
        let context: WebDatabase = null;

        if (!this.isSupported ()) {
            return false;
        }

        window.openDatabase();

        return true;
    }

}