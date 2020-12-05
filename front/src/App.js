import CallStatusEnum from "./CallStatusEnum";
import Client from './Client.js';
import Notification from './Notification.js';
import ProviderTable from './ProviderTable.js';
import React from 'react';
import SettingsPanel from './SettingsPanel.js';
import SettingsPanelToggle from './SettingsPanelToggle.js';
import SidePanel from './SidePanel.js';

class App extends React.Component {
    static MAX_NOTIFICATIONS = 5;
    static NOTIFICATION_TTL = 3000;
    client = new Client();
    nextNotificationId = 1;

    overrideSettings = {
        accountSid:     '',
        apiKeySid:      '',
        apiKeyToken:    '',
        twilioNumber:   '',
        adminNumber:    '',
        providerNumber: ''
    };

    constructor(props) {
        super(props);
        this.state = {notifications: [], activeCalls: {}, settingsOpen: false};
    }

    componentDidMount() {
    }

    addNotification(notification) {
        let notifications = this.state.notifications;

        if (notifications.length >= App.MAX_NOTIFICATIONS) {
            notifications = notifications.slice(-App.MAX_NOTIFICATIONS + 1);
        }

        notification.id = this.nextNotificationId++;
        notifications.push(notification);
        setTimeout(() => this.removeNotificationById(notification.id), App.NOTIFICATION_TTL);

        this.setState({notifications: notifications});
    }

    removeNotificationById = (id) => {
        let notifications = this.state.notifications;
        let length = notifications.length;

        for (let i = 0; i < length; i++) {
            if (notifications[i].id === id) {
                notifications.splice(i, 1);
                this.setState({notifications: notifications});
                break;
            }
        }
    };

    callOrCancel = (providerId) => {
        let call = this.state.activeCalls[providerId];

        if (call) {
            this.cancelCall(providerId, call.sid);
        } else {
            this.call(providerId);
        }
    };

    call(providerId) {
        let params = {...this.overrideSettings};
        params.providerId = providerId;
        this.client.callMethod('call', params).then(
            result => this.onCallStart(result.data.provider, result.data.call),
            () => this.onCallStartError(providerId)
        );
        //this.onCallStart({providerId: providerId, name: 'Provider Name'}, {sid: '123abc'});
    }

    onCallStart(provider, call) {
        this.setActiveCall(provider.providerId, call);
        this.addNotification({text: 'Calling admin to connect to ' + provider.name});
        this.scheduleCallStatusCheck(provider.providerId, call.sid);
    }

    setActiveCall(providerId, call) {
        let activeCalls = {...this.state.activeCalls};
        activeCalls[providerId] = call;
        this.setState({activeCalls: activeCalls});
    }

    unsetActiveCall(providerId) {
        console.log('Unsetting active call for');
        console.log(providerId);
        let activeCalls = {...this.state.activeCalls};
        delete activeCalls[providerId];
        this.setState({activeCalls: activeCalls});
    }

    onCallStartError(providerId) {
        this.addNotification({text: 'Could not start a call.', className:'error'});
    }

    scheduleCallStatusCheck(providerId, callSid) {
        setTimeout(
            () => this.checkCallStatus(providerId, callSid),
            1000
        );
    }

    checkCallStatus(providerId, callSid) {
        this.client.getCall(callSid)
            .then((result) => this.onCallStatusReceived(providerId, result));
    }

    onCallStatusReceived(providerId, result) {
        if (result.data === null) {
            this.addNotification({text: 'Internal error: call not found on server.', className: 'error'});
            this.unsetActiveCall(providerId);
            return;
        }

        let newCall = result.data;
        let oldCall = this.state.activeCalls[providerId];
        let active = true;

        if (oldCall.status !== newCall.status) {
            active = this.handleCallStatusChange(oldCall, newCall);
        }

        if (!active) {
            this.unsetActiveCall(newCall.providerId);
        } else {
            this.scheduleCallStatusCheck(newCall.providerId, newCall.sid);

            if (oldCall.status !== newCall.status) {
                this.setActiveCall(newCall.providerId, newCall);
            }
        }
    }

    handleCallStatusChange(oldCall, newCall) {
        let notification = null;
        let active = true;

        switch (newCall.status) {
            case CallStatusEnum.ADMIN_CONNECTED:
                notification = {text: 'Admin connected.'};
                break;
            case CallStatusEnum.ADMIN_FAILED:
                notification = {text: 'Admin failed.', className: 'error'};
                active = false;
                break;
            case CallStatusEnum.CALLING_PROVIDER:
                notification = {text: 'Calling the provider.'};
                break;
            case CallStatusEnum.BOTH_CONNECTED:
                notification = {text: 'Both connected.'};
                break;
            case CallStatusEnum.PROVIDER_FAILED:
                notification = {text: 'Provider failed.', className: 'error'};
                active = false;
                break;
            case CallStatusEnum.COMPLETED:
                notification = {text: 'Completed successfully.'};
                active = false;
                break;
        }

        if (notification !== null) {
            this.addNotification(notification);
        }

        return active;
    }

    cancelCall(providerId) {
        this.addNotification({text: 'Cancelling a call is not implemented.', className: 'error'});
    }

    onSettingChange = (name, value) => {
        this.overrideSettings[name] = value;
    };

    toggleSettings = () => {
        this.setState({settingsOpen: !this.state.settingsOpen});
    };

    render() {
        return (
            <div>
                {this.renderNotifications()}
                <SidePanel/>
                <SettingsPanel visible={this.state.settingsOpen} onChange={this.onSettingChange} />
                <SettingsPanelToggle onClick={this.toggleSettings} settingsOpen={this.state.settingsOpen} />
                <ProviderTable app={this} client={this.client} activeCalls={this.state.activeCalls} />
            </div>
        );
    }

    renderNotifications() {
        let notificationElements = [];

        for (let notification of this.state.notifications) {
            notificationElements.push(
                <Notification
                    notification={notification}
                    key={notification.id}
                    onClick={this.removeNotificationById}
                />
            );
        }

        return notificationElements;
    }
}

export default App;
