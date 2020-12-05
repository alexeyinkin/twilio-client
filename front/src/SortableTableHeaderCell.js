import React from 'react';
import SortOrderEnum from "./SortOrderEnum";

class SortableTableHeaderCell extends React.Component {
    render() {
        let onClick = (
            e => this.props.onSort(
                this.props.column.name,
                this.props.sortOrder === SortOrderEnum.DESC ? SortOrderEnum.ASC : SortOrderEnum.DESC
            )
        );
        let className = 'SortableTableHeaderCell';

        if (this.props.sorted) {
            className += ' SortableTableHeaderCell_' + (this.props.sortOrder || SortOrderEnum.ASC);
        }

        return (
            <div onClick={onClick} className={className}>
                {this.props.column.title}&nbsp;
            </div>
        );
    }
}

export default SortableTableHeaderCell;
