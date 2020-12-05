import CellTypeEnum from './CellTypeEnum.js';
import LocationComparator from './LocationComparator.js';
import StatusComparator from './StatusComparator.js';
import StringComparator from './StringComparator.js';

class ComparatorFactory {
    stringComparator = new StringComparator();
    statusComparator = new StatusComparator();
    locationComparator = new LocationComparator();

    getComparator(type) {
        switch (type) {
            case CellTypeEnum.STRING:
            case CellTypeEnum.DATE:    // Dates come as strings and are kept that way for now.
                return this.stringComparator;
            case CellTypeEnum.STATUS:
                return this.statusComparator;
            case CellTypeEnum.LOCATION:
                return this.locationComparator;
        }

        return null;
    }
}

export default ComparatorFactory;
