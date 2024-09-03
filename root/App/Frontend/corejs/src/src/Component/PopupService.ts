import {ValidatorService} from './ValidatorService';

export class PopupService {
    open (url: string | URL, width: number, height: number, top: number, left: number) {
        var context = null;

        if (this.isSupported()) {
            context = window.open(url, 'popup', 'top=' + top + ',left=' + left + ',width=' + width + 'px,height=' + height + 'px,status=no,scrollbars=no,toolbar=no,resizable=no,scrollbars=no,location=no');
        }

        return context;
    }

    close (context: Window) {
        if (!ValidatorService.isObject(context)) {
            return;
        }
        
        context.close();
    }

    closeSelf (location) {
        open(location, '_self').close();
    }

    isSupported () {
        var context = window.open('about:blank', 'win', 'width=1, height=1, scrollbars=yes, resizable=yes');
        var isDisabled = !context || context.closed || typeof context.closed == 'undefined';
        
        if (!isDisabled) {
            context.close();
        }
        
        return isDisabled ? false : true;
    }

    openLink (link) {
        if (link) {
            return;
        }

        var url = link.getAttribute("href");

        if (url) {
            return window.open(url);
        }
    }
}