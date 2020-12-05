import React from 'react';

class AbstractTableCell extends React.Component {
    render() {
        return <div className={this.getCssClass(this.props.columnName)}>{this.getCellContent()}</div>
    }

    getCellContent() {
        throw 'Override this method';
    }

    getCssClass() {
        throw 'Override this method';
    }
}

export default AbstractTableCell;
