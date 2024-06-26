export class MidiService {

    isSupported() {
        if (navigator.requestMIDIAccess) {
            return true;
        }

        return false;
    }

    requestAccess(options?: MIDIOptions): Promise<MIDIAccess>|boolean {
        if (!this.isSupported) {
            return false;
        }

        return navigator.requestMIDIAccess(options);
    }

}