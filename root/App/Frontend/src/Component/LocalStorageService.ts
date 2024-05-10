export class LocalStorageService {

    isSupport () {
        if (!window.localStorage) {
            return false;
        }
        
        var isSupport = false;
        
        var key = 'is_support_key';
        var value = 'is_support_value';
        
        if (typeof localStorage !== 'object') {
            return false;
        }

        try {
            localStorage.setItem(key, value);
            let storageData = localStorage.getItem(key);

            if (storageData === value) {
                localStorage.removeItem(key);

                isSupport = true;
            }
        } catch(e) {
            isSupport = false;
        }

        return isSupport;
    }

    removeItem (name) {
        if (!this.isSupport() || !this.getItem(name)) {
            return false;
        }

        try {
            window.localStorage.removeItem(name);

            return true;
        } catch (e) {
        }

        return false;
    }

    setItem (name, value) {
        if (!this.isSupport()) {
            return false;
        }

        try {
            window.localStorage.setItem(name, value);
        } catch (Exception) {
            //if (Exception == QUOTA_EXCEEDED_ERR) {
                return false;
            //}
        }
        
        return true;
    }

    isEmpty (name) {
        if (this.getItem(name) === null) {
            return true;
        }
        
        return false;
    }

    getItem (name) {
        if (!this.isSupport()) {
            return false;
        }

        const item = window.localStorage.getItem(name);
        
        if (!item) {
            return false;
        }
        
        return item;
    }

}