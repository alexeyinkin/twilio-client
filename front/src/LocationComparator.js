import NumberComparator from './NumberComparator.js';
import StringComparator from './StringComparator.js';

class LocationComparator {
    stringComparator = new StringComparator();
    numberComparator = new NumberComparator();

    compare(a, b) {
        let streetResult = this.stringComparator.compare(a.street, b.street);
        if (streetResult !== 0) {
            return streetResult;
        }

        return this.numberComparator.compare(a.building, b.building);
    }
}

export default LocationComparator;
