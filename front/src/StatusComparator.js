import Status from './Status.js';

class StatusComparator {
    statusesInOrder = [
        Status.PASSED,
        Status.DID_NOT_PASS,
        Status.NOT_COMPLETE
    ];

    compare(a, b) {
        return this.statusesInOrder.indexOf(a) - this.statusesInOrder.indexOf(b);
    }
}

export default StatusComparator;
