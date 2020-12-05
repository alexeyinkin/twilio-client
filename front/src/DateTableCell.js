import AbstractTableCell from './AbstractTableCell.js';

class DateTableCell extends AbstractTableCell {
    getCssClass() {
        return 'DateTableCell';
    }

    getCellContent() {
        let date = this.getDate();
        let millisecondsAgo = Date.now() - date.getTime();

        if (millisecondsAgo > 0) {
            if (millisecondsAgo < 1000 * 60 * 60 * 24) {
                return this.formatTimeAgo(millisecondsAgo);
            }
        }

        return this.formatAbsoluteValue();
    }

    formatTimeAgo(millisecondsAgo) {
        if (millisecondsAgo < 1000 * 60 * 60) {
            return Math.floor(millisecondsAgo / 1000 / 60) + ' minutes ago';
        }

        return Math.floor(millisecondsAgo / 1000 / 60 / 60) + ' hours ago';
    }

    formatAbsoluteValue() {
        let date = this.getDate();
        return date.getDate() + ' ' + date.toLocaleString('en-US', { month: 'short', hour: 'numeric', minute: 'numeric', hour12: true });
    }

    getDate() {
        let date = this.props.value;

        if (typeof date === 'string') {
            // https://stackoverflow.com/questions/13363673/javascript-date-is-invalid-on-ios/13363791
            date = date.replace(' ', 'T');
            date = new Date(date);
        }

        return date;
    }
}

export default DateTableCell;
