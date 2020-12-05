import AbstractTableCell from './AbstractTableCell.js';

class StringTableCell extends AbstractTableCell {
    getCssClass(columnName) {
        return 'StringTableCell StringTableCell_' + columnName;
    }

    getCellContent() {
        return String(this.props.value);
    }
}

export default StringTableCell;
