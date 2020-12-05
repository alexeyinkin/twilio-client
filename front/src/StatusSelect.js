import Select from './Select.js';
import Status from './Status.js';

class StatusSelect extends Select {
    constructor(props) {
        let items = [
            {value: Status.NOT_COMPLETE,    title: 'Not Complete'},
            {value: Status.DID_NOT_PASS,    title: 'Didn\'t Pass'},
            {value: Status.PASSED,          title: 'Passed'}
        ];

        super(props, items);
    }
}

export default StatusSelect;
