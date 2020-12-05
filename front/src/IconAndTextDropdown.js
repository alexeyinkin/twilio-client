import IconAndText from './IconAndText.js';
import React from 'react';

class IconAndTextDropdown extends React.Component {
    render() {
        let divClass = 'IconAndTextDropdown' + (this.props.open ? '_open' : '_close');
        let listItems = [];

        for (let item of this.props.items) {
            let onClick = this.props.onSelect && ((e) => this.props.onSelect(e, item.value));

            listItems.push(
                <IconAndText
                    name={item.value}
                    title={item.title}
                    key={item.value}
                    onClick={onClick}
                    className="IconAndText_dropdown"
                />
            );
        }
        return (
            <div className={divClass}>
                {listItems}
            </div>
        );
    }
}

export default IconAndTextDropdown;
