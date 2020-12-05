import ComparatorFactory from './ComparatorFactory.js';
import React from 'react';
import SortableTableHeaderRow from './SortableTableHeaderRow.js';
import SortableTableSelect from './SortableTableSelect.js';
import SortOrderEnum from './SortOrderEnum.js';
import TableRow from './TableRow.js';

class SortableTable extends React.Component {
    comparatorFactory = new ComparatorFactory();

    constructor(props, columns, idProperty) {
        super(props);
        this.state = {rows: [], sortColumnName: null, sortOrder: null};
        this.columns = columns;
        this.idProperty = idProperty;
    }

    componentDidMount() {
        this.sortIfShould();
    }

    sortIfShould() {
        if (this.state.sortColumnName) {
            this.sort(this.state.sortColumnName, this.state.sortOrder);
        }
    }

    sort = (columnName, order) => {
        let column = this.getColumnByName(columnName);
        let comparator = this.comparatorFactory.getComparator(column.type);
        let rows = this.state.rows;

        if (comparator === null) {
            return;
        }

        rows.sort((rowA, rowB) => {
            let a = rowA[columnName];
            let b = rowB[columnName];
            let multiplier = order === SortOrderEnum.DESC ? -1 : 1;
            return comparator.compare(a, b) * multiplier;
        });

        this.setState({rows: rows, sortColumnName: columnName, sortOrder: order});
    };

    getColumnByName(columnName) {
        for (let column of this.columns) {
            if (column.name === columnName) {
                return column;
            }
        }

        return undefined;
    }

    onButtonClick = (rowId, columnName) => {
        // Override this.
    };

    setCellValue = (rowId, columnName, value) => {
        // Override this to save changes.
    };

    render() {
        let rowElements = this.state.rows.map(
            (row) => {
                let rowId = row[this.idProperty];

                return <TableRow
                    data={row}
                    key={rowId}
                    columns={this.columns}
                    onCellValueChange={(columnName, value) => this.setCellValue(rowId, columnName, value) }
                    onButtonClick={(columnName) => this.onButtonClick(rowId, columnName)}
                    call={this.props.activeCalls[rowId]}
                />;
            }
        );

        return (
            <div className="SortableTable CenterPanel">
                <SortableTableHeaderRow columns={this.columns} sortColumnName={this.state.sortColumnName} sortOrder={this.state.sortOrder} onSort={this.sort} />
                <div className="SortableTableSelect">
                    <SortableTableSelect
                        columns={this.columns}
                        value={this.state.sortColumnName}
                        onChange={this.sort}
                        titleTemplate="Sort by TITLE"
                    />
                </div>
                <div className="SortableTableRows">
                    {rowElements}
                </div>
            </div>
        );
    }
}

export default SortableTable;
