import AbstractTableCell from './AbstractTableCell.js';

class LocationTableCell extends AbstractTableCell {
    getCssClass() {
        return 'LocationTableCell';
    }

    getCellContent() {
        let parts = [];

        if (this.props.value.building) {
            parts.push(this.props.value.building);
        }
        if (this.props.value.street) {
            parts.push(this.props.value.street);
        }
        return parts.join(' ');
    }
}

export default LocationTableCell;
