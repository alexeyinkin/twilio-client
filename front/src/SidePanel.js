import React from 'react';

class SidePanel extends React.Component {
    render() {
        return (
            <div className="SidePanel">
                <div className="SidePanelItems">
                    <div>Daily Summary</div>
                </div>
                <div className="PoweredBy">
                    <div className="PoweredByText">Powered by:</div>
                    <div className="PoweredByLogo"></div>
                </div>
            </div>
        );
    }
}

export default SidePanel;
