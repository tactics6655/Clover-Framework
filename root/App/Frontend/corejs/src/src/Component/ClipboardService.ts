export class ClipboardService {

    constructor() {

    }

    setData (text, message) {
        if ((window.attachEvent || window.addEventListener) && navigator.userAgent.toLowerCase().indexOf('msie') !== -1) {
            window.clipboardData.setData(text);

            return true;
        }

        prompt(message, text);
    }

    copy (text) {
        if (window.clipboardData && window.clipboardData.setData) {
            return window.clipboardData.setData("Text", text); 
        }

        if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            let textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();

            try {
                return document.execCommand("copy");  // Security exception may be thrown by some browsers.
            } catch (e) {
            } finally {
                document.body.removeChild(textarea);
            }

            return false;
        }
    }
}