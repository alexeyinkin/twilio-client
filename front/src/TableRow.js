import ButtonTableCell from './ButtonTableCell.js';
import CellTypeEnum from './CellTypeEnum.js';
import DateTableCell from './DateTableCell.js';
import LocationTableCell from './LocationTableCell.js';
import React from 'react';
import StatusTableCell from './StatusTableCell.js';
import StringTableCell from './StringTableCell.js';

class TableRow extends React.Component {
    render() {
        let columnElements = [];

        for (let column of this.props.columns) {
            columnElements.push(this.getTableCellElement(column, this.props.data));
        }
        return <div>{columnElements}</div>;
    }

    getTableCellElement(column, data) {
        let name = column.name;
        let value = data[name];

        switch (column.type) {
            case CellTypeEnum.STRING:
                return <StringTableCell value={value} columnName={name} key={name} />;

            case CellTypeEnum.DATE:
                return <DateTableCell value={value} key={name} />;

            case CellTypeEnum.LOCATION:
                return <LocationTableCell value={value} key={name} />;

            case CellTypeEnum.BUTTON:
                let title = this.props.call ? 'Cancel' : 'Call';
                console.log(this.props.call);

                return <ButtonTableCell
                    title={title}
                    key={name}
                    onClick={value => this.props.onButtonClick(name)}
                />;

            case CellTypeEnum.STATUS:
                return <StatusTableCell
                    value={value}
                    key={name}
                    onChange={value => this.props.onCellValueChange(name, value)}
                />;
        }

        return null;
    }
}

export default TableRow;
