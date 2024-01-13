class NotificationService {

    public isSupported() {
        if (!("Notification" in window)) {
            return false;
        }
        
        try {
            const notify = window.Notification || window.webkitNotifications || navigator.mozNotification;

            if (notify) {
                return true;
            }
        } catch (e) {
        }
        
        return false;
    }

    public requestPermission() {
        if (window.Notification.permission !== 'denied') {
            window.Notification.requestPermission(function (permission) {});
        } else if (window.webkitNotifications && window.webkitNotifications.checkPermission) {
            window.webkitNotifications.requestPermission();
        }
    }

    public getPermittedLevel() {
        let permit;

        if (window.webkitNotifications && window.webkitNotifications.checkPermission) {
            permit = Permissions[window.webkitNotifications.checkPermission()];
        } else if (window.Notification && window.Notification.permission) {
            permit = window.Notification.permission;
        } else if (navigator.mozNotification) {
            permit = Notification.permission;
        }
        
        return permit;
    }

    public notify(title: string, message: string, icon: string, body: string, options: any) {
        if (this.getPermittedLevel() === 'denied') {
            this.requestPermission();
            return;
        }

        var notificationHandler;
        var notification = null;

        if (window.Notification) {
            if (!options) {
                options = {}
            }
            
            notificationHandler = new Notification(message, options);
        } else if (window.webkitNotifications) {
            notificationHandler = window.webkitNotifications.createNotification(icon, title, body);
            notificationHandler.show();
        } else if (navigator.mozNotification) {
            notificationHandler = navigator.mozNotification.createNotification(title, body, icon);
            notificationHandler.show();
        }
    }

}