import React from 'react';

class SettingsPanelToggle extends React.Component {
    render() {
        let title = this.props.settingsOpen ? 'Close Settings' : 'Settings';

        return (
            <button className="SettingsPanelToggle" type="button" onClick={this.props.onClick}>
                {title}
            </button>
        );
    }
}

export default SettingsPanelToggle;
