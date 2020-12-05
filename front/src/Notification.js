import React from 'react';

class Notification extends React.Component {
    render() {
        let className = 'Notification Notification_' + (this.props.notification.className || '');
        let onClick = () => this.props.onClick(this.props.notification.id);

        return (
            <div className={className} onClick={onClick}>
                {this.props.notification.text}
            </div>
        );
    }
}

export default Notification;
