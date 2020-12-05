import React from 'react';
import IconAndText from './IconAndText.js';
import IconAndTextDropdown from './IconAndTextDropdown.js';

class Select extends React.Component {
    static instances = {};
    static nextId = 1;

    constructor(props, items) { // TODO: pass items in props
        super(props);
        this.id = Select.nextId++;
        this.items = items;
        this.state = {value: props.value, open: false};

        this.valuesToItems = {};
        for (let item of this.items) {
            this.valuesToItems[item.value] = item;
        }
    }

    componentDidMount() {
        Select.instances[this.id] = this;
    }

    componentWillUnmount() {
        delete Select.instances[this.id];
    }

    static onBodyClick(e) {
        let idToKeepOpen = null;
        let node = e.target;

        while (node) {
            if (node.classList.contains('Select')) {
                idToKeepOpen = node.getAttribute('selectid');
            }

            node = node.parentElement;
        }

        Select.closeInstancesOtherThan(idToKeepOpen);
    }

    static closeInstancesOtherThan(idToKeepOpen) {
        for (let id in Select.instances) {
            if (id !== idToKeepOpen) {
                Select.instances[id].close();
            }
        }
    }

    setValue = (value) => {
        this.setState({value: value});
        if (this.props.onChange) {
            this.props.onChange(value);
        }
    };

    toggle = () => {
        this.setState({open: !this.state.open});
    };

    close = () => {
        this.setState({open: false});
    };

    getTitleByValue(value) {
        let item = this.valuesToItems[value];
        let title = item ? item.title : '...';

        if (this.props.titleTemplate) {
            title = this.props.titleTemplate.replace('TITLE', title);
        }

        return title;
    };

    render() {
        let value = this.state.value;
        let onSelect = (e, value) => { this.setValue(value); this.close(); };
        let valueClass = 'SelectValue SelectValue' + (this.state.open ? '_open' : '_close');

        return (
            <div className="Select" selectid={this.id}>
                <IconAndText
                    name={value}
                    title={this.getTitleByValue(value)}
                    onClick={this.toggle}
                    className={valueClass}
                />
                <div className="SelectDropdownToggle" onClick={this.toggle}>⌄</div>
                <IconAndTextDropdown
                    value={value}
                    items={this.items}
                    open={this.state.open}
                    onSelect={onSelect}
                />
            </div>
        )
    }
}

document.addEventListener(
    'click',
    Select.onBodyClick
);

export default Select;
