import Select from "./Select.js";

class SortableTableSelect extends Select {
    constructor(props) {
        let items = [];

        for (let column of props.columns) {
            if (column.title) { // Skip buttons column. TODO: Decide by cell type.
                items.push({value: column.name, title: column.title});
            }
        }

        super(props, items);
    }
}

export default SortableTableSelect;
