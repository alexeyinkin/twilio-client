import React from 'react';

class SettingRow extends React.Component {
    constructor(props) {
        super(props);
        this.state = {value: this.props.value};
    }

    onChange = (e) => {
        let value = e.target.value;
        this.setState({value: value});
        this.props.onChange(value);
    };

    render() {
        return (
            <div className="SettingRow">
                <div className="SettingName">{this.props.title}:</div>
                <div className="SettingInputDiv">
                    <input value={this.state.value} onChange={this.onChange} />
                </div>
            </div>
        );
    }
}

export default SettingRow;
