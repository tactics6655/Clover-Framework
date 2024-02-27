<div id="form">
    <form action="./" method="post" enctype="multipart/form-data">
        <input name="file" type="file" id="file"/>
        
        <input type="submit" value="Submit"/>
    </form>
</div>

<style>
#form {

}
</style>

<script>
class EventDispatcher {

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

    isExists(eventName) {
        return (typeof this.events[eventName] === 'undefined');
    }

    isCallable() {
        
    }

    dispatch(eventName, args) {
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
    
    sort() {
        this.events = this.events[eventName].sort(function (prev, next) {
            return prev.priority > next.priority;
        });
    }

};

class Widget {

    dispatcher = null;

    constructor(dispatcher) {
        this.dispatcher = new EventDispatcher();
        
        this.dispatcher.addListener('exam', this.onMouseMove);

        //this.dispatcher.removeListener('exam', this.onMouseMove);
    }

    retriveDispatcher() {
        return this.dispatcher;
    }

    onMouseMove(ev) {
        console.log(ev);
    }

}

document.addEventListener('DOMContentLoaded', function () {
    var widget = new Widget();

    document.onmousemove = (function (ev) {
        var dispatcher = widget.retriveDispatcher();
        widget.retriveDispatcher().dispatch('exam', ev);
    });
});

</script>