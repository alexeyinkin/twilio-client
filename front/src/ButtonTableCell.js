import AbstractTableCell from './AbstractTableCell.js';

class ButtonTableCell extends AbstractTableCell {
    getCssClass() {
        return 'ButtonTableCell';
    }

    getCellContent() {
        return <button type="button" onClick={this.props.onClick}>{this.props.title}</button>;
    }
}

export default ButtonTableCell;
