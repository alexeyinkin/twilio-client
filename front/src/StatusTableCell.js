import AbstractTableCell from './AbstractTableCell.js';
import StatusSelect from './StatusSelect.js';

class StatusTableCell extends AbstractTableCell {
    getCssClass() {
        return 'StatusTableCell';
    }

    getCellContent() {
        return <StatusSelect value={this.props.value} onChange={this.props.onChange} />
    }
}

export default StatusTableCell;
