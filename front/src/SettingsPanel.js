import React from 'react';
import SettingRow from './SettingRow';

class SettingsPanel extends React.Component {
    constructor(props) {
        super(props);

        this.fields = [
            {name: 'accountSid',        title: 'Account SID'},
            {name: 'apiKeySid',         title: 'API Key SID'},
            {name: 'apiKeyToken',       title: 'API Key Token'},
            {name: 'twilioNumber',      title: 'Twilio Number'},
            {name: 'adminNumber',       title: 'Admin Number'},
            {name: 'providerNumber',    title: 'Provider Number'}
        ];
    }

    render() {
        let className = 'SettingsPanel CenterPanel SettingsPanel_' + (this.props.visible ? 'open' : 'close');
        let rowElements = this.fields.map(
            field => <SettingRow
                name={field.name}
                title={field.title}
                value={field.value}
                onChange={value => this.props.onChange(field.name, value)}
                key={field.name}
            />
        );

        return (
            <div className={className}>
                {rowElements}
            </div>
        );
    }
}

export default SettingsPanel;
