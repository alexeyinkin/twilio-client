import React from 'react';
import SortableTableHeaderCell from './SortableTableHeaderCell.js';

class SortableTableHeaderRow extends React.Component {
    render() {
        let cellElements = [];

        for (let column of this.props.columns) {
            let sorted = (this.props.sortColumnName === column.name);
            let sortOrder = sorted ? this.props.sortOrder : undefined;

            cellElements.push(
                <SortableTableHeaderCell
                    column={column}
                    key={column.name}
                    sorted={sorted}
                    sortOrder={sortOrder}
                    onSort={this.props.onSort}
                />
            );
        }

        return (
            <div className="SortableTableHeaders">
                <div className="SortableTableHeaderRow">
                    {cellElements}
                </div>
            </div>
        );
    }
}

export default SortableTableHeaderRow;
