import React from 'react';

class IconAndText extends React.Component {
    render() {
        let iconClassName = 'Icon Icon_' + this.props.name;

        return (
            <div onClick={this.props.onClick} className={this.props.className}>
                <div className={iconClassName}></div>
                <span>
                    {this.props.title}
                </span>
            </div>
        );
    }
}

export default IconAndText;
